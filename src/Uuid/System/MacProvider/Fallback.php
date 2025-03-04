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

namespace Castor\Uuid\System\MacProvider;

use Castor\Bytes;
use Castor\Uuid\ByteArray;
use Castor\Uuid\System\MacProvider;
use Random\Randomizer;

/**
 * This is a fallback MacProvider implemented following RFC 4122, Section 4.5.
 *
 * It provides 6 random bytes, with the least significant bit of the 1st octet set to 1.
 *
 * @see https://datatracker.ietf.org/doc/html/rfc4122#section-4.5
 */
final readonly class Fallback implements MacProvider
{
    public function __construct(
        private Randomizer $random
    ) {}

    /**
     * @return ByteArray[]
     */
    public function getMacAddresses(): array
    {
        $b = ByteArray::fromRaw($this->random->getBytes(6));
        $b[0] = $b[0] & 0xFE | 0x01;

        return [$b];
    }
}
