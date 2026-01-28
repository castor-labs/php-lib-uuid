<?php

declare(strict_types=1);

namespace Castor\Uuid;

use Castor\Uuid\System\Time\Gregorian;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class Version6Test extends TestCase
{
    #[Test]
    public function it_generates(): void
    {
        $state = new System\State\Fixed(
            Gregorian::fromTimestamp('139127190012012330'),
            ByteArray::fromHex('0001'),
            ByteArray::fromHex('00b0d063c226'),
        );

        $v6 = Version6::generate($state);

        $this->assertSame('1ee47713-343a-672a-8001-00b0d063c226', $v6->toString());
        $this->assertSame('139127190012012330', (string) $v6->getTime()->getTimestamp());
        $this->assertSame('2023-08-30T20:10:01.201233Z', $v6->getTime()->getInstant()->toISOString());
        $this->assertSame('00b0d063c226', $v6->getNode()->toHex());
        $this->assertSame('0001', $v6->getClockSeq()->toHex());
        $this->assertSame($v6->getTime()->bytes->toHex(), $state->getTime()->bytes->toHex());
    }

    #[Test]
    public function it_generates_multiple(): void
    {
        $previous = '';
        for ($i = 0; $i < 1000; ++$i) {
            $generated = Version6::generate()->toString();
            $this->assertNotSame($generated, $previous);
            $previous = $generated;
        }
    }

    #[Test]
    #[TestWith(['00010f-0405-4607-8809-0a0b0c0d0e0f', false, 'UUID must have 16 bytes.'])]
    #[TestWith(['00010203-0405-4607-8809-0a0b0c0d0e0fdf', false, 'UUID must have 16 bytes.'])]
    #[TestWith(['7628f4de-01bd-494b-a84b-d7f900521218', false, 'Not a valid version 6 UUID.'])]
    #[TestWith(['7628f4de-01bd-494b-a84b-d7f900521218', true, 'Not a valid version 6 UUID.'])]
    #[TestWith(['ZZlf8060-4a64-4f50-9e10-882cb74461f7', false, 'Invalid hexadecimal in UUID.'])]
    #[TestWith(['ZZlf8060-4a64-4f50-9e10-882cb74461f7', true, 'Invalid UUID string.'])]
    public function it_parses_with_error(string $in, bool $lazy, string $expectedError): void
    {
        $this->expectException(ParsingError::class);
        $this->expectExceptionMessage($expectedError);
        Version6::parse($in, $lazy);
    }

    #[Test]
    public function it_serializes(): void
    {
        $v6 = Version6::parse('1ee47713-343a-672a-8001-00b0d063c226', false);

        $serialized = \serialize($v6);
        $json = \json_encode(['uuid' => $v6], JSON_THROW_ON_ERROR);

        $hash = \md5($serialized);

        $this->assertSame(
            '077ffc6a8dc1d17a78309ce9d3c8473c',
            $hash,
            'Does not match hash of serialized data: ' . $serialized,
        );
        $this->assertTrue($v6->equals(\unserialize($serialized)));
        $this->assertSame('{"uuid":"1ee47713-343a-672a-8001-00b0d063c226"}', $json);
        $this->assertSame('1ee47713-343a-672a-8001-00b0d063c226', (string) $v6);
    }
}
