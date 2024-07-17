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

use Brick\DateTime\Instant;
use Castor\Uuid\System\Time\Unix;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Random\Randomizer;

class Version7Test extends TestCase
{
    #[Test]
    public function it_generates(): void
    {
        $v7 = Version7::generate(
            Unix::fromInstant(Instant::of(1721172188, 86590000)),
            new Randomizer(new Mt19937(100))
        );

        $this->assertSame('0190bddb-5bb6-7896-9c8b-18dbd0ab4337', $v7->toString());
        $this->assertSame('1721172188086', (string) $v7->getTime()->getTimestamp());
        $this->assertSame('2024-07-16T23:23:08.086Z', $v7->getTime()->getInstant()->toISOString());
    }

    #[Test]
    public function it_generates_multiple(): void
    {
        $previous = '';
        for ($i = 0; $i < 1000; ++$i) {
            $generated = Version7::generate()->toString();
            $this->assertNotSame($generated, $previous);
            $previous = $generated;
        }
    }

    #[Test]
    #[TestWith(['00010f-0405-4607-8809-0a0b0c0d0e0f', false, 'UUID must have 16 bytes.'])]
    #[TestWith(['00010203-0405-4607-8809-0a0b0c0d0e0fdf', false, 'UUID must have 16 bytes.'])]
    #[TestWith(['7628f4de-01bd-494b-a84b-d7f900521218', false, 'Not a valid version 7 UUID.'])]
    #[TestWith(['7628f4de-01bd-494b-a84b-d7f900521218', true, 'Not a valid version 7 UUID.'])]
    #[TestWith(['ZZlf8060-4a64-4f50-9e10-882cb74461f7', false, 'Invalid hexadecimal in UUID.'])]
    #[TestWith(['ZZlf8060-4a64-4f50-9e10-882cb74461f7', true, 'Invalid UUID string.'])]
    public function it_parses_with_error(string $in, bool $lazy, string $expectedError): void
    {
        $this->expectException(ParsingError::class);
        $this->expectExceptionMessage($expectedError);
        Version7::parse($in, $lazy);
    }

    #[Test]
    public function it_serializes(): void
    {
        $v7 = Version7::parse('0190bddb-5bb6-7896-9c8b-18dbd0ab4337');

        $serialized = \serialize($v7);
        $json = \json_encode(['uuid' => $v7], JSON_THROW_ON_ERROR);

        $this->assertSame('O:20:"Castor\Uuid\Version7":1:{i:0;s:36:"0190bddb-5bb6-7896-9c8b-18dbd0ab4337";}', $serialized);
        $this->assertTrue($v7->equals(\unserialize($serialized)));
        $this->assertSame('{"uuid":"0190bddb-5bb6-7896-9c8b-18dbd0ab4337"}', $json);
        $this->assertSame('0190bddb-5bb6-7896-9c8b-18dbd0ab4337', (string) $v7);
    }
}
