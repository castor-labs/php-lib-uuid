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
use Random\Engine\Mt19937;
use Random\Randomizer;

class Version4Test extends TestCase
{
    #[Test]
    public function it_generates(): void
    {
        $uuid = Version4::generate(new Randomizer(new Mt19937(1000)));

        $this->assertSame('b3a551a7-5712-4d34-8718-711dc00e429c', $uuid->toString());
    }

    #[Test]
    #[TestWith(['fa06067f-602d-404a-a34c-45c6a7744011'])]
    #[TestWith(['a959e53b-3b3f-4995-a95b-e117ff790662'])]
    #[TestWith(['0f342954-20e6-4431-9445-630ad3e8c48a'])]
    public function it_parses(string $v4): void
    {
        $this->expectNotToPerformAssertions();
        Version4::parse($v4);
    }

    #[Test]
    #[TestWith(['00010f-0405-4607-8809-0a0b0c0d0e0f', false, 'UUID must have 16 bytes.'])]
    #[TestWith(['00010203-0405-4607-8809-0a0b0c0d0e0fdf', false, 'UUID must have 16 bytes.'])]
    #[TestWith(['5bbf8060-4a64-1f50-9e10-882cb74461f7', false, 'Not a valid version 4 UUID.'])]
    #[TestWith(['5bbf8060-4a64-1f50-9e10-882cb74461f7', true, 'Not a valid version 4 UUID.'])]
    #[TestWith(['ZZlf8060-4a64-4f50-9e10-882cb74461f7', false, 'Invalid hexadecimal in UUID.'])]
    #[TestWith(['ZZlf8060-4a64-4f50-9e10-882cb74461f7', true, 'Invalid UUID string.'])]
    public function it_parses_with_error(string $in, bool $lazy, string $expectedError): void
    {
        $this->expectException(ParsingError::class);
        $this->expectExceptionMessage($expectedError);
        Version4::parse($in, $lazy);
    }

    #[Test]
    public function it_generates_unique_v4(): void
    {
        $a = Version4::generate()->toString();
        $b = Version4::generate()->toString();

        $this->assertNotSame($a, $b);
    }

    #[Test]
    public function it_serializes(): void
    {
        $v4 = Version4::parse('fa06067f-602d-404a-a34c-45c6a7744011');

        $serialized = \serialize($v4);
        $json = \json_encode(['uuid' => $v4], JSON_THROW_ON_ERROR);

        $this->assertSame('O:20:"Castor\Uuid\Version4":1:{i:0;s:36:"fa06067f-602d-404a-a34c-45c6a7744011";}', $serialized);
        $this->assertTrue($v4->equals(\unserialize($serialized)));
        $this->assertSame('{"uuid":"fa06067f-602d-404a-a34c-45c6a7744011"}', $json);
        $this->assertSame('fa06067f-602d-404a-a34c-45c6a7744011', (string) $v4);
    }
}
