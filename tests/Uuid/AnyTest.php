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

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class AnyTest extends TestCase
{
    #[Test]
    #[TestWith(['00000000-0000-0000-0000-000000000000', Any::class])]
    #[TestWith(['3343a72a-4771-11ee-8001-00b0d063c226', Version1::class])]
    #[TestWith(['a0f6aad0-cdf5-3ddc-a2ac-0bddb3249309', Version3::class])]
    #[TestWith(['fa06067f-602d-404a-a34c-45c6a7744011', Version4::class])]
    #[TestWith(['1ee47713-343a-672a-8001-00b0d063c226', Version6::class])]
    #[TestWith(['99cf973d-3fe7-7ee4-88bd-a0991a048794', Version7::class])]
    #[TestWith(['ffffffff-ffff-ffff-ffff-ffffffffffff', Any::class])]
    public function it_parses(string $in, string $type): void
    {
        $uuid = Any::parse($in);
        $this->assertInstanceOf($type, $uuid);
    }

    #[Test]
    public function it_serializes(): void
    {
        $unknown = Any::parse('99cf973d-3fe7-8ee4-88bd-a0991a048794', false);

        $serialized = \serialize($unknown);
        $json = \json_encode(['uuid' => $unknown], JSON_THROW_ON_ERROR);

        $hash = \md5($serialized);

        $this->assertSame(
            'e0d569ae9961295361a930432d035100',
            $hash,
            'Does not match hash of serialized data: '.$serialized
        );
        $this->assertTrue($unknown->equals(\unserialize($serialized)));
        $this->assertSame('{"uuid":"99cf973d-3fe7-8ee4-88bd-a0991a048794"}', $json);
        $this->assertSame('99cf973d-3fe7-8ee4-88bd-a0991a048794', (string) $unknown);
    }
}
