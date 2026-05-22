---
description: Handle ParsingError and understand interoperability boundaries.
---
# Errors and compatibility

## ParsingError

All parsing failures use `Castor\Uuid\ParsingError`, an `InvalidArgumentException` subclass.

```php
<?php

use Castor\Uuid;
use Castor\Uuid\ParsingError;

try {
    $uuid = Uuid\parse('not-a-uuid');
} catch (ParsingError $error) {
    // Handle invalid UUID input.
}
```

Common failures include:

- Invalid canonical string shape in lazy parsing.
- Invalid hexadecimal characters in eager parsing.
- A decoded byte sequence whose length is not exactly 16 bytes.
- A concrete version parser receiving another version.

`Uuid\isValid($value)` is a convenience wrapper that returns `false` instead of throwing.

## Any and unknown versions

`Any::fromBytes()` and `Any::parse()` return concrete types for version nibbles 1, 3, 4, 5, 6, and 7. If the UUID is structurally valid but the version nibble is not recognized by this package, the result is `Any`.

This lets applications preserve, compare, serialize, and emit unknown UUID values without pretending they are one of the supported concrete versions.

## Serialization and JSON

UUID objects are stringable and JSON serializable. PHP serialization stores both byte and string data so UUID values can be round-tripped.

```php
<?php

use Castor\Uuid\Version4;

$uuid = Version4::parse('fa06067f-602d-404a-a34c-45c6a7744011');

json_encode(['uuid' => $uuid]);
// {"uuid":"fa06067f-602d-404a-a34c-45c6a7744011"}
```

## Interoperability notes

- Canonical strings are emitted in lowercase segmented hexadecimal form.
- Uppercase input is accepted because parsing lowercases before validation.
- URNs are emitted with `toUrn()`, but parser input should be the canonical UUID string.
- Bytes are always 16 bytes for UUID values, 6 bytes for nodes and Unix time, 8 bytes for Gregorian time, and 2 bytes for clock sequences.
- Version 1 and 6 expose node identifiers. Use version 7 for new time-ordered identifiers when you do not want MAC-derived node information in the UUID.
- Version 3 uses MD5 for RFC compatibility. Version 5 uses SHA-1. Neither should be treated as a password hashing or security-token primitive.

## Version 2

Castor UUID does not implement version 2. The DCE Security UUID layout is tied to local domain identifiers and has limited modern interoperability. Applications should use version 4 for random identifiers, version 5 for deterministic namespace identifiers, or version 7 for sortable time-based identifiers.
