<?php

declare(strict_types=1);

namespace Castor\Uuid;

use Castor\Uuid\System\Random;
use Random\Randomizer;

/**
 * Version4 represents a version 4 UUID.
 *
 * Version 4 UUIDs are made of 128 random bits. However, 6 bits are used to indicate the version and the variant.
 * Thus, the actual entropy is 122 bits. The possibilities of collisions are one in 5.3 undecillions.
 *
 * Version 4 UUIDs always have their most significant bits on the 7th octet set to 0100 (x40)
 */
final class Version4 extends Any
{
    /**
     * @throws ParsingError
     */
    public static function parse(string $uuid, bool $lazy = true): self
    {
        $v4 = parent::parse($uuid, $lazy);
        if (!$v4 instanceof self) {
            throw new ParsingError('Not a valid version 4 UUID.');
        }

        return $v4;
    }

    public static function fromBytes(ByteArray|string $bytes): self
    {
        $uuid = parent::fromBytes($bytes);
        if (!$uuid instanceof self) {
            throw new ParsingError('Not a valid version 4 UUID.');
        }

        return $uuid;
    }

    public static function generate(?Randomizer $randomizer = null): self
    {
        $randomizer = $randomizer ?? Random::global();

        $bytes = ByteArray::fromRaw($randomizer->getBytes(self::LEN));

        // We set the 7th octet to 0100 XXXX (version 4)
        $bytes[self::VEB] = ($bytes[self::VEB] & 0x0F) | 0x40; // AND 0000 1111 OR 0100 0000
        // Set the variant to 6-7 to 10
        $bytes[self::VAB] = ($bytes[self::VAB] & 0x3F) | 0x80;

        return new self($bytes);
    }
}
