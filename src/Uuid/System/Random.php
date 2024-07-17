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
