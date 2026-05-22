---
description: RFC-4122 UUID value objects for PHP 8.3.
---
# Castor UUID

Castor UUID provides immutable value objects for RFC-4122 Universally Unique Identifiers. It focuses on explicit UUID versions, byte-level access, stable string formatting, and small system abstractions for time, randomness, node identifiers, and generation state.

The package supports UUID versions 1, 3, 4, 5, 6, and 7:

- **Version 1**: Gregorian timestamp, clock sequence, and node identifier.
- **Version 3**: deterministic MD5 namespace-and-name UUID.
- **Version 4**: random UUID with 122 bits of entropy after version and variant bits.
- **Version 5**: deterministic SHA-1 namespace-and-name UUID.
- **Version 6**: reordered Gregorian timestamp UUID for better sort locality.
- **Version 7**: Unix-millisecond timestamp plus randomness; preferred for new time-ordered identifiers.

Version 2 UUIDs are intentionally not implemented because they are the legacy DCE Security profile, encode local identifiers such as POSIX UID/GID values, and are not generally useful for modern application identifiers.

## Install

Castor packages are distributed through the Castor Composer repository:

```json
{
  "repositories": [
    {
      "type": "composer",
      "url": "https://castor-labs.github.io/php-packages"
    }
  ]
}
```

Then require the package:

```bash
composer require castor/uuid
```

## Quick example

```php
<?php

use Castor\Uuid;

$uuid = Uuid\Version7::generate();

echo $uuid->toString(); // 0190bddb-5bb6-7896-9c8b-18dbd0ab4337
echo $uuid->toUrn();    // urn:uuid:0190bddb-5bb6-7896-9c8b-18dbd0ab4337
echo $uuid->getBytes()->toHex();

$parsed = Uuid\parse($uuid->toString());

if ($parsed instanceof Uuid\TimeBased) {
    echo $parsed->getTime()->getInstant()->toISOString();
}
```
