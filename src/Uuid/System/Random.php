<?php

declare(strict_types=1);

namespace Castor\Uuid\System;

use Random\Engine\Secure;
use Random\Randomizer;

final class Random
{
    private static ?Randomizer $global = null;

    public static function global(): Randomizer
    {
        if (self::$global === null) {
            self::$global = new Randomizer(new Secure());
        }

        return self::$global;
    }
}
