<?php
declare(strict_types = 1);

namespace Bajcik\Util;

/**
 *
 * @author Paweł Cichorowski <pawel.cichorowski@gmail.com>
 *
 */
class StringUtils
{

    public static $encoding = 'UTF-8';

    public static function initialize()
    {
        $encoding = self::getDefaultEncoding();
        mb_internal_encoding($encoding);
        mb_regex_encoding($encoding);
    }

    public static function getDefaultEncoding(): string
    {
        return self::$encoding;
    }

    public static function is($value): bool
    {
        return is_string($value);
    }

    public static function isEncodedIn(string $string, string $encoding): bool
    {
        // alternative: $isUTF8 = preg_match('//u', $string);
        return mb_check_encoding($string, $encoding);
    }

    public static function parse($value): string
    {
        return strval($value);
    }

    public static function isEmpty($value, bool $trim = true): bool
    {
        return (is_string($value) && (($trim ? self::trim($value) : $value) == '') || (null === $value) || (is_bool($value) && !$value) || (is_array($value) && empty($value)));
    }

    public static function splitByNewLineDelimeter(string $string, bool $keepEmptyLines = true, int $limit = 0): array
    {
        return preg_split('/$\R?^/um', $string, $limit, $keepEmptyLines ? null : PREG_SPLIT_NO_EMPTY);
    }

