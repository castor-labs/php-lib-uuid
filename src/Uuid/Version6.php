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

use Castor\Bytes;
use Castor\Uuid\System\State;
use Castor\Uuid\System\State\Standard;
use Castor\Uuid\System\Time\Gregorian;

/**
 * Version6 represents a version 6 UUID.
 *
 * Version 6 UUIDS are made of a Gregorian Timestamp, a Clock Sequence and one the MAC addresses of the host.
 *
 * Version 6 UUIDs always have their most significant bits on the 7th octet set to 0110 (x60)
 */
final class Version6 extends Any implements TimeBased
{
    /**
     * Parses a UUID Version 6 from the string representation.
     *
     * @throws ParsingError
     */
    public static function parse(string $uuid, bool $lazy = true): self
    {
        $v6 = parent::parse($uuid, $lazy);
        if (!$v6 instanceof self) {
            throw new ParsingError('Not a valid version 6 UUID.');
        }

        return $v6;
    }

    /**
     * Creates a UUID Version 6 from the raw bytes.
     */
    public static function fromBytes(Bytes|string $bytes): self
    {
        $uuid = parent::fromBytes($bytes);
        if (!$uuid instanceof self) {
            throw new ParsingError('Not a valid version 6 UUID.');
        }

        return $uuid;
    }

    /**
     * Generates a UUID Version 6 from the passed system state.
     *
     * If the state is null, then the global state instance is used
     */
    public static function generate(?State $state = null): self
    {
        $state = $state ?? Standard::global();
        $time = $state->getTime()->bytes;
        $node = $state->getNode()->asString();
        $seq = $state->getClockSequence()->asString();

        $bytes = new Bytes($time->asString().$seq.$node);

        // We need to shift 4 bits to constraint this to a 60 bit number, discarding the first 4 bits
        $bytes[0] = (($bytes[0] & 0x0F) << 4) | ($bytes[1] >> 4);
        $bytes[1] = (($bytes[1] & 0x0F) << 4) | ($bytes[2] >> 4);
        $bytes[2] = (($bytes[2] & 0x0F) << 4) | ($bytes[3] >> 4);
        $bytes[3] = (($bytes[3] & 0x0F) << 4) | ($bytes[4] >> 4);
        $bytes[4] = (($bytes[4] & 0x0F) << 4) | ($bytes[5] >> 4);
        $bytes[5] = (($bytes[5] & 0x0F) << 4) | ($bytes[6] >> 4);

        // We set the 7th octet to 0110 XXXX (version 6)
        $bytes[self::VEB] = $bytes[self::VEB] & 0x0F | 0x60; // AND 0000 1111 OR 0110 0000

        // Set buts 6-7 to 10
        $bytes[self::VAB] = $bytes[self::VAB] & 0x3F | 0x80;

        return new self($bytes);
    }

    /**
     * Returns the Gregorian of this UUID.
     */
    public function getTime(): Gregorian
    {
        $bytes = $this->getBytes()->slice(0, 8);

        // Unset the version bits and place the least 4 significant bits of the previous byte there
        $bytes[6] = ($bytes[6] & 0x0F) | (($bytes[5] & 0x0F) << 4);

        // Shift 4 bits to the right to recover the original timestamp
        $bytes[5] = ($bytes[5] >> 4) | (($bytes[4] & 0x0F) << 4);
        $bytes[4] = ($bytes[4] >> 4) | (($bytes[3] & 0x0F) << 4);
        $bytes[3] = ($bytes[3] >> 4) | (($bytes[2] & 0x0F) << 4);
        $bytes[2] = ($bytes[2] >> 4) | (($bytes[1] & 0x0F) << 4);
        $bytes[1] = ($bytes[1] >> 4) | (($bytes[0] & 0x0F) << 4);
        $bytes[0] >>= 4;

        return new Gregorian($bytes);
    }

    /**
     * Returns the node of this UUID.
     */
    public function getNode(): Bytes
    {
        return $this->getBytes()->slice(10);
    }

    /**
     * Returns the clock sequence of this UUID.
     */
    public function getClockSeq(): Bytes
    {
        $bytes = $this->getBytes();
        $bytes[8] = $bytes[8] & 0x3F; // Unset the variant bits

        return $bytes->slice(8, 2);
    }
}
