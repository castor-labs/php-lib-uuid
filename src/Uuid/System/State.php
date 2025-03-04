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
