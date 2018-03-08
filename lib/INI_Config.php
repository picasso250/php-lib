<?php
/**
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/23
 * Time: 14:46
 */

namespace lib;


class INI_Config
{
    static function find_file($root, $name, $ext)
    {
        if (!defined('ENV')) {
            $f = "$root/env";
            if (is_file($f)) {
                $c = file_get_contents($f);
                $a = trim($c);
                define('ENV', strtoupper($a));
            } else {
                define('ENV', 'PRD');
            }
        }
        $f = "$root/$name." . ENV . $ext;
        if (is_file($f)) {
            return $f;
        }
        $f = "$root/$name".$ext;
        if (!is_file($f)) {
            die("no config file");
        }
        return $f;
    }
    static function load($root, $name)
    {
        if (!defined('ENV')) {
            $f = "$root/env";
            if (is_file($f)) {
                $c = file_get_contents($f);
                $a = trim($c);
                define('ENV', strtoupper($a));
            } else {
                define('ENV', 'PRD');
            }
        }
        $f = "$root/$name.".ENV.".ini";
        if (is_file($f)) {
            return parse_ini_file($f);
        }
        $f = "$root/$name.ini";
        if (!is_file($f)) {
            die("no config file");
        }
        return parse_ini_file($f);
    }

}