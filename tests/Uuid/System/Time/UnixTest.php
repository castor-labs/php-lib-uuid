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

namespace Castor\Uuid\System\Time;

use Brick\DateTime\Instant;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UnixTest extends TestCase
{
    #[Test]
    public function it_creates_from_instant(): void
    {
        $timestamp = Unix::fromInstant(Instant::of(1721172188, 86590000));

        $instant = $timestamp->getInstant();

        $this->assertSame(1721172188, $instant->getEpochSecond());
        $this->assertSame(86000000, $instant->getNano());
    }
}
