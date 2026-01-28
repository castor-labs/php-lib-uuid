<?php

declare(strict_types=1);

namespace Castor\Uuid\System;

use Brick\DateTime\Instant;

interface Time
{
    /**
     * Returns the instant from this time.
     */
    public function getInstant(): Instant;

    /**
     * Returns the timestamp as a numeric integer from this time.
     *
     * @return numeric-string
     */
    public function getTimestamp(): string;
}
