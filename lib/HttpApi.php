<?php
/**
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/7
 * Time: 17:22
 */

namespace lib;

/*
 * 请求别人的API
 * 1. 将所有的记录保留
 * 2. 有超时的时间，后端的server应该是响应快速的
 */
class HttpApi
{
    static $log;

    public static function getJson($url)
    {
        $res = self::get($url);
        $r = json_decode($res, true);
        if (json_last_error() && self::$log) {
            self::$log->error("json decode error '%s' %d", $res, json_last_error());
        }
        return $r;
    }
    public static function get($url)
    {
        $t = microtime(true);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_URL, $url);
        $str = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        $t = microtime(true) - $t;
        if (self::$log) {
            self::$log->info(__FUNCTION__."\t%s [%s] (%ss) => '%s'", $url, $info['http_code'], $t, $str);
        }
        return $str;
    }

}