<?php

declare(strict_types=1);

namespace Castor\Uuid;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class Version5Test extends TestCase
{
    #[Test]
    public function it_creates(): void
    {
        $ns = Ns\url();
        $v5 = Version5::create($ns, 'test');
        $this->assertSame('da5b8893-d6ca-5c1c-9a9c-91f40a2a3649', $v5->toString());
    }

    #[Test]
    #[TestWith(['00010f-0405-4607-8809-0a0b0c0d0e0f', false, 'UUID must have 16 bytes.'])]
    #[TestWith(['0001003f-0405-4607-8809-0a0b0c0d0e0fdf', false, 'UUID must have 16 bytes.'])]
    #[TestWith(['7628f4de-01bd-494b-a84b-d7f900521218', false, 'Not a valid version 5 UUID.'])]
    #[TestWith(['7628f4de-01bd-494b-a84b-d7f900521218', true, 'Not a valid version 5 UUID.'])]
    #[TestWith(['ZZlf8060-4a64-4f50-9e10-882cb74461f7', false, 'Invalid hexadecimal in UUID.'])]
    #[TestWith(['ZZlf8060-4a64-4f50-9e10-882cb74461f7', true, 'Invalid UUID string.'])]
    public function it_parses_error(string $in, bool $lazy, string $expectedError): void
    {
        $this->expectException(ParsingError::class);
        $this->expectExceptionMessage($expectedError);
        Version5::parse($in, $lazy);
    }

    /**
     * @throws \JsonException
     */
    #[Test]
    public function it_serializes(): void
    {
        $v5 = Version5::parse('5fe80e27-269a-5cce-98c3-989ddd181b71', false);

        $serialized = \serialize($v5);
        $json = \json_encode(['uuid' => $v5], JSON_THROW_ON_ERROR);

        $hash = \md5($serialized);

        $this->assertSame(
            '47c422afb014e163834dc042a101dc2c',
            $hash,
            'Does not match hash of serialized data: ' . $serialized,
        );
        $this->assertTrue($v5->equals(\unserialize($serialized)));
        $this->assertSame('{"uuid":"5fe80e27-269a-5cce-98c3-989ddd181b71"}', $json);
        $this->assertSame('5fe80e27-269a-5cce-98c3-989ddd181b71', (string) $v5);
    }
}
