<?php

declare(strict_types=1);

namespace Castor\Uuid\System;

use Castor\Bytes;
use Castor\Uuid\ByteArray;
use Castor\Uuid\System\Time\Gregorian;

/**
 * State represents the system state needed to construct some UUIDs.
 */
interface State
{
    /**
     * Returns the system's clock sequence as bytes.
     */
    public function getClockSequence(): ByteArray;

    /**
     * Returns the system time.
     */
    public function getTime(): Gregorian;

    /**
     * Returns the system's node.
     */
    public function getNode(): ByteArray;
}
