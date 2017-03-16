<?php
declare(strict_types = 1);

namespace Bajcik\IO;

class FileUtils
{

    /**
     *
     * @param string $path
     *
     * @return string
     */
    public static function normalize(string $path): string
    {
        // normalize directory separator
        $normalizedPath = preg_replace('/[\/\\\\]+/', DIRECTORY_SEPARATOR, $path);

        // remove trialing slash, preserve root, eg c:\ and / on linux
        $length = \strlen($normalizedPath);
        $lastCharIndex = $length - 1;
        if (isset($normalizedPath[$lastCharIndex]) && (DIRECTORY_SEPARATOR === $normalizedPath[$lastCharIndex])) {
            if ($length > 3) {
                return \substr($normalizedPath, 0, $length - 1);
            }
        }

        return $normalizedPath;
    }

}
