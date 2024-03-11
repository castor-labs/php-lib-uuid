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
use PHPUnit\Framework\TestCase;

#[CoversClass(V5::class)]
#[CoversClass(Any::class)]
#[CoversFunction('Castor\Uuid\Ns\url')]
class V5Test extends TestCase
{
    public function testCreate(): void
    {
        $ns = Ns\url();
        $v5 = V5::create($ns, 'test');
        $this->assertSame('da5b8893-d6ca-5c1c-9a9c-91f40a2a3649', $v5->toString());
    }

    #[DataProvider('getParseErrorData')]
    public function testParseError(string $in, bool $lazy, string $expectedError): void
    {
        $this->expectException(ParsingError::class);
        $this->expectExceptionMessage($expectedError);
        V5::parse($in, $lazy);
    }

    public static function getParseErrorData(): array
    {
        return [
            'less chars' => ['00010f-0405-4607-8809-0a0b0c0d0e0f', false, 'UUID must have 16 bytes.'],
            'more chars' => ['0001003f-0405-4607-8809-0a0b0c0d0e0fdf', false, 'UUID must have 16 bytes.'],
            'wrong version' => ['7628f4de-01bd-494b-a84b-d7f900521218', false, 'Not a valid version 5 UUID.'],
            'wrong version lazy' => ['7628f4de-01bd-494b-a84b-d7f900521218', true, 'Not a valid version 5 UUID.'],
            'invalid hex' => ['ZZlf8060-4a64-4f50-9e10-882cb74461f7', false, 'Invalid hexadecimal in UUID.'],
            'invalid hex lazy' => ['ZZlf8060-4a64-4f50-9e10-882cb74461f7', true, 'Invalid UUID string.'],
        ];
    }

    /**
     * @throws \JsonException
     */
    public function testSerialization(): void
    {
        $v5 = V5::parse('5fe80e27-269a-5cce-98c3-989ddd181b71');

        $serialized = \serialize($v5);
        $json = \json_encode(['uuid' => $v5], JSON_THROW_ON_ERROR);

        $this->assertSame('O:14:"Castor\Uuid\V5":1:{i:0;s:36:"5fe80e27-269a-5cce-98c3-989ddd181b71";}', $serialized);
        $this->assertTrue($v5->equals(\unserialize($serialized)));
        $this->assertSame('{"uuid":"5fe80e27-269a-5cce-98c3-989ddd181b71"}', $json);
        $this->assertSame('5fe80e27-269a-5cce-98c3-989ddd181b71', (string) $v5);
    }
}
