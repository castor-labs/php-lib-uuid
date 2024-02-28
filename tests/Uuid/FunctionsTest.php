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

namespace Castor\Uuid;

use Castor\Uuid;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Any::class)]
#[CoversFunction('Castor\Uuid\parse')]
#[CoversFunction('Castor\Uuid\max')]
#[CoversFunction('Castor\Uuid\nil')]
#[CoversFunction('Castor\Uuid\Ns\dns')]
#[CoversFunction('Castor\Uuid\Ns\url')]
#[CoversFunction('Castor\Uuid\Ns\oid')]
#[CoversFunction('Castor\Uuid\Ns\x500')]
class FunctionsTest extends TestCase
{
    #[Test]
    public function it_creates_well_known_uuids(): void
    {
        $this->assertSame('6ba7b810-9dad-11d1-80b4-00c04fd430c8', Ns\dns()->toString());
        $this->assertSame('6ba7b811-9dad-11d1-80b4-00c04fd430c8', Ns\url()->toString());
        $this->assertSame('6ba7b812-9dad-11d1-80b4-00c04fd430c8', Ns\oid()->toString());
        $this->assertSame('6ba7b814-9dad-11d1-80b4-00c04fd430c8', Ns\x500()->toString());
        $this->assertSame('ffffffff-ffff-ffff-ffff-ffffffffffff', Uuid\max()->toString());
        $this->assertSame('00000000-0000-0000-0000-000000000000', Uuid\nil()->toString());
    }

    #[Test]
    #[DataProvider('getParseData')]
    public function it_parses(string $uuid, string $type): void
    {
        $parsed = Uuid\parse($uuid);
        $this->assertInstanceOf($type, $parsed);
    }

    public static function getParseData(): array
    {
        return [
            'nil' => ['00000000-0000-0000-0000-000000000000', Any::class],
            'v_3' => ['a0f6aad0-cdf5-3ddc-a2ac-0bddb3249309', V3::class],
            'v_4' => ['fa06067f-602d-404a-a34c-45c6a7744011', V4::class],
            'v_5' => ['5fe80e27-269a-5cce-98c3-989ddd181b71', V5::class],
            'max' => ['ffffffff-ffff-ffff-ffff-ffffffffffff', Any::class],
            'any' => ['99cf973d-3fe7-7ee4-88bd-a0991a048794', Any::class],
        ];
    }
}
