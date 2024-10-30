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
use Brick\Math\BigInteger;
use Brick\Math\RoundingMode;
use Castor\Bytes;
use Castor\Encoding\Error;
use Castor\Uuid\System\Time;

readonly class Unix implements Time
{
    public function __construct(
        public Bytes $bytes
    ) {
        if ($this->bytes->len() !== 6) {
            throw new \InvalidArgumentException('Unix time must be 48 bits long');
        }
    }

    public static function fromInstant(Instant $instant): self
    {
        $secondsInMilliseconds = BigInteger::of($instant->getEpochSecond())->multipliedBy(1000);
        $nanosInMilliseconds = BigInteger::of($instant->getNano())->dividedBy(1e+6, RoundingMode::DOWN);

        return self::fromTimestamp($secondsInMilliseconds->plus($nanosInMilliseconds));
    }

    public static function fromTimestamp(BigInteger $timestamp): self
    {
        $hex = \str_pad($timestamp->toBase(16), 12, '0', STR_PAD_LEFT);

        try {
            return new self(Bytes::fromHex($hex));
        } catch (Error $e) {
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
        $nanoSeconds = $timestamp->multipliedBy(1e+6);

        return Instant::of(0, $nanoSeconds->toInt());
    }

    /**
     * Returns the number of millisecond elapsed since 1970-01-01 00:00:00 UTC.
     */
    public function getTimestamp(): BigInteger
    {
        return BigInteger::fromBase($this->bytes->toHex(), 16);
    }
}
