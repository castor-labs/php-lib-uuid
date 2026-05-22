---
description: Start using Castor UUID value objects.
---
# Getting started

Castor UUID exposes a small common contract plus concrete classes for known UUID versions. The common interface is `Castor\Uuid`; the version classes live under the `Castor\Uuid` namespace.

```php
<?php

use Castor\Uuid;

$id = Uuid\Version4::generate();

$id->toString(); // standard hexadecimal form
$id->toUrn();    // urn:uuid:...
$id->getBytes(); // Castor\Uuid\ByteArray
```

The package also autoloads helper functions in the `Castor\Uuid` namespace:

```php
<?php

use Castor\Uuid;

$parsed = Uuid\parse('fa06067f-602d-404a-a34c-45c6a7744011');
$isValid = Uuid\isValid('fa06067f-602d-404a-a34c-45c6a7744011');
$nil = Uuid\nil();
$max = Uuid\max();
```

## The common contract

Every UUID implementation implements `Castor\Uuid`:

- `getBytes(): ByteArray` returns a clone of the underlying 16 bytes.
- `toString(): string` returns `00000000-0000-0000-0000-000000000000` format.
- `equals(Uuid $uuid): bool` compares canonical string values.
- `toUrn(): string` prefixes the canonical string with `urn:uuid:`.

`Castor\Uuid\Any` implements the common operations for every UUID and is also the generic parser. Known versions return their concrete classes; unknown but structurally valid version nibbles return `Any`.

## Known namespaces

For deterministic versions 3 and 5, use the well-known namespace helpers:

```php
<?php

use Castor\Uuid\Ns;

Ns\dns();  // 6ba7b810-9dad-11d1-80b4-00c04fd430c8
Ns\url();  // 6ba7b811-9dad-11d1-80b4-00c04fd430c8
Ns\oid();  // 6ba7b812-9dad-11d1-80b4-00c04fd430c8
Ns\x500(); // 6ba7b814-9dad-11d1-80b4-00c04fd430c8
```
