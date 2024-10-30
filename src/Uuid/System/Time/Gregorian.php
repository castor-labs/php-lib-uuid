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

/**
 * Represents the time as the number of 100-nanosecond intervals since the gregorian epoch.
 */
readonly class Gregorian implements Time
{
    /**
     * The number of 100-nanosecond intervals from the Gregorian calendar epoch
     * to the Unix epoch.
     */
    private const string GREGORIAN_TO_UNIX_OFFSET = '122192928000000000';

    /**
     * The number of 100-nanosecond intervals in one second.
     */
    private const string SECOND_INTERVALS = '10000000';

    public function __construct(
        public Bytes $bytes,
    ) {
        if ($this->bytes->len() !== 8) {
            throw new \InvalidArgumentException('Gregorian time must be 64 bits long');
        }
    }

    public static function fromTimestamp(BigInteger $timestamp): self
    {
        $hex = \str_pad($timestamp->toBase(16), 16, '0', STR_PAD_LEFT);

        try {
            return new self(Bytes::fromHex($hex));
        } catch (Error $e) {
            throw new \RuntimeException('Impossible error', previous: $e);
        }
    }

    public static function fromInstant(Instant $instant): self
    {
        $epochSeconds = BigInteger::of($instant->getEpochSecond());
        $nanoSeconds = BigInteger::of($instant->getNano());

        $secondsTicks = $epochSeconds->multipliedBy(self::SECOND_INTERVALS);
        $nanoTicks = $nanoSeconds->dividedBy(100, RoundingMode::DOWN);
        $ticksSinceEpoch = $secondsTicks->plus($nanoTicks);

        return self::fromTimestamp($ticksSinceEpoch->plus(self::GREGORIAN_TO_UNIX_OFFSET));
    }

    public static function now(Clock $clock): self
    {
        return self::fromInstant($clock->getTime());
    }

    public function getInstant(): Instant
    {
        $ticksSinceEpoch = $this->getTimestamp()->minus(self::GREGORIAN_TO_UNIX_OFFSET); // Subtract gregorian offset

        $epochSeconds = $ticksSinceEpoch->dividedBy(self::SECOND_INTERVALS, RoundingMode::DOWN);
        $nanoSeconds = $ticksSinceEpoch->remainder(self::SECOND_INTERVALS)->multipliedBy(100);

        return Instant::of($epochSeconds->toInt(), $nanoSeconds->toInt());
    }

    /**
     * Returns the number of 100 nanosecond intervals since 1582-10-15 00:00:00 UTC as a numeric string.
     */
    public function getTimestamp(): BigInteger
    {
        return BigInteger::fromBase($this->bytes->toHex(), 16);
    }
}
