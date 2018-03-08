<?php
/**
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/6
 * Time: 10:00
 */

/**
 * 自动加载 命名空间下的类
 * @version 1.1 修复 linux 下不能加载加载
 * @version 1.0 自动加载
 */
function autoload_dir($namespace_root, $dir)
{
    $r = "$namespace_root\\";
    spl_autoload_register(function($c) use ($r,$dir) {
        if (strpos($c, $r) === 0) {
            $f = $dir.'/'.str_replace("\\", "/", $c).".php";
            if (file_exists($f)) {
                require $f;
            }
        }
    });
}
