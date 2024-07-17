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

namespace Castor\Uuid\System\State;

use Brick\DateTime\Clock;
use Castor\Bytes;
use Castor\Uuid\System\MacProvider;
use Castor\Uuid\System\MacProvider\Fallback;
use Castor\Uuid\System\MacProvider\FromOs;
use Castor\Uuid\System\State;
use Castor\Uuid\System\Time\Gregorian;
use Random\Engine\Secure;
use Random\Randomizer;

final class Standard implements State
{
    private static ?Standard $global = null;

    private Bytes $lastTimestamp;
    private Bytes $clockSequence;
    private Bytes $macAddress;

    public function __construct(
        private readonly Clock $clock,
        private readonly Randomizer $random,
        private readonly MacProvider $macProvider,
    ) {
        $this->macAddress = new Bytes('');
        $this->clockSequence = new Bytes('');
        $this->lastTimestamp = new Bytes('');
    }

    public static function global(): Standard
    {
        if (null === self::$global) {
            $randomizer = new Randomizer(new Secure());
            self::$global = new Standard(
                new Clock\SystemClock(),
                $randomizer,
                new FromOs(new Fallback($randomizer)),
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

    public function getTime(): Gregorian
    {
        $gregorianTime = Gregorian::now($this->clock);

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
        return new Bytes(\pack('n*', $this->random->getBytes(2)));
    }
}
