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
