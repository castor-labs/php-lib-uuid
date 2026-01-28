<?php

declare(strict_types=1);

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
