---
description: Parse UUIDs from strings and bytes, emit strings, hex, JSON, and URNs.
---
# Parsing and formatting

## Parse any UUID

Use `Castor\Uuid\parse()` or `Castor\Uuid\Any::parse()` when you accept any UUID version.

```php
<?php

use Castor\Uuid;

$uuid = Uuid\parse('FA06067F-602D-404A-A34C-45C6A7744011');

$uuid instanceof Uuid\Version4; // true
$uuid->toString();              // fa06067f-602d-404a-a34c-45c6a7744011
```

Parsing lowercases input before validation. With the default lazy mode, the input must be the standard segmented form: `8-4-4-4-12` hexadecimal digits.

## Parse a specific version

Use a version class when the caller must provide a specific UUID version:

```php
<?php

use Castor\Uuid\ParsingError;
use Castor\Uuid\Version7;

try {
    $uuid = Version7::parse('0190bddb-5bb6-7896-9c8b-18dbd0ab4337');
} catch (ParsingError $error) {
    // Invalid UUID syntax or a UUID whose version nibble is not 7.
}
```

The concrete parsers call the generic parser and then verify the resulting type.

## Lazy and eager parsing

`parse($uuid, true)` validates the canonical string shape and keeps bytes uncomputed until `getBytes()` is called. This is the default and is efficient when you mostly compare or serialize string values.

`parse($uuid, false)` removes hyphens, decodes hexadecimal immediately, checks the byte length, and builds the UUID from bytes. This mode also accepts a 32-character bare hexadecimal string because hyphens are removed before decoding.

```php
<?php

use Castor\Uuid;

$lazy = Uuid\parse('fa06067f-602d-404a-a34c-45c6a7744011');
$eager = Uuid\parse('fa06067f602d404aa34c45c6a7744011', false);
```

## Create from bytes

Use `Castor\Uuid\fromBytes()` or `Any::fromBytes()` with either a raw 16-byte string or a `ByteArray`.

```php
<?php

use Castor\Uuid;
use Castor\Uuid\ByteArray;

$bytes = ByteArray::fromHex('fa06067f602d404aa34c45c6a7744011');
$uuid = Uuid\fromBytes($bytes);

$uuid instanceof Uuid\Version4; // true
```

The version nibble in byte 6 chooses the concrete type for versions 1, 3, 4, 5, 6, and 7. Other version nibbles return `Any`.

## String, hex, JSON, and URN output

```php
<?php

use Castor\Uuid\Version4;

$uuid = Version4::parse('fa06067f-602d-404a-a34c-45c6a7744011');

$uuid->toString();          // fa06067f-602d-404a-a34c-45c6a7744011
(string) $uuid;             // same as toString()
$uuid->toUrn();             // urn:uuid:fa06067f-602d-404a-a34c-45c6a7744011
$uuid->getBytes()->toHex(); // fa06067f602d404aa34c45c6a7744011
json_encode($uuid);         // "fa06067f-602d-404a-a34c-45c6a7744011"
```

`toUrn()` emits URN form. The parser accepts canonical UUID strings, not the `urn:uuid:` prefix.

## Equality

`equals()` compares canonical string representations, so two UUID objects are equal when they represent the same UUID value even if one was parsed lazily and the other was created from bytes.

```php
<?php

use Castor\Uuid;
use Castor\Uuid\ByteArray;

$a = Uuid\parse('fa06067f-602d-404a-a34c-45c6a7744011');
$b = Uuid\fromBytes(ByteArray::fromHex('fa06067f602d404aa34c45c6a7744011'));

$a->equals($b); // true
```
