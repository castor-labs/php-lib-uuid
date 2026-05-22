---
description: Generate and inspect UUID versions 1, 3, 4, 5, 6, and 7.
---
# UUID versions

Each concrete version class has version-aware `parse()` and `fromBytes()` methods. They return the concrete type when the input has the expected version nibble and throw `Castor\Uuid\ParsingError` otherwise.

## Version 1

`Version1` UUIDs encode a Gregorian timestamp, a clock sequence, and a node identifier. By default they use `System\State\Standard`, which combines the current system clock, a secure random clock sequence, and a MAC address provider.

```php
<?php

use Castor\Uuid\Version1;

$uuid = Version1::generate();

$time = $uuid->getTime();          // Castor\Uuid\System\Time\Gregorian
$node = $uuid->getNode();          // 6-byte ByteArray
$clockSeq = $uuid->getClockSeq();  // 2-byte ByteArray
```

For repeatable generation in tests, pass a fixed state:

```php
<?php

use Castor\Uuid\ByteArray;
use Castor\Uuid\System\State\Fixed;
use Castor\Uuid\System\Time\Gregorian;
use Castor\Uuid\Version1;

$state = new Fixed(
    Gregorian::fromTimestamp('139127190012012330'),
    ByteArray::fromHex('0001'),
    ByteArray::fromHex('00b0d063c226'),
);

Version1::generate($state)->toString();
// 3343a72a-4771-11ee-8001-00b0d063c226
```

## Version 3

`Version3` UUIDs are deterministic. They hash namespace bytes plus a name with MD5, then set the UUID version and variant bits.

```php
<?php

use Castor\Uuid;
use Castor\Uuid\Version3;

$uuid = Version3::create(Uuid\Ns\url(), 'test');

$uuid->toString(); // 1cf93550-8eb4-3c32-a229-826cf8c1be59
```

Use version 3 only when MD5 compatibility is required. Prefer version 5 when you need a deterministic namespace UUID and do not have an MD5 interoperability requirement.

## Version 4

`Version4` UUIDs are random. They contain 128 random bits with 6 bits reserved for UUID version and variant metadata, leaving 122 bits of entropy.

```php
<?php

use Castor\Uuid\Version4;

$uuid = Version4::generate();
```

You can pass a `Random\Randomizer` for deterministic tests or alternate random engines:

```php
<?php

use Castor\Uuid\Version4;
use Random\Engine\Mt19937;
use Random\Randomizer;

Version4::generate(new Randomizer(new Mt19937(1000)))->toString();
// b3a551a7-5712-4d34-8718-711dc00e429c
```

## Version 5

`Version5` UUIDs are deterministic like version 3, but use SHA-1 and keep the first 16 hash bytes.

```php
<?php

use Castor\Uuid;
use Castor\Uuid\Version5;

$uuid = Version5::create(Uuid\Ns\url(), 'test');

$uuid->toString(); // da5b8893-d6ca-5c1c-9a9c-91f40a2a3649
```

Use version 5 for stable identifiers derived from a namespace and a string name.

## Version 6

`Version6` UUIDs use the same Gregorian time, clock sequence, and node components as version 1, but reorder timestamp bits so generated identifiers sort more naturally by creation time.

```php
<?php

use Castor\Uuid\Version6;

$uuid = Version6::generate();

$uuid->getTime();     // Gregorian time
$uuid->getNode();     // node bytes
$uuid->getClockSeq(); // clock sequence bytes
```

A fixed state produces deterministic bytes:

```php
<?php

use Castor\Uuid\ByteArray;
use Castor\Uuid\System\State\Fixed;
use Castor\Uuid\System\Time\Gregorian;
use Castor\Uuid\Version6;

$state = new Fixed(
    Gregorian::fromTimestamp('139127190012012330'),
    ByteArray::fromHex('0001'),
    ByteArray::fromHex('00b0d063c226'),
);

Version6::generate($state)->toString();
// 1ee47713-343a-672a-8001-00b0d063c226
```

## Version 7

`Version7` UUIDs encode a 48-bit Unix millisecond timestamp followed by random bytes. They are usually the best choice for new identifiers that need time locality without exposing MAC addresses.

```php
<?php

use Castor\Uuid\Version7;

$uuid = Version7::generate();

$uuid->getTime(); // Castor\Uuid\System\Time\Unix
```

Pass a Unix timestamp and randomizer when a deterministic value is required:

```php
<?php

use Brick\DateTime\Instant;
use Castor\Uuid\System\Time\Unix;
use Castor\Uuid\Version7;
use Random\Engine\Mt19937;
use Random\Randomizer;

$uuid = Version7::generate(
    Unix::fromInstant(Instant::of(1721172188, 86590000)),
    new Randomizer(new Mt19937(100)),
);

$uuid->toString(); // 0190bddb-5bb6-7896-9c8b-18dbd0ab4337
```

## Why version 2 is not implemented

Version 2 UUIDs are the DCE Security UUID profile. They replace part of the timestamp with local domain data such as POSIX UID or GID values. That makes them less portable, less privacy-preserving, and rarely useful for application-level identifiers. Castor UUID intentionally supports the modern and broadly interoperable versions instead.
