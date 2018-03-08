<?php
/**
 * parser two kinds:
 * a:b
 * a {
 *    k: v
 * }
 *
 * @version 1.0
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/7
 * Time: 16:34
 */

namespace lib;

class HeaderParser
{
    private static function parse_string_($arr, &$pos = 0)
    {
        $kvs = array();
        while (isset($arr[$pos])) {
            $line = $arr[$pos++];
            if (preg_match('/^\s*([\w_]+):\s*(["\w_]+)\s*$/u', $line, $m)) {
                $kvs[$m[1]] = $m[2];
                continue;
            }
            if (preg_match('/^\s*([\w_]+)\s*\{\s*$/u', $line, $m)) {
                $kvs[$m[1]] = self::parse_string_($arr, $pos);
                continue;
            }
            if (preg_match('/^\s*}\s*$/', $line, $m)) {
                break;
            }
        }
        return $kvs;
    }

    public static function parseString($str) {
        $arr = str_getcsv($str, "\n");
        return self::parse_string_($arr);
    }

}