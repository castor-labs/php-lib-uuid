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
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(V3::class)]
#[CoversClass(Any::class)]
#[CoversFunction('Castor\Uuid\Ns\url')]
class V3Test extends TestCase
{
    #[Test]
    public function it_creates(): void
    {
        $ns = Ns\url();
        $v3 = V3::create($ns, 'test');
        $this->assertSame('1cf93550-8eb4-3c32-a229-826cf8c1be59', $v3->toString());
    }

    #[Test]
    #[DataProvider('getParseErrorData')]
    public function it_parses_with_error(string $in, bool $lazy, string $expectedError): void
    {
        $this->expectException(ParsingError::class);
        $this->expectExceptionMessage($expectedError);
        V3::parse($in, $lazy);
    }

    public static function getParseErrorData(): array
    {
        return [
            'less chars' => ['00010f-0405-4607-8809-0a0b0c0d0e0f', false, 'UUID must have 16 bytes.'],
            'more chars' => ['00010203-0405-4607-8809-0a0b0c0d0e0fdf', false, 'UUID must have 16 bytes.'],
            'wrong version' => ['7628f4de-01bd-494b-a84b-d7f900521218', false, 'Not a valid version 3 UUID.'],
            'wrong version lazy' => ['7628f4de-01bd-494b-a84b-d7f900521218', true, 'Not a valid version 3 UUID.'],
            'invalid hex' => ['ZZlf8060-4a64-4f50-9e10-882cb74461f7', false, 'Invalid hexadecimal in UUID.'],
            'invalid hex lazy' => ['ZZlf8060-4a64-4f50-9e10-882cb74461f7', true, 'Invalid UUID string.'],
        ];
    }

    #[Test]
    public function it_serializes(): void
    {
        $v3 = V3::parse('a0f6aad0-cdf5-3ddc-a2ac-0bddb3249309');

        $serialized = \serialize($v3);
        $json = \json_encode(['uuid' => $v3], JSON_THROW_ON_ERROR);

        $this->assertSame('O:14:"Castor\Uuid\V3":1:{i:0;s:36:"a0f6aad0-cdf5-3ddc-a2ac-0bddb3249309";}', $serialized);
        $this->assertTrue($v3->equals(\unserialize($serialized)));
        $this->assertSame('{"uuid":"a0f6aad0-cdf5-3ddc-a2ac-0bddb3249309"}', $json);
        $this->assertSame('a0f6aad0-cdf5-3ddc-a2ac-0bddb3249309', (string) $v3);
    }
}
