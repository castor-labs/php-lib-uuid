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

namespace Castor\Uuid\V1;

use Brick\DateTime\Clock;
use Castor\Bytes;
use Castor\Io\Reader;
use Castor\Random;
use Castor\Uuid\System\MacProvider;
use Castor\Uuid\System\MacProvider\Fallback;
use Castor\Uuid\System\MacProvider\FromOs;

final class Simplified implements State
{
    private static ?Simplified $global = null;

    private Bytes $lastTimestamp;
    private Bytes $clockSequence;
    private Bytes $macAddress;

    public function __construct(
        private readonly Clock $clock,
        private readonly Reader $random,
        private readonly MacProvider $macProvider,
    ) {
        $this->macAddress = new Bytes('');
        $this->clockSequence = new Bytes('');
        $this->lastTimestamp = new Bytes('');
    }

    public static function global(): Simplified
    {
        if (null === self::$global) {
            self::$global = new Simplified(
                new Clock\SystemClock(),
                Random\Source::secure(),
                new FromOs(new Fallback(Random\Source::secure())),
            );
        }

        return self::$global;
    }

    public function getClockSequence(): Bytes
    {
        if (0 === $this->clockSequence->len()) {
            $this->clockSequence = $this->generateClockSequence();
        }

        return $this->clockSequence;
    }

    public function getTime(): GregorianTime
    {
        $gregorianTime = GregorianTime::now($this->clock);

        if ($gregorianTime->bytes->equals($this->lastTimestamp)) {
            $this->clockSequence = $this->generateClockSequence();
        } else {
            $this->lastTimestamp = $gregorianTime->bytes;
        }

        return $gregorianTime;
    }

    public function getNode(): Bytes
    {
        if (0 === $this->macAddress->len()) {
            $macs = $this->macProvider->getMacAddresses();
            $this->macAddress = $macs[0];
        }

        return $this->macAddress;
    }

    private function generateClockSequence(): Bytes
    {
        return new Bytes(\pack('n*', $this->random->read(2)));
    }
}
