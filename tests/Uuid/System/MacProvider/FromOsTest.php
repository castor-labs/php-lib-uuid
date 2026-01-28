<?php

declare(strict_types=1);

namespace Castor\Uuid\System\MacProvider;

use Castor\Uuid\System\MacProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(FromOs::class)]
class FromOsTest extends TestCase
{
    #[Test]
    public function it_can_find_macs(): void
    {
        $next = $this->createMock(MacProvider::class);

        $provider = new FromOs($next);

        $addresses = $provider->getMacAddresses();
        $this->assertGreaterThan(0, $addresses);

        foreach ($addresses as $address) {
            // None of the addresses should have the least significant bit of the first octet set
            $this->assertStringEndsWith('0', \base_convert((string) $address[0], 10, 2));
        }
    }
}
