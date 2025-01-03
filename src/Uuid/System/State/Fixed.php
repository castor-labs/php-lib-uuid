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
