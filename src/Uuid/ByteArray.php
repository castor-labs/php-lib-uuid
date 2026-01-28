<?php

declare(strict_types=1);

namespace Castor\Uuid;

use Castor\Arr;
use Castor\Bytes;
use Castor\Encoding\Failure;
use Castor\Encoding\Hex;

/**
 * @template-extends \SplFixedArray<int<0,255>>
 */
final class ByteArray extends \SplFixedArray
{
    /**
     * @throws Failure
     */
    public static function fromHex(string $hex): self
    {
        return self::fromRaw(Hex\decode($hex));
    }

    public static function fromRaw(string $raw): self
    {
        $bytes = Bytes\unpack($raw);

        return self::create($bytes);
    }

    /**
     * @param array<int, int<0,255>> $array
     */
    public static function create(array $array): self
    {
        $self = new self(\count($array));
        $self->allocate(...$array);

        return $self;
    }

    /**
     * @param int<0,255> ...$bytes
     */
    public function allocate(int ...$bytes): void
    {
        // @var array<int,int<0,255> $bytes
        foreach ($bytes as $i => $byte) {
            $this[$i] = $byte;
        }
    }

    public function toRaw(): string
    {
        return Bytes\pack(...$this->toArray());
    }

    public function slice(int $offset, ?int $length = null): ByteArray
    {
        return self::create(Arr\slice($this->toArray(), $offset, $length));
    }

    public function toHex(): string
    {
        return Hex\encode($this->toRaw());
    }

    public function equals(ByteArray $bytes): bool
    {
        return $this == $bytes;
    }
}
