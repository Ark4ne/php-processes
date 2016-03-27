<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 10/01/2016
 * Time: 00:24
 */

namespace Ark4ne\Support;

/**
 * Class Str
 *
 * @package Ark4ne\Support
 */
final class Str
{

    /**
     * @param $var
     *
     * @return string
     */
    public static function fromVar($var)
    {
        switch (true) {
            case is_null($var):
                return '';
            case is_bool($var):
                return ($var ? '1' : '0');
            case is_string($var):
                return $var;
            case is_numeric($var):
            case method_exists($var, '__toString'):
                return (string)$var;
            case $var instanceof \Serializable:
                return serialize($var);
            default:
                return json_encode($var);
        }
    }
}
