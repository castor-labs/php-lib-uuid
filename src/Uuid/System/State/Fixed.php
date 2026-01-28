<?php

declare(strict_types=1);

namespace Castor\Uuid\System\State;

use Castor\Uuid\ByteArray;
use Castor\Uuid\System\State;
use Castor\Uuid\System\Time\Gregorian;

final readonly class Fixed implements State
{
    public function __construct(
        private Gregorian $time,
        private ByteArray $clockSeq,
        private ByteArray $node,
    ) {}

    public function getClockSequence(): ByteArray
    {
        return $this->clockSeq;
    }

    public function getTime(): Gregorian
    {
        return $this->time;
    }

    public function getNode(): ByteArray
    {
        return $this->node;
    }
}
