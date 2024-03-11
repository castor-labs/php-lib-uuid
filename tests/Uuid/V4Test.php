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

use Castor\Bytes;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(V4::class)]
#[CoversClass(Any::class)]
class V4Test extends TestCase
{
    #[Test]
    public function it_generates(): void
    {
        $rand = Bytes::fromUint8(250, 121, 156, 79, 226, 155, 163, 224, 208, 141, 170, 226, 238, 50, 229, 73);
        $uuid = V4::generate($rand);

        $this->assertSame('fa799c4f-e29b-43e0-908d-aae2ee32e549', $uuid->toString());
    }

    #[Test]
    #[DataProvider('getParseData')]
    public function it_parses(string $v4): void
    {
        $this->expectNotToPerformAssertions();
        V4::parse($v4);
    }

    #[Test]
    #[DataProvider('getParseErrorData')]
    public function it_parses_with_error(string $in, bool $lazy, string $expectedError): void
    {
        $this->expectException(ParsingError::class);
        $this->expectExceptionMessage($expectedError);
        V4::parse($in, $lazy);
    }

    public static function getParseErrorData(): array
    {
        return [
            'less chars' => ['00010f-0405-4607-8809-0a0b0c0d0e0f', false, 'UUID must have 16 bytes.'],
            'more chars' => ['00010203-0405-4607-8809-0a0b0c0d0e0fdf', false, 'UUID must have 16 bytes.'],
            'wrong version' => ['5bbf8060-4a64-1f50-9e10-882cb74461f7', false, 'Not a valid version 4 UUID.'],
            'wrong version lazy' => ['5bbf8060-4a64-1f50-9e10-882cb74461f7', true, 'Not a valid version 4 UUID.'],
            'invalid hex' => ['ZZlf8060-4a64-4f50-9e10-882cb74461f7', false, 'Invalid hexadecimal in UUID.'],
            'invalid hex lazy' => ['ZZlf8060-4a64-4f50-9e10-882cb74461f7', true, 'Invalid UUID string.'],
        ];
    }

    #[Test]
    public function it_generates_unique_v4(): void
    {
        $a = V4::generate()->toString();
        $b = V4::generate()->toString();

        $this->assertNotSame($a, $b);
    }

    #[Test]
    public function it_serializes(): void
    {
        $v4 = V4::parse('fa06067f-602d-404a-a34c-45c6a7744011');

        $serialized = \serialize($v4);
        $json = \json_encode(['uuid' => $v4], JSON_THROW_ON_ERROR);

        $this->assertSame('O:14:"Castor\Uuid\V4":1:{i:0;s:36:"fa06067f-602d-404a-a34c-45c6a7744011";}', $serialized);
        $this->assertTrue($v4->equals(\unserialize($serialized)));
        $this->assertSame('{"uuid":"fa06067f-602d-404a-a34c-45c6a7744011"}', $json);
        $this->assertSame('fa06067f-602d-404a-a34c-45c6a7744011', (string) $v4);
    }

    public static function getParseData(): array
    {
        return [
            ['fa06067f-602d-404a-a34c-45c6a7744011'],
            ['a959e53b-3b3f-4995-a95b-e117ff790662'],
            ['0f342954-20e6-4431-9445-630ad3e8c48a'],
        ];
    }
}
