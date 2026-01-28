<?php

declare(strict_types=1);

namespace Castor;

use Castor\Uuid\ByteArray;

/**
 * This is the base contract for a UUID.
 *
 * This contract is purposefully simple and it only limits itself to the operations that are common to all UUIDs
 *
 * If you need to act upon a particular implementation (for instance, extract the time of a Version1 UUID), you MUST
 * type hint to that particular version or use the "instanceof" operator on types implementing this interface.
 */
interface Uuid
{
    /**
     * Returns the underlying bytes that make up this UUID.
     *
     * Implementors MUST NOT return the original reference stored inside the UUID.
     */
    public function getBytes(): ByteArray;

    /**
     * Returns the standard segmented hexadecimal representation of the UUID.
     *
     * The format is 00000000-0000-0000-0000-000000000000
     */
    public function toString(): string;

    /**
     * Checks whether two UUIDs are equal.
     */
    public function equals(Uuid $uuid): bool;

    /**
     * Returns the URN representation of the UUID.
     */
    public function toUrn(): string;
}
