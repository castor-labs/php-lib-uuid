---
description: Understand random, MAC, state, and time abstractions used by generators.
---
# System services

The generator classes keep environment-dependent behavior behind small interfaces. You can use the defaults in applications and pass explicit implementations in tests.

## Random

`Castor\Uuid\System\Random::global()` returns a process-wide `Random\Randomizer` backed by `Random\Engine\Secure`.

It is used by:

- `Version4::generate()` for 16 random bytes.
- `Version7::generate()` for the random suffix.
- `System\State\Standard` for version 1 and 6 clock sequences.
- `System\MacProvider\Fallback` for random multicast node identifiers.

Pass a `Randomizer` directly to version 4 or 7 when you need deterministic tests.

## MacProvider

`System\MacProvider` returns one or more 6-byte node identifiers as `ByteArray` instances. Implementations must not return an empty array.

### FromOs

`System\MacProvider\FromOs` tries to discover MAC addresses from the operating system. On Linux, it reads `/sys/class/net/*/address`. On other systems, or when sysfs does not provide addresses, it falls back to network configuration commands such as `ipconfig`, `ifconfig`, or `netstat` depending on the platform.

`FromOs` caches successful discovery and delegates to a fallback provider when no usable address is found.

### Fallback

`System\MacProvider\Fallback` follows RFC 4122 section 4.5 for systems without an IEEE 802 address. It generates 6 random bytes and sets the least significant bit of the first octet to mark the node as multicast/random rather than a real hardware address.

```php
<?php

use Castor\Uuid\System\MacProvider\Fallback;
use Random\Engine\Secure;
use Random\Randomizer;

$provider = new Fallback(new Randomizer(new Secure()));
$node = $provider->getMacAddresses()[0];
```

## State

`System\State` supplies the three inputs needed by version 1 and version 6 generation:

- `getTime(): System\Time\Gregorian`
- `getClockSequence(): ByteArray`
- `getNode(): ByteArray`

### Standard state

`System\State\Standard::global()` combines:

- `Brick\DateTime\Clock\SystemClock` for current time.
- `Random\Randomizer` with `Secure` for clock sequences.
- `FromOs` with `Fallback` for node selection.

It caches the selected node and clock sequence. When two generated UUIDs see the same timestamp bytes, it refreshes the clock sequence to avoid duplicate version 1 or 6 values.

### Fixed state

`System\State\Fixed` is a readonly implementation for deterministic UUID generation.

```php
<?php

use Castor\Uuid\ByteArray;
use Castor\Uuid\System\State\Fixed;
use Castor\Uuid\System\Time\Gregorian;

$state = new Fixed(
    Gregorian::fromTimestamp('139127190012012330'),
    ByteArray::fromHex('0001'),
    ByteArray::fromHex('00b0d063c226'),
);
```

## Time interface

`System\Time` is shared by Gregorian and Unix time objects. It provides:

- `getInstant()` for a `Brick\DateTime\Instant`.
- `getTimestamp()` for the stored numeric timestamp string.

The concrete time type depends on the UUID version: version 1 and 6 use Gregorian time; version 7 uses Unix millisecond time.
