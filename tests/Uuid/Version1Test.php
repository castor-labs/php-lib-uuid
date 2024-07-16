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

use Brick\Math\BigInteger;
use Castor\Bytes;
use Castor\Uuid\System\Time;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class Version1Test extends TestCase
{
    #[Test]
    public function it_generates(): void
    {
        $state = new System\State\Fixed(
            Time::fromTimestamp(BigInteger::of('139127190012012330')),
            Bytes::fromHex('0001'),
            Bytes::fromHex('00b0d063c226')
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
            new Time(Bytes::fromHex('01ee4782395c14c4')),
            Bytes::fromHex('17ae'),
            Bytes::fromHex('0242ac1b0004')
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
        $v1 = Version1::parse('5102999c-4771-11ee-be56-0242ac120002');

        $serialized = \serialize($v1);
        $json = \json_encode(['uuid' => $v1], JSON_THROW_ON_ERROR);

        $this->assertSame('O:20:"Castor\Uuid\Version1":1:{i:0;s:36:"5102999c-4771-11ee-be56-0242ac120002";}', $serialized);
        $this->assertTrue($v1->equals(\unserialize($serialized)));
        $this->assertSame('{"uuid":"5102999c-4771-11ee-be56-0242ac120002"}', $json);
        $this->assertSame('5102999c-4771-11ee-be56-0242ac120002', (string) $v1);
    }
}
