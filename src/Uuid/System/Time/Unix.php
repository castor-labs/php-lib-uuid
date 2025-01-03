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

namespace Castor\Uuid\System\Time;

use Brick\DateTime\Clock;
use Brick\DateTime\Instant;
use Castor\Encoding\Failure;
use Castor\Uuid\ByteArray;
use Castor\Uuid\System\Time;

readonly class Unix implements Time
{
    public function __construct(
        public ByteArray $bytes
    ) {
        if ($this->bytes->count() !== 6) {
            throw new \InvalidArgumentException('Unix time must be 48 bits long');
        }
    }

    public static function fromInstant(Instant $instant): self
    {
        $secondsInMilliseconds = \bcmul((string) $instant->getEpochSecond(), '1000');
        $nanosInMilliseconds = \bcdiv((string) $instant->getNano(), (string) 1e+6);

        return self::fromTimestamp(\bcadd($secondsInMilliseconds, $nanosInMilliseconds, 0));
    }

    public static function fromTimestamp(string $timestamp): self
    {
        if (!\is_numeric($timestamp)) {
            throw new \InvalidArgumentException('Timestamp must be a valid numeric string');
        }

        $hex = \str_pad(\base_convert($timestamp, 10, 16), 12, '0', STR_PAD_LEFT);

        try {
            return new self(ByteArray::fromHex($hex));
        } catch (Failure $e) {
            throw new \RuntimeException('Impossible error', previous: $e);
        }
    }

    public static function now(Clock $clock): self
    {
        return self::fromInstant($clock->getTime());
    }

    public function getInstant(): Instant
    {
        $timestamp = $this->getTimestamp();
        $nanoSeconds = \bcmul($timestamp, (string) 1e+6);

        return Instant::of(0, (int) $nanoSeconds);
    }

    /**
     * Returns the number of millisecond elapsed since 1970-01-01 00:00:00 UTC.
     */
    public function getTimestamp(): string
    {
        return \base_convert($this->bytes->toHex(), 16, 10);
    }
}
