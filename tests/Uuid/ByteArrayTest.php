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
