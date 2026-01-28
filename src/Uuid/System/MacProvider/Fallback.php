<?php

declare(strict_types=1);

namespace Castor\Uuid\System\MacProvider;

use Castor\Bytes;
use Castor\Uuid\ByteArray;
use Castor\Uuid\System\MacProvider;
use Random\Randomizer;

/**
 * This is a fallback MacProvider implemented following RFC 4122, Section 4.5.
 *
 * It provides 6 random bytes, with the least significant bit of the 1st octet set to 1.
 *
 * @see https://datatracker.ietf.org/doc/html/rfc4122#section-4.5
 */
final readonly class Fallback implements MacProvider
{
    public function __construct(
        private Randomizer $random,
    ) {}

    /**
     * @return ByteArray[]
     */
    public function getMacAddresses(): array
    {
        $b = ByteArray::fromRaw($this->random->getBytes(6));
        $b[0] = ($b[0] & 0xFE) | 0x01;

        return [$b];
    }
}
