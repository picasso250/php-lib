<?php
/**
 * @version 1.0.0 基本功能
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/4
 * Time: 15:55
 */

namespace lib;

class Request
{
    public static function post($name, $default = '')
    {
        return isset($_POST[$name]) ? trim($_POST[$name]) : $default;
    }
    public static function get($name, $default = '')
    {
        return isset($_GET[$name]) ? trim($_GET[$name]) : $default;
    }

    public static function is_post()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }
}