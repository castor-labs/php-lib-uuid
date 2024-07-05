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

namespace Castor\Uuid\Version1;

use Castor\Bytes;

/**
 * State represents the needed state to construct a Version1 UUID.
 */
interface State
{
    /**
     * Returns the Clock Sequence as Bytes.
     */
    public function getClockSequence(): Bytes;

    /**
     * Returns the time for an UUID v1.
     */
    public function getTime(): Time;

    /**
     * Returns the Node.
     */
    public function getNode(): Bytes;
}
