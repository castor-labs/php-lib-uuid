<?php

declare(strict_types=1);

/**
 * @project Castor UUID
 * @link https://github.com/castor-labs/php-lib-uuid
 * @package castor/uuid
 * @author Matias Navarro-Carter mnavarrocarter@gmail.com
 * @license MIT
 * @copyright 2024 CastorLabs Ltd
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Castor\Uuid;

use Brick\DateTime\Instant;
use Castor\Bytes;
use Castor\Uuid\System\Random;
use Castor\Uuid\System\Time\Unix;
use Random\Randomizer;

/**
 * Version7 represents a version 7 UUID.
 *
 * Version 7 UUIDS are made of 48 bits of a Unix Timestamp and 74 bits of randomness. The remaining 6 bits are for the
 * version and the variant.
 *
 * Version 7 UUIDs should be preferred over Version 1 or 6 UUIDs.
 *
 * Version 7 UUIDs always have their most significant bits on the 7th octet set to 0111 (0x70).
 */
final class Version7 extends Any implements TimeBased
{
    /**
     * Parses a UUID Version 7 from the string representation.
     *
     * @throws ParsingError
     */
    public static function parse(string $uuid, bool $lazy = true): self
    {
        $v7 = parent::parse($uuid, $lazy);
        if (!$v7 instanceof self) {
            throw new ParsingError('Not a valid version 7 UUID.');
        }

        return $v7;
    }

    /**
     * Creates a UUID Version 7 from the raw bytes.
     */
    public static function fromBytes(Bytes|string $bytes): self
    {
        $uuid = parent::fromBytes($bytes);
        if (!$uuid instanceof self) {
            throw new ParsingError('Not a valid version 7 UUID.');
        }

        return $uuid;
    }

    /**
     * Generates a UUID Version 7 from the passed Instant and Randomizer.
     */
    public static function generate(?Unix $timestamp = null, ?Randomizer $randomizer = null): self
    {
        $timestamp = $timestamp ?? Unix::fromInstant(Instant::now());
        $randomizer = $randomizer ?? Random::global();

        $bytes = new Bytes(
            $timestamp->bytes->asString().
            $randomizer->getBytes(10),
        );

        // We set the 7th octet to 0111 XXXX (version 7)
        $bytes[self::VEB] = $bytes[self::VEB] & 0x0F | 0x70; // AND 0000 1111 OR 0111 0000

        // Set buts 6-7 to 10
        $bytes[self::VAB] = $bytes[self::VAB] & 0x3F | 0x80;

        return new self($bytes);
    }

    /**
     * Returns the Unix Timestamp of this UUID.
     */
    public function getTime(): Unix
    {
        $bytes = $this->getBytes()->slice(0, 6);

        return new Unix($bytes);
    }
}
