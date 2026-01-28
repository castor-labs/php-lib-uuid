<?php

declare(strict_types=1);

namespace Castor\Uuid\System\MacProvider;

use Castor\Arr;
use Castor\Encoding\Failure;
use Castor\Uuid\ByteArray;
use Castor\Uuid\System\MacProvider;

final class FromOs implements MacProvider
{
    /**
     * Pattern to match nodes in ifconfig and ipconfig output.
     */
    private const string IFCONFIG_PATTERN = '/[^:]([0-9a-f]{2}([:-])[0-9a-f]{2}(\2[0-9a-f]{2}){4})[^:]/i';

    /**
     * Pattern to match nodes in sysfs stream output.
     */
    private const string SYSFS_PATTERN = '/^([0-9a-f]{2}:){5}[0-9a-f]{2}$/i';

    /**
     * @param null|ByteArray[] $cached
     */
    public function __construct(
        private readonly MacProvider $next,
        private ?array $cached = null,
    ) {}

    /**
     * @return ByteArray[]
     */
    public function getMacAddresses(): array
    {
        $addresses = $this->tryGetMacAddresses();
        if ([] !== $addresses) {
            return $addresses;
        }

        return $this->next->getMacAddresses();
    }

    /**
     * @return ByteArray[]
     *
     * TODO: Adjust to work within docker containers
     */
    private function tryGetMacAddresses(): array
    {
        // If there is cache, we send that
        if (null !== $this->cached) {
            return $this->cached;
        }

        // First, we try with linux.
        $macs = $this->getFromSysFile();
        if ([] !== $macs) {
            $this->cached = $macs;

            return $macs;
        }

        // Otherwise, we try with console commands from different os
        $macs = $this->getFromConsoleCommand();
        $this->cached = $macs;

        return $macs;
    }

    /**
     * Returns MAC address from the first system interface via the sysfs interface.
     *
     * @return ByteArray[]
     */
    private function getFromSysFile(): array
    {
        /** @var string[] $macs */
        $macs = [];

        if ('LINUX' === \strtoupper(PHP_OS)) {
            $addressPaths = \glob('/sys/class/net/*/address', GLOB_NOSORT);

            if (false === $addressPaths || 0 === \count($addressPaths)) {
                return [];
            }

            Arr\walk($addressPaths, static function (string $addressPath) use (&$macs): void {
                if (\is_readable($addressPath)) {
                    $macs[] = \trim(\file_get_contents($addressPath));
                }
            });

            // Remove invalid entries.
            $macs = Arr\filter($macs, static function (string $address) {
                return '00:00:00:00:00:00' !== $address && \preg_match(self::SYSFS_PATTERN, $address);
            });
        }

        // Map any macs we have
        return [...Arr\map($macs, fn(string $mac): ByteArray => ByteArray::fromHex($this->cleanMac($mac)))];
    }

    /**
     * Returns the network interface configuration for the system.
     *
     * TODO: This code needs to be adjusted to work with different tool versions (ie. busybox)
     *
     * TODO: Otherwise, we need to rely in native OS APIs instead of console
     *
     * @codeCoverageIgnore
     *
     * @return ByteArray[]
     */
    private function getFromConsoleCommand(): array
    {
        /** @var ByteArray[] $macs */
        $macs = [];

        $disabledFunctions = \strtolower((string) \ini_get('disable_functions'));

        if (\str_contains($disabledFunctions, 'passthru')) {
            return [];
        }

        \ob_start();

        switch (\strtoupper(\substr(PHP_OS, 0, 3))) {
            case 'WIN':
                \passthru('ipconfig /all 2>&1');

                break;

            case 'DAR':
                \passthru('ifconfig 2>&1');

                break;

            case 'FRE':
                \passthru('netstat -i -f link 2>&1');

                break;

            case 'LIN':
            default:
                \passthru('netstat -ie 2>&1');

                break;
        }

        $ifconfig = (string) \ob_get_clean();

        $n = \preg_match_all(self::IFCONFIG_PATTERN, $ifconfig, $matches, PREG_PATTERN_ORDER);
        if (!\is_int($n)) {
            throw new \LogicException('Invalid regular expression');
        }

        if ($n > 0) {
            foreach ($matches[1] as $iface) {
                if ('00:00:00:00:00:00' !== $iface && '00-00-00-00-00-00' !== $iface) {
                    try {
                        $macs[] = ByteArray::fromHex($this->cleanMac($iface));
                    } catch (Failure) {
                        continue; // Ignore invalid macs
                    }
                }
            }
        }

        return [...$macs];
    }

    private function cleanMac(string $mac): string
    {
        return \str_replace([':', '-'], '', $mac);
    }
}
