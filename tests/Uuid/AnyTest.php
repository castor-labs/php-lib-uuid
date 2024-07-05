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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Any::class)]
class AnyTest extends TestCase
{
    #[Test]
    #[DataProvider('getParseData')]
    public function it_parses(string $in, string $type): void
    {
        $uuid = Any::parse($in);
        $this->assertInstanceOf($type, $uuid);
    }

    /**
     * @return array[]
     */
    public static function getParseData(): array
    {
        return [
            'nil' => ['00000000-0000-0000-0000-000000000000', Any::class],
            'v_3' => ['a0f6aad0-cdf5-3ddc-a2ac-0bddb3249309', Version3::class],
            'v_4' => ['fa06067f-602d-404a-a34c-45c6a7744011', Version4::class],
            'v_5' => ['5fe80e27-269a-5cce-98c3-989ddd181b71', Version5::class],
            'max' => ['ffffffff-ffff-ffff-ffff-ffffffffffff', Any::class],
            'any' => ['99cf973d-3fe7-7ee4-88bd-a0991a048794', Any::class],
        ];
    }

    #[Test]
    public function it_serializes(): void
    {
        $unknown = Any::parse('99cf973d-3fe7-7ee4-88bd-a0991a048794');

        $serialized = \serialize($unknown);
        $json = \json_encode(['uuid' => $unknown], JSON_THROW_ON_ERROR);

        $this->assertSame('O:15:"Castor\Uuid\Any":1:{i:0;s:36:"99cf973d-3fe7-7ee4-88bd-a0991a048794";}', $serialized);
        $this->assertTrue($unknown->equals(\unserialize($serialized)));
        $this->assertSame('{"uuid":"99cf973d-3fe7-7ee4-88bd-a0991a048794"}', $json);
        $this->assertSame('99cf973d-3fe7-7ee4-88bd-a0991a048794', (string) $unknown);
    }
}
