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
     * @param array  $arr
     * @param string $separator
     * @param string $prefix
     * @param string $suffix
     *
     * @return string
     */
    public static function toString(array $arr, $separator = ' ', $prefix = '', $suffix = '')
    {
        $str = '';
        if (count($arr) > 0) {
            $str = $prefix . implode($separator, $arr) . $suffix;
        }

        return $str;
    }
}
