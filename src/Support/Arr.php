<?php

namespace Ark4ne\Support;

/**
 * Class Arr
 *
 * @package Ark4ne\Support
 */
final class Arr
{

    /**
     * @param        $arr
     * @param string $separator
     * @param string $prefix
     * @param string $suffix
     *
     * @return string
     */
    public static function toString($arr, $separator = ' ', $prefix = '', $suffix = '')
    {
        $str = '';
        if (is_array($arr) && count($arr)) {
            $str = $prefix . implode($separator, $arr) . $suffix;
        }

        return $str;
    }
}
