Castor UUID
===========

An RFC-4122 compliant Universally Unique Identifiers value object implementation

## Install

```bash
composer require castor/uuid
```

## Quick Start

```php
<?php

use Castor\Uuid;

$uuid = Uuid\Version4::generate();
echo $uuid->toString(); // Prints: d2e365c8-b525-428d-979f-64b70e76a217
echo $uuid->toUrn(); // Prints: urn:uuid:d2e365c8-b525-428d-979f-64b70e76a217
echo $uuid->getBytes()->toHex(); // Prints: d2e365c8b525428d979f64b70e76a217

$parsed = Uuid\parse($uuid->toString());
echo $parsed instanceof Uuid\Version1; // Prints: false
echo $parsed instanceof Uuid\Version4; // Prints: true
echo $parsed instanceof Uuid\Version3; // Prints: false
echo $parsed instanceof Uuid\Version5; // Prints: false
echo $parsed instanceof Uuid\Version6; // Prints: false
echo $parsed->equals($uuid); // Prints: true
```

The same API is available for `Uuid\Version3` and `Uuid\Version5`.

`Uuid\Version1` and `Uuid\Version6` provides an extended API that can return the different components that form it.

`Uuid\Version2` will not be implemented.