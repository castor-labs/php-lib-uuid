<?php

declare(strict_types=1);

namespace Castor\Uuid;

use Castor\Uuid\System\Time\Gregorian;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class Version1Test extends TestCase
{
    #[Test]
    public function it_generates(): void
    {
        $state = new System\State\Fixed(
            Gregorian::fromTimestamp('139127190012012330'),
            ByteArray::fromHex('0001'),
            ByteArray::fromHex('00b0d063c226'),
        );

        $v1 = Version1::generate($state);
        $this->assertSame('3343a72a-4771-11ee-8001-00b0d063c226', $v1->toString());

        $this->assertSame('139127190012012330', (string) $v1->getTime()->getTimestamp());
        $this->assertSame('2023-08-30T20:10:01.201233Z', $v1->getTime()->getInstant()->toISOString());
        $this->assertSame($v1->getTime()->bytes->toHex(), $state->getTime()->bytes->toHex());
        $this->assertSame('00b0d063c226', $v1->getNode()->toHex());
        $this->assertSame('0001', $v1->getClockSeq()->toHex());
    }

    #[Test]
    public function its_compatible_with_ramsey(): void
    {
        $state = new System\State\Fixed(
            new Gregorian(ByteArray::fromHex('01ee4782395c14c4')),
            ByteArray::fromHex('17ae'),
            ByteArray::fromHex('0242ac1b0004'),
        );

        $v1 = Version1::generate($state);
        $this->assertSame('395c14c4-4782-11ee-97ae-0242ac1b0004', $v1->toString());
        $this->assertSame('2023-08-30T22:11:52.872058Z', $v1->getTime()->getInstant()->toISOString());
    }

    #[Test]
    public function it_generates_multiple(): void
    {
        $previous = '';
        for ($i = 0; $i < 1000; ++$i) {
            $generated = Version1::generate()->toString();
            $this->assertNotSame($generated, $previous);
            $previous = $generated;
        }
    }

    #[Test]
    #[TestWith(['00010f-0405-4607-8809-0a0b0c0d0e0f', false, 'UUID must have 16 bytes.'])]
    #[TestWith(['00010203-0405-4607-8809-0a0b0c0d0e0fdf', false, 'UUID must have 16 bytes.'])]
    #[TestWith(['7628f4de-01bd-494b-a84b-d7f900521218', false, 'Not a valid version 1 UUID.'])]
    #[TestWith(['7628f4de-01bd-494b-a84b-d7f900521218', true, 'Not a valid version 1 UUID.'])]
    #[TestWith(['ZZlf8060-4a64-4f50-9e10-882cb74461f7', false, 'Invalid hexadecimal in UUID.'])]
    #[TestWith(['ZZlf8060-4a64-4f50-9e10-882cb74461f7', true, 'Invalid UUID string.'])]
    public function it_parses_with_error(string $in, bool $lazy, string $expectedError): void
    {
        $this->expectException(ParsingError::class);
        $this->expectExceptionMessage($expectedError);
        Version1::parse($in, $lazy);
    }

    #[Test]
    public function it_serializes(): void
    {
        $v1 = Version1::parse('5102999c-4771-11ee-be56-0242ac120002', false);

        $serialized = \serialize($v1);
        $json = \json_encode(['uuid' => $v1], JSON_THROW_ON_ERROR);

        $hash = \md5($serialized);

        $this->assertSame(
            'caba92f9403e49b428d6d7eec655bfbf',
            $hash,
            'Does not match hash of serialized data: ' . $serialized,
        );
        $this->assertTrue($v1->equals(\unserialize($serialized)));
        $this->assertSame('{"uuid":"5102999c-4771-11ee-be56-0242ac120002"}', $json);
        $this->assertSame('5102999c-4771-11ee-be56-0242ac120002', (string) $v1);
    }
}
