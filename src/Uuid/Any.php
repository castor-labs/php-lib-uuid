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
use Castor\Encoding\Error;
use Castor\Str;
use Castor\Uuid;

/**
 * Represents a UUID of any format.
 *
 * This class contains common operations available to all UUIDs. It's also capable of parsing any UUID.
 * You should use this class when you are not interested in the concrete UUID version you are working with.
 *
 * However, if you require a particular UUID version, it's better to use the parse methods of the particular
 * version class, as they will ensure you have a correct version.
 *
 * The stability of this API is not guaranteed for extension. You are discouraged to extend this class.
 */
class Any implements Uuid, \Stringable, \JsonSerializable
{
    protected const URN_NS = 'urn:uuid:';

    /** @var int Length of bytes of an UUID */
    protected const LEN = 16;

    /** @var int The version byte */
    protected const VEB = 6;

    /** @var int The variant byte */
    protected const VAB = 8;

    protected function __construct(
        private readonly Bytes $bytes,
    ) {}

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return array{0: string}
     */
    public function __serialize(): array
    {
        return [$this->toString()];
    }

    /**
     * @param array{0: string} $data
     */
    public function __unserialize(array $data): void
    {
        $this->bytes = static::parse($data[0])->getBytes();
    }

    public function toString(): string
    {
        return \sprintf('%s%s-%s-%s-%s-%s%s%s', ...\str_split($this->bytes->toHex(), 4));
    }

    public function getBytes(): Bytes
    {
        return clone $this->bytes;
    }

    public function equals(Uuid $uuid): bool
    {
        return $this->bytes->equals($uuid->getBytes());
    }

    /**
     * @return mixed
     */
    public function jsonSerialize(): string
    {
        return $this->toString();
    }

    public function toUrn(): string
    {
        return self::URN_NS.$this->toString();
    }

    /**
     * Creates a UUID from raw bytes.
     *
     * The return type will always implement Uuid, but it could be any of the implementations available in this library.
     *
     * If you want to conditionally act upon the version parsed, you can use the "instanceof" keyword to figure out the
     * version you are working with.
     *
     * @throws ParsingError if the bytes are invalid
     */
    public static function fromBytes(Bytes|string $bytes): Uuid
    {
        if (\is_string($bytes)) {
            $bytes = new Bytes($bytes);
        }

        if (self::LEN !== $bytes->len()) {
            throw new ParsingError('UUID must have 16 bytes.');
        }

        $v = $bytes[self::VEB] & 0xF0; // 1111 0000

        return match ($v) {
            0x10 => new V1($bytes), // 0001 0000
            0x30 => new V3($bytes), // 0011 0000
            0x40 => new V4($bytes), // 0100 0000
            0x50 => new V5($bytes), // 0101 0000
            default => new Any($bytes)
        };
    }

    /**
     * Parses a UUID.
     *
     * The return type will always implement Uuid, but it could be any of the implementations available in this library.
     *
     * If you want to conditionally act upon the version parsed, you can use the "instanceof" keyword to figure out the
     * version you are working with.
     */
    public static function parse(string $uuid): Uuid
    {
        $uuid = Str\toLower(Str\replace($uuid, '-', ''));

        try {
            $bytes = Bytes::fromHex($uuid);
        } catch (Error $e) {
            throw new ParsingError('Invalid hexadecimal in UUID.', previous: $e);
        }

        return self::fromBytes($bytes);
    }
}
