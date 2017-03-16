<?php
declare(strict_types = 1);

namespace Bajcik\Util;

/**
 *
 * @author Paweł Cichorowski <pawel.cichorowski@gmail.com>
 *
 */
class SystemUtils
{

    /**
     * Checks that Windows is currently running operating system.
     *
     * @return boolean
     */
    public static function isWindowsOperatingSystem(): bool
    {
        $os = strtoupper(substr(PHP_OS, 0, 3));

        return ($os === 'WIN');
    }

    /**
     * Checks that Mac OS is currently running operating system.
     *
     * @return boolean
     */
    public static function isMacintoshOperatingSystem(): bool
    {
        $os = strtoupper(substr(PHP_OS, 0, 3));

        return ($os === 'MAC');
    }

    /**
     * Checks that Unix is currently running operating system.
     *
     * @return boolean
     */
    public static function isUnixOperatingSystem(): bool
    {
        $os = strtoupper(substr(PHP_OS, 0, 3));

        return (($os !== 'MAC') && ($os !== 'WIN'));
    }

    public static function getFileUploadMaxSize()
    {
        static $max_size = false;

        if (false === $max_size) {
            // Start with post_max_size.
            $max_size = self::parse_size(ini_get('post_max_size'));
            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = self::parse_size(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }

        return $max_size;
    }

    private static function parse_size($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }

}
