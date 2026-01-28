<?php

declare(strict_types=1);

namespace Castor\Uuid\System\Time;

use Brick\DateTime\Clock;
use Brick\DateTime\Instant;
use Castor\Encoding\Failure;
use Castor\Uuid\ByteArray;
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
        public ByteArray $bytes,
    ) {
        if ($this->bytes->count() !== 8) {
            throw new \InvalidArgumentException('Gregorian time must be 64 bits long');
        }
    }

    public static function fromTimestamp(string $timestamp): self
    {
        if (!\is_numeric($timestamp)) {
            throw new \InvalidArgumentException('Timestamp must be a valid numeric string');
        }

        $hex = \str_pad(\base_convert($timestamp, 10, 16), 16, '0', STR_PAD_LEFT);

        try {
            return new self(ByteArray::fromHex($hex));
        } catch (Failure $e) {
            throw new \RuntimeException('Impossible error', previous: $e);
        }
    }

    public static function fromInstant(Instant $instant): self
    {
        $epochSeconds = (string) $instant->getEpochSecond();
        $nanoSeconds = (string) $instant->getNano();

        $secondsTicks = \bcmul($epochSeconds, self::SECOND_INTERVALS);
        $nanoTicks = \bcdiv($nanoSeconds, '100');
        $ticksSinceEpoch = \bcadd($secondsTicks, $nanoTicks);

        return self::fromTimestamp(\bcadd($ticksSinceEpoch, self::GREGORIAN_TO_UNIX_OFFSET, 0));
    }

    public static function now(Clock $clock): self
    {
        return self::fromInstant($clock->getTime());
    }

    public function getInstant(): Instant
    {
        $ticksSinceEpoch = \bcsub($this->getTimestamp(), self::GREGORIAN_TO_UNIX_OFFSET); // Subtract gregorian offset

        $epochSeconds = \bcdiv($ticksSinceEpoch, self::SECOND_INTERVALS);
        $nanoSeconds = \bcmul(\bcmod($ticksSinceEpoch, self::SECOND_INTERVALS), '100');

        return Instant::of((int) $epochSeconds, (int) $nanoSeconds);
    }

    /**
     * Returns the number of 100 nanosecond intervals since 1582-10-15 00:00:00 UTC as a numeric string.
     */
    public function getTimestamp(): string
    {
        return \base_convert($this->bytes->toHex(), 16, 10);
    }
}
