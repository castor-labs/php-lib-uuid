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

use Brick\DateTime\Clock\FixedClock;
use Brick\DateTime\Instant;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GregorianTest extends TestCase
{
    #[Test]
    public function it_creates_gregorian_time(): void
    {
        $time = Gregorian::now(new FixedClock(Instant::of(1693426201, 201233)));
        $this->assertSame('139127190010002012', (string) $time->getTimestamp());
    }

    #[Test]
    public function it_creates_from_timestamp(): void
    {
        $instant = Gregorian::fromTimestamp('139127190010002012')->getInstant();
        $this->assertSame(1693426201, $instant->getEpochSecond());
        $this->assertSame(201200, $instant->getNano()); // Nano precision is lost because of 100 nano intervals
    }
}
