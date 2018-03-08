<?php
/**
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/4
 * Time: 15:23
 */

namespace lib;

/**
 * 和 输出 相关的类
 * Class Response
 * @version 1.0 基本功能（输出json、报错、渲染html）
 * @package lib
 */
class Response
{
    static $layout;
    static $view_root = __DIR__;

    public static function json($a)
    {
        echo json_encode($a);
    }
    public static function error($code, $msg)
    {
        header("HTTP/1.1 $code error");
        echo $msg;
    }

    public static function ok()
    {
        echo "OK";
    }

    public static function render($_tpl, $_data)
    {
        extract($_data);
        if (self::$layout) {
            include self::$layout;
        } else {
            include self::$view_root."/$_tpl";
        }
    }
}