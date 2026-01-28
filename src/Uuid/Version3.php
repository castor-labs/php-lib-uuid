<?php

declare(strict_types=1);

namespace Castor\Uuid;

use Castor\Bytes;
use Castor\Uuid;

/**
 * Version3 represents a version 3 UUID.
 *
 * Version 3 UUIDS are the md5 hash of another UUID (namespace) plus any string
 *
 * Version 3 UUIDs always have their most significant bits on the 7th octet set to 0011 (x30)
 */
final class Version3 extends Any
{
    private const string HASHING_ALGO = 'md5';

    /**
     * Parses a UUID Version 3 from the string representation.
     *
     * @throws ParsingError
     */
    public static function parse(string $uuid, bool $lazy = true): self
    {
        $v3 = parent::parse($uuid, $lazy);
        if (!$v3 instanceof self) {
            throw new ParsingError('Not a valid version 3 UUID.');
        }

        return $v3;
    }

    /**
     * Creates a UUID Version 3 from the raw bytes.
     */
    public static function fromBytes(ByteArray|string $bytes): self
    {
        $uuid = parent::fromBytes($bytes);
        if (!$uuid instanceof self) {
            throw new ParsingError('Not a valid version 3 UUID.');
        }

        return $uuid;
    }

    public static function create(Uuid $namespace, string $name): self
    {
        $bytes = ByteArray::fromRaw(@\hash(self::HASHING_ALGO, $namespace->getBytes()->toRaw() . $name, true));

        // We set the 7th octet to 0011 XXXX (version 3)
        $bytes[self::VEB] = ($bytes[self::VEB] & 0x0F) | 0x30; // AND 0000 1111 OR 0011 0000

        // Set buts 6-7 to 10
        $bytes[self::VAB] = ($bytes[self::VAB] & 0x3F) | 0x80;

        return new self($bytes);
    }
}
