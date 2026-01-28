<?php

declare(strict_types=1);

namespace Castor\Uuid\System;

use Castor\Uuid\ByteArray;

interface MacProvider
{
    /**
     * Returns an array of mac addresses parsed from bytes.
     *
     * Implementors MUST NOT return an empty array.
     *
     * In case is possible for the implementation not to be able to find any MAC, you must compose a
     * MacProvider\RandUniMultiCast as a fallback.
     *
     * @return ByteArray[]
     */
    public function getMacAddresses(): array;
}
