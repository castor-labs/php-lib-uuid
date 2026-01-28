<?php

declare(strict_types=1);

namespace Castor\Uuid;

use Castor\Bytes;
use Castor\Uuid;

/**
 * Parses a UUI from a string.
 *
 * @throws ParsingError if the UUID is not valid
 */
function parse(string $uuid, bool $lazy = true): Uuid
{
    return Any::parse($uuid, $lazy);
}

/**
 * Creates a UUID from the raw bytes.
 */
function fromBytes(ByteArray|string $bytes): Uuid
{
    return Any::fromBytes($bytes);
}

/**
 * Checks whether a UUID is valid.
 */
function isValid(string $uuid): bool
{
    try {
        parse($uuid);

        return true;
    } catch (ParsingError) {
        return false;
    }
}

/**
 * Returns the maximum UUID possible (all F).
 */
function max(): Uuid
{
    /** @var null|Uuid $uuid */
    static $uuid = null;
    if (null === $uuid) {
        $uuid = Any::fromBytes(ByteArray::create([
            0xFF,
            0xFF,
            0xFF,
            0xFF,
            0xFF,
            0xFF,
            0xFF,
            0xFF,
            0xFF,
            0xFF,
            0xFF,
            0xFF,
            0xFF,
            0xFF,
            0xFF,
            0xFF,
        ]));
    }

    return $uuid;
}

/**
 * Returns the nil UUID (all zeroes).
 */
function nil(): Uuid
{
    /** @var null|Uuid $uuid */
    static $uuid = null;
    if (null === $uuid) {
        $uuid = Any::fromBytes(ByteArray::create([
            0x00,
            0x00,
            0x00,
            0x00,
            0x00,
            0x00,
            0x00,
            0x00,
            0x00,
            0x00,
            0x00,
            0x00,
            0x00,
            0x00,
            0x00,
            0x00,
        ]));
    }

    return $uuid;
}

namespace Castor\Uuid\Ns;

use Castor\Uuid;
use Castor\Uuid\Any;
use Castor\Uuid\ByteArray;

/**
 * Returns the UUID namespace for Domain Name System (DNS).
 */
function dns(): Uuid
{
    /** @var null|Uuid $uuid */
    static $uuid = null;
    if (null === $uuid) {
        $uuid = Any::fromBytes(ByteArray::create([
            0x6B,
            0xA7,
            0xB8,
            0x10,
            0x9D,
            0xAD,
            0x11,
            0xD1,
            0x80,
            0xB4,
            0x00,
            0xC0,
            0x4F,
            0xD4,
            0x30,
            0xC8,
        ]));
    }

    return $uuid;
}

/**
 * Return the UUID namespace for ISO Object Identifiers (OIDs).
 */
function oid(): Uuid
{
    /** @var null|Uuid $uuid */
    static $uuid = null;
    if (null === $uuid) {
        $uuid = Any::fromBytes(ByteArray::create([
            0x6B,
            0xA7,
            0xB8,
            0x12,
            0x9D,
            0xAD,
            0x11,
            0xD1,
            0x80,
            0xB4,
            0x00,
            0xC0,
            0x4F,
            0xD4,
            0x30,
            0xC8,
        ]));
    }

    return $uuid;
}

/**
 * Returns the UUID namespace for Uniform Resource Locators (URLs).
 */
function url(): Uuid
{
    /** @var null|Uuid $uuid */
    static $uuid = null;
    if (null === $uuid) {
        $uuid = Any::fromBytes(ByteArray::create([
            0x6B,
            0xA7,
            0xB8,
            0x11,
            0x9D,
            0xAD,
            0x11,
            0xD1,
            0x80,
            0xB4,
            0x00,
            0xC0,
            0x4F,
            0xD4,
            0x30,
            0xC8,
        ]));
    }

    return $uuid;
}

/**
 * UUID namespace for X.500 Distinguished Names (DNs).
 */
function x500(): Uuid
{
    /** @var null|Uuid $uuid */
    static $uuid = null;
    if (null === $uuid) {
        $uuid = Any::fromBytes(ByteArray::create([
            0x6B,
            0xA7,
            0xB8,
            0x14,
            0x9D,
            0xAD,
            0x11,
            0xD1,
            0x80,
            0xB4,
            0x00,
            0xC0,
            0x4F,
            0xD4,
            0x30,
            0xC8,
        ]));
    }

    return $uuid;
}
