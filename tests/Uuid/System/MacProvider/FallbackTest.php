<?php

declare(strict_types=1);

namespace Castor\Uuid\System\MacProvider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Random\Randomizer;

#[CoversClass(FromOs::class)]
#[CoversClass(Fallback::class)]
class FallbackTest extends TestCase
{
    #[Test]
    public function it_generates_random(): void
    {
        $macProvider = new Fallback(new Randomizer(new Mt19937(100)));
        $addresses = $macProvider->getMacAddresses();
        $this->assertCount(1, $addresses);
        $address = $addresses[0];

        // The least significant bit of the first octet must always be set
        $this->assertStringEndsWith('1', \base_convert((string) $address[0], 10, 2));
        $this->assertSame('09961c8b18db', $address->toHex());
    }
}
