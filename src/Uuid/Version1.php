<?php

declare(strict_types=1);

namespace Castor\Uuid;

use Castor\Bytes;
use Castor\Uuid\System\State;
use Castor\Uuid\System\State\Standard;
use Castor\Uuid\System\Time\Gregorian;

/**
 * Version1 represents a version 1 UUID.
 *
 * Version 1 UUIDS are made of a Gregorian Timestamp, a Clock Sequence and one the MAC addresses of the host.
 *
 * Version 1 UUIDs always have their most significant bits on the 7th octet set to 0001 (x10)
 */
final class Version1 extends Any implements TimeBased
{
    /**
     * Parses a UUID Version 1 from the string representation.
     *
     * @throws ParsingError
     */
    public static function parse(string $uuid, bool $lazy = true): self
    {
        $v1 = parent::parse($uuid, $lazy);
        if (!$v1 instanceof self) {
            throw new ParsingError('Not a valid version 1 UUID.');
        }

        return $v1;
    }

    /**
     * Creates a UUID Version 1 from the raw bytes.
     */
    public static function fromBytes(ByteArray|string $bytes): self
    {
        $uuid = parent::fromBytes($bytes);
        if (!$uuid instanceof self) {
            throw new ParsingError('Not a valid version 1 UUID.');
        }

        return $uuid;
    }

    public static function generate(?State $state = null): self
    {
        $state = $state ?? Standard::global();
        $ts = $state->getTime()->bytes;
        $node = $state->getNode();
        $seq = $state->getClockSequence();

        $bytes = new ByteArray(self::LEN);
        $bytes->allocate($ts[4], $ts[5], $ts[6], $ts[7], $ts[2], $ts[3], $ts[0], $ts[1], ...$seq, ...$node);

        // We set the 7th octet to 0001 XXXX (version 1)
        $bytes[self::VEB] = ($bytes[self::VEB] & 0x0F) | 0x10; // AND 0000 1111 OR 0001 0000

        // Set buts 6-7 to 10
        $bytes[self::VAB] = ($bytes[self::VAB] & 0x3F) | 0x80;

        return new self($bytes);
    }

    /**
     * Returns the Gregorian of this UUID.
     */
    public function getTime(): Gregorian
    {
        $b = $this->getBytes();
        $b[6] &= 0x0F; // Unset the version bits
        $b->setSize(8);
        $b->allocate($b[6], $b[7], $b[4], $b[5], $b[0], $b[1], $b[2], $b[3]);

        return new Gregorian($b);
    }

    /**
     * Returns the node of this UUID.
     */
    public function getNode(): ByteArray
    {
        return $this->getBytes()->slice(10);
    }

    /**
     * Returns the clock sequence of this UUID.
     */
    public function getClockSeq(): ByteArray
    {
        $bytes = $this->getBytes();
        $bytes[8] &= 0x3F; // Unset the variant bits

        return $bytes->slice(8, 2);
    }
}
