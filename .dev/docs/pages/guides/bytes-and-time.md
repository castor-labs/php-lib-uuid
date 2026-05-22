---
description: Work with ByteArray, TimeBased UUIDs, Gregorian time, and Unix time.
---
# Bytes and time

## ByteArray

`Castor\Uuid\ByteArray` is a fixed-size byte container based on `SplFixedArray<int<0,255>>`. UUIDs use it for raw bytes, hashes, timestamps, nodes, and clock sequences.

```php
<?php

use Castor\Uuid\ByteArray;

$bytes = ByteArray::fromHex('fa06067f602d404aa34c45c6a7744011');

$bytes->toHex(); // fa06067f602d404aa34c45c6a7744011
$bytes->toRaw(); // raw binary string
$bytes->slice(0, 6);
```

Construction helpers:

- `ByteArray::fromHex(string $hex)` decodes hexadecimal.
- `ByteArray::fromRaw(string $raw)` unpacks a binary string.
- `ByteArray::create(array $bytes)` creates an array from integer bytes.
- `allocate(int ...$bytes)` fills positions in an existing fixed array.

Comparison uses byte content:

```php
<?php

use Castor\Uuid\ByteArray;

ByteArray::fromHex('ee25446ee6bf')->equals(ByteArray::fromHex('ee25446ee6bf'));
// true
```

`Uuid::getBytes()` returns a clone, so callers cannot mutate the bytes stored inside a UUID value object.

## TimeBased UUIDs

`Castor\Uuid\TimeBased` marks UUIDs that can expose their embedded time:

- `Version1` returns `System\Time\Gregorian`.
- `Version6` returns `System\Time\Gregorian`.
- `Version7` returns `System\Time\Unix`.

```php
<?php

use Castor\Uuid;

$uuid = Uuid\Version7::generate();

if ($uuid instanceof Uuid\TimeBased) {
    $instant = $uuid->getTime()->getInstant();
}
```

## Gregorian time

`System\Time\Gregorian` stores an 8-byte count of 100-nanosecond intervals since the Gregorian epoch, `1582-10-15T00:00:00Z`. It is used by versions 1 and 6.

```php
<?php

use Castor\Uuid\System\Time\Gregorian;

$time = Gregorian::fromTimestamp('139127190012012330');

$time->getTimestamp();             // numeric string
$time->getInstant()->toISOString(); // 2023-08-30T20:10:01.201233Z
$time->bytes->toHex();
```

Create it from a `Brick\DateTime\Instant` or from a `Brick\DateTime\Clock`:

```php
<?php

use Brick\DateTime\Clock\SystemClock;
use Brick\DateTime\Instant;
use Castor\Uuid\System\Time\Gregorian;

Gregorian::fromInstant(Instant::of(1693426201, 201233000));
Gregorian::now(new SystemClock());
```

## Unix time

`System\Time\Unix` stores a 6-byte Unix timestamp in milliseconds. It is used by version 7.

```php
<?php

use Brick\DateTime\Instant;
use Castor\Uuid\System\Time\Unix;

$time = Unix::fromInstant(Instant::of(1721172188, 86590000));

$time->getTimestamp();             // 1721172188086
$time->getInstant()->toISOString(); // 2024-07-16T23:23:08.086Z
$time->bytes->toHex();
```

Both time classes implement `System\Time`, whose common API is `getInstant()` and `getTimestamp()`.
