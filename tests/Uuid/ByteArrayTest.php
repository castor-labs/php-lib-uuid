<?php

declare(strict_types=1);

namespace Castor\Uuid;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ByteArrayTest extends TestCase
{
    #[Test]
    public function it_determines_equality_based_on_content(): void
    {
        $a = ByteArray::fromHex('ee25446ee6bf');
        $b = ByteArray::fromHex('ee25446ee6bf');

        $this->assertTrue($a->equals($b));
    }
}
