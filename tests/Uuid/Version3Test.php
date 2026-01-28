<?php

declare(strict_types=1);

namespace Castor\Uuid;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class Version3Test extends TestCase
{
    #[Test]
    public function it_creates(): void
    {
        $ns = Ns\url();
        $v3 = Version3::create($ns, 'test');
        $this->assertSame('1cf93550-8eb4-3c32-a229-826cf8c1be59', $v3->toString());
    }

    #[Test]
    #[TestWith(['00010f-0405-4607-8809-0a0b0c0d0e0f', false, 'UUID must have 16 bytes.'])]
    #[TestWith(['00010203-0405-4607-8809-0a0b0c0d0e0fdf', false, 'UUID must have 16 bytes.'])]
    #[TestWith(['7628f4de-01bd-494b-a84b-d7f900521218', false, 'Not a valid version 3 UUID.'])]
    #[TestWith(['7628f4de-01bd-494b-a84b-d7f900521218', true, 'Not a valid version 3 UUID.'])]
    #[TestWith(['ZZlf8060-4a64-4f50-9e10-882cb74461f7', false, 'Invalid hexadecimal in UUID.'])]
    #[TestWith(['ZZlf8060-4a64-4f50-9e10-882cb74461f7', true, 'Invalid UUID string.'])]
    public function it_parses_with_error(string $in, bool $lazy, string $expectedError): void
    {
        $this->expectException(ParsingError::class);
        $this->expectExceptionMessage($expectedError);
        Version3::parse($in, $lazy);
    }

    #[Test]
    public function it_serializes(): void
    {
        $v3 = Version3::parse('a0f6aad0-cdf5-3ddc-a2ac-0bddb3249309', false);

        $serialized = \serialize($v3);
        $json = \json_encode(['uuid' => $v3], JSON_THROW_ON_ERROR);

        $hash = \md5($serialized);

        $this->assertSame(
            'e7c111909fae531b94cd9a64ab8c6355',
            $hash,
            'Does not match hash of serialized data: ' . $serialized,
        );
        $this->assertTrue($v3->equals(\unserialize($serialized)));
        $this->assertSame('{"uuid":"a0f6aad0-cdf5-3ddc-a2ac-0bddb3249309"}', $json);
        $this->assertSame('a0f6aad0-cdf5-3ddc-a2ac-0bddb3249309', (string) $v3);
    }
}