    public static function splitByWhiteSpace(string $string, int $limit = 0): array
    {
        return preg_split('/\s+/um', $string, $limit, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Replaces all accented characters to non-accented characters.
     *
     * @param
     *            $string
     *
     * @return string
     * @static
     *
     */
    public static function translit(string $string): string
    {
        $string = self::_translitReplaceCommonChars($string);

        $encoding = self::getDefaultEncoding();
        $string = iconv($encoding, $encoding . '//IGNORE', $string);
        $result = '';
        $a = preg_match_all('/([^\p{L}]*)([\p{L}]+)([^\p{L}]*)/u', $string, $matches, PREG_SET_ORDER);
        if (empty($matches)) {
            return $string;
        } else {
            foreach ($matches as $match) {
                $match[2] = preg_replace('/[^\p{L}]+/u', '', iconv($encoding, 'ASCII//TRANSLIT', $match[2]));
                $result .= $match[1] . $match[2] . $match[3];
            }
        }

        return $result;
    }

    private static function _translitReplaceCommonChars(string $string): string
    {
        $table = [
            'À' => 'A',
            'Á' => 'A',
            'Â' => 'A',
            'Ã' => 'A',
            'Ä' => 'A',
            'Å' => 'A',
            'Ă' => 'A',
            'Ā' => 'A',
            'Ą' => 'A',
            'Æ' => 'A',
            'Ǽ' => 'A',
            'à' => 'a',
            'á' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'ä' => 'a',
            'å' => 'a',
            'ă' => 'a',
            'ā' => 'a',
            'ą' => 'a',
            'æ' => 'a',
            'ǽ' => 'a',
            'Þ' => 'B',
            'þ' => 'b',
            'ß' => 'Ss',
            'Ç' => 'C',
            'Č' => 'C',
            'Ć' => 'C',
            'Ĉ' => 'C',
            'Ċ' => 'C',
            'ç' => 'c',
            'č' => 'c',
            'ć' => 'c',
            'ĉ' => 'c',
            'ċ' => 'c',
            'Đ' => 'Dj',
            'Ď' => 'D',
            'Đ' => 'D',
            'đ' => 'dj',
            'ď' => 'd',
            'È' => 'E',
            'É' => 'E',
            'Ê' => 'E',
            'Ë' => 'E',
            'Ĕ' => 'E',
            'Ē' => 'E',
            'Ę' => 'E',
            'Ė' => 'E',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'ĕ' => 'e',
            'ē' => 'e',
            'ę' => 'e',
            'ė' => 'e',
            'Ĝ' => 'G',
            'Ğ' => 'G',
            'Ġ' => 'G',
            'Ģ' => 'G',
            'ĝ' => 'g',
            'ğ' => 'g',
            'ġ' => 'g',
            'ģ' => 'g',
            'Ĥ' => 'H',
            'Ħ' => 'H',
            'ĥ' => 'h',
            'ħ' => 'h',
            'Ì' => 'I',
            'Í' => 'I',
            'Î' => 'I',
            'Ï' => 'I',
            'İ' => 'I',
            'Ĩ' => 'I',
            'Ī' => 'I',
            'Ĭ' => 'I',
            'Į' => 'I',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'į' => 'i',
            'ĩ' => 'i',
            'ī' => 'i',
            'ĭ' => 'i',
            'ı' => 'i',
            'Ĵ' => 'J',
            'ĵ' => 'j',
            'Ķ' => 'K',
            'ķ' => 'k',
            'ĸ' => 'k',
            'Ĺ' => 'L',
            'Ļ' => 'L',
            'Ľ' => 'L',
            'Ŀ' => 'L',
            'Ł' => 'L',
            'ĺ' => 'l',
            'ļ' => 'l',
            'ľ' => 'l',
            'ŀ' => 'l',
            'ł' => 'l',
            'Ñ' => 'N',
            'Ń' => 'N',
            'Ň' => 'N',
            'Ņ' => 'N',
            'Ŋ' => 'N',
            'ñ' => 'n',
            'ń' => 'n',
            'ň' => 'n',
            'ņ' => 'n',
            'ŋ' => 'n',
            'ŉ' => 'n',
            'Ò' => 'O',
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ö' => 'O',
            'Ø' => 'O',
            'Ō' => 'O',
            'Ŏ' => 'O',
            'Ő' => 'O',
            'Œ' => 'O',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ö' => 'o',
            'ø' => 'o',
            'ō' => 'o',
            'ŏ' => 'o',
            'ő' => 'o',
            'œ' => 'o',
            'ð' => 'o',
            'Ŕ' => 'R',
            'Ř' => 'R',
            'ŕ' => 'r',
            'ř' => 'r',
            'ŗ' => 'r',
            'Š' => 'S',
            'Ŝ' => 'S',
            'Ś' => 'S',
            'Ş' => 'S',
            'š' => 's',
            'ŝ' => 's',
            'ś' => 's',
            'ş' => 's',
            'Ŧ' => 'T',
            'Ţ' => 'T',
            'Ť' => 'T',
            'ŧ' => 't',
            'ţ' => 't',
            'ť' => 't',
            'Ù' => 'U',
            'Ú' => 'U',
            'Û' => 'U',
            'Ü' => 'U',
            'Ũ' => 'U',
            'Ū' => 'U',
            'Ŭ' => 'U',
            'Ů' => 'U',
            'Ű' => 'U',
            'Ų' => 'U',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ü' => 'u',
            'ũ' => 'u',
            'ū' => 'u',
            'ŭ' => 'u',
            'ů' => 'u',
            'ű' => 'u',
            'ų' => 'u',
            'Ŵ' => 'W',
            'Ẁ' => 'W',
            'Ẃ' => 'W',
            'Ẅ' => 'W',
            'ŵ' => 'w',
            'ẁ' => 'w',
            'ẃ' => 'w',
            'ẅ' => 'w',
            'Ý' => 'Y',
            'Ÿ' => 'Y',
            'Ŷ' => 'Y',
            'ý' => 'y',
            'ÿ' => 'y',
            'ŷ' => 'y',
            'Ž' => 'Z',
            'Ź' => 'Z',
            'Ż' => 'Z',
            'Ž' => 'Z',
            'ž' => 'z',
            'ź' => 'z',
            'ż' => 'z',
            'ž' => 'z',
            '“' => '"',
            '”' => '"',
            '‘' => "'",
            '’' => "'",
            '•' => '-',
            '…' => '...',
            '—' => '-',
            '–' => '-',
            '¿' => '?',
            '¡' => '!',
            '°' => ' degrees ',
            '¼' => ' 1/4 ',
            '½' => ' 1/2 ',
            '¾' => ' 3/4 ',
            '⅓' => ' 1/3 ',
            '⅔' => ' 2/3 ',
            '⅛' => ' 1/8 ',
            '⅜' => ' 3/8 ',
            '⅝' => ' 5/8 ',
            '⅞' => ' 7/8 ',
            '÷' => ' divided by ',
            '×' => ' times ',
            '±' => ' plus-minus ',
            '√' => ' square root ',
            '∞' => ' infinity ',
            '≈' => ' almost equal to ',
            '≠' => ' not equal to ',
            '≡' => ' identical to ',
            '≤' => ' less than or equal to ',
            '≥' => ' greater than or equal to ',
            '←' => ' left ',
            '→' => ' right ',
            '↑' => ' up ',
            '↓' => ' down ',
            '↔' => ' left and right ',
            '↕' => ' up and down ',
            '℅' => ' care of ',
            '℮' => ' estimated ',
            'Ω' => ' ohm ',
            '♀' => ' female ',
            '♂' => ' male ',
            '©' => ' Copyright ',
            '®' => ' Registered ',
            '™' => ' Trademark ',
        ];

        $string = strtr($string, $table);
        // Currency symbols: £¤¥€ - we dont bother with them for now
        $string = preg_replace("/[^\x9\xA\xD\x20-\x7F]/u", "", $string);

        return $string;
    }

    /**
     * Returns a string with all spaces converted to delimeter (by default), accented
     * characters converted to non-accented characters, and non word characters replaced by delimeter.
     * Result is trimmed.
     *
     * @param
     *            $string
     * @param $delimeter , default
     *            is '-'
     *
     * @return string
     * @static
     *
     *
     *
     */
    public static function slugify(string $string, string $delimeter = '_'): string
    {
        $string = self::toLower($string);
        $string = self::translit($string);
        $string = preg_replace('/[^\p{L}\s]+/u', $delimeter, $string);
        $string = str_replace($delimeter, ' ', $string);
        $string = preg_replace('/(\s+)/u', ' ', $string);
        $string = self::trim($string);
        $string = str_replace(' ', $delimeter, $string);

        return $string;
    }

    /**
     * Returns the given lower_case_and_underscored_word as a CamelCased word.
     * Result is trimmed.
     *
     * @param string $lower_case_and_underscored_word
     *            Word to camelize
     *
     * @return string Camelized word. LikeThis.
     * @static
     *
     *
     *
     */
    public static function camelizeUpper(string $lowerCaseAndUnderscoredWord): string
    {
        return str_replace(' ', '', mb_convert_case(str_replace('_', ' ', str_replace('-', ' ', $lowerCaseAndUnderscoredWord)), MB_CASE_TITLE, self::$encoding));
    }

    public static function camelizeLower(string $lowerCaseAndUnderscoredWord): string
    {
        return self::lowerCaseFirstLetter(self::camelizeUpper($lowerCaseAndUnderscoredWord));
    }

    public static function upperCaseFirstLetter(string $string): string
    {
        $fc = mb_strtoupper(mb_substr($string, 0, 1, self::$encoding), self::$encoding);

        return $fc . mb_substr($string, 1, mb_strlen($string, self::$encoding), self::$encoding);
    }

    public static function lowerCaseFirstLetter(string $string): string
    {
        $fc = mb_strtolower(mb_substr($string, 0, 1, self::$encoding), self::$encoding);

        return $fc . mb_substr($string, 1, mb_strlen($string, self::$encoding), self::$encoding);
    }

    /**
     * Returns the given camelCasedWord as an underscored_word.
     * Result is trimmed.
     *
     * @param string $camelCasedWord
     *            Camel-cased word to be "underscorized"
     *
     * @return string Underscore-syntaxed version of the $camelCasedWord
     * @static
     *
     *
     *
     */
    public static function underscore(string $camelCasedWord): string
    {
        return self::trim(mb_strtolower(preg_replace('/(?<=\p{L})([\p{Lu}])/u', '_\1', $camelCasedWord), self::$encoding));
    }

    public static function toUpper(string $string): string
    {
        return mb_strtoupper($string, self::$encoding);
    }

    public static function toLower(string $string): string
    {
        return mb_strtolower($string, self::$encoding);
    }

    public static function toUpperFirst(string $string): string
    {
        if (isset($string[0])) {
            $string[0] = mb_strtoupper($string[0], self::$encoding);
        }

        return $string;
    }

    public static function toLowerFirst(string $string): string
    {
        if (isset($string[0])) {
            $string[0] = mb_strtolower($string[0], self::$encoding);
        }

        return $string;
    }

    public static function substring($string, $start, $length = null)
    {
        if (null === $length) {
            return mb_substr($string, $start, mb_strlen($string, self::$encoding), self::$encoding);
        } else {
            return mb_substr($string, $start, $length, self::$encoding);
        }
    }

    public static function trim($string)
    {
        return preg_replace('/^[\pZ\pC]+([\PZ\PC]*)[\pZ\pC]+$/u', '$1', $string);
    }

    public static function startsWith(string $string, string $pattern): bool
    {
        return !strncmp($string, $pattern, strlen($pattern));
    }

    public static function endsWith(string $string, string $pattern): bool
    {
        return !strncmp(strrev($string), strrev($pattern), strlen($pattern));
    }

    public static function containsCaseSensitive(string $haystack, string $needle): bool
    {
        return false !== strpos($haystack, $needle);
    }

    public static function containsIgnoreCase(string $haystack, string $needle): bool
    {
        return false !== strpos(self::toUpper($haystack), self::toUpper($needle));
    }

}
