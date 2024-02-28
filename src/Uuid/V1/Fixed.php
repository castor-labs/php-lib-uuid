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

namespace Castor\Uuid\V1;

use Castor\Bytes;

final readonly class Fixed implements State
{
    public function __construct(
        private GregorianTime $time,
        private Bytes $clockSeq,
        private Bytes $node,
    ) {}

    public function getClockSequence(): Bytes
    {
        return $this->clockSeq;
    }

    public function getTime(): GregorianTime
    {
        return $this->time;
    }

    public function getNode(): Bytes
    {
        return $this->node;
    }
}
