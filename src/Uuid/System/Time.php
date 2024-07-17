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

use Brick\DateTime\Instant;
use Brick\Math\BigInteger;

interface Time
{
    /**
     * Returns the instant from this time.
     */
    public function getInstant(): Instant;

    /**
     * Returns the timestamp as an integer from this time.
     */
    public function getTimestamp(): BigInteger;
}
