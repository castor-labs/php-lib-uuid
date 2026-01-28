<?php

declare(strict_types=1);

namespace Castor\Uuid;

use Castor\Uuid\System\Time;

interface TimeBased
{
    /**
     * Returns the time encoded inside this UUID.
     */
    public function getTime(): Time;
}
