<?php

namespace lib;

/**
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/7
 * Time: 12:47
 */
class SimpleRouter
{
    private static $_before;
    private static $_after;
    public static function before($cb)
    {
        self::$_before = $cb;
    }
    public static function after($cb)
    {
        self::$_after = $cb;
    }
    public static function dispatch($key = 'type')
    {
        if (!isset($_REQUEST[$key])) {
            $action = 'index';
        } else {
            $action = $_REQUEST[$key];
        }
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $func = "action_${method}_${action}";
        if (function_exists($func)) {
            self::doAction($func, $action);
        } else {
            $ajax_func = "action_ajax_${method}_${action}";
            if (function_exists($ajax_func)) {
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    self::doAction($ajax_func, $action);
                } else {
                    header('HTTP/1.1 400 ajax only');
                    echo "ajax only page";
                }
            } else {
                header('HTTP/1.1 404 not found');
                echo "404, not find action";
            }
        }
    }
    private static function doAction($func, $name)
    {
        if (self::$_before) {
            $before = self::$_before;
            if (false === $before($name)) {
                return;
            }
        }
        if ($func() !== false && self::$_after) {
            $after = self::$_after;
            $after();
        }
    }

}