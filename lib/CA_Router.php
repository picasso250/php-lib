<?php
/**
 * Router like /controller/action
 * or /user/23
 * or /user/23/talk
 * or /question/2333/answer/222
 *
 * nginx rule
    location /xxx/ {
        try_files $uri /xxx/index.php$is_args$args;
    }
 *
 * @version 1.0 can router
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/6
 * Time: 9:39
 */

namespace lib;

class CA_Router
{
    static $controller = 'index';
    static $action = 'index';
    static $c_id = 0;
    static $a_id = 0;

    static $page404;

    public static function dispatch($base = '')
    {

        $uri = $_SERVER['REQUEST_URI'];
        $path = explode('?', $uri)[0];
        if ($base) {
            if (strpos($path, $base) !== 0) {
                die("root wrong");
            }
            $path = substr($path, strlen($base));
        }
        self::do_dispatch_path($path);
    }

    private static function do_dispatch_path($path)
    {
        $a = explode('/', $path);
        $a = array_values(array_filter($a));
        if (empty($a)) {
            self::$controller = 'index';
            self::$action = 'index';
            self::do_control_action();
        } else {
            if (isset($a[0])) {
                self::$controller = $a[0];
            }
            if (isset($a[1])) {
                if (is_numeric($a[1])) {
                    self::$c_id = $a[1];
                    self::$action = 'view_';
                    if (isset($a[2])) {
                        self::$action = $a[2];
                        if (isset($a[3])) {
                            self::$a_id = $a[3];
                        }
                    }
                } else {
                    self::$action = $a[1];
                }
            }
            self::do_control_action();
        }
    }

    private static function do_control_action()
    {
        if (isset(self::$page404) && is_callable(self::$page404)) {
            $page404 = function () {
                header("HTTP/1.1 404 page not found");
                $p = self::$page404;
                $p();
            };
        } else {
            $page404 = function () {
                header("HTTP/1.1 404 page not found");
                die("page 404");
            };
        }
        $cc = "controller\\".self::$controller;
        if (!class_exists($cc)) {
            $page404();
            return;
        }
        $c = new $cc();
        $c->id = self::$c_id;
        $m = "action_".self::$action;
        if (!method_exists($c, $m)) {
            $page404();
            return;
        }
        $c->$m(self::$a_id);
    }
}