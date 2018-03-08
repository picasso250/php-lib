<?php
/**
 * @version 1.0 生成map和自动加载，支持 psr-4 files classmap
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/9
 * Time: 16:31
 */

namespace lib;

class Autoload
{

    /**
     * 自动加载composer类
     * @param $root
     */
    static function auto_load_psr($root) {
        $map = array();
        $map_file = $root.'/map.json';
        if (!is_file($map_file)) die("no third_party class map file.");
        $json = json_decode(file_get_contents($map_file), true);
        foreach ($json as $root => $auto) {
            // 处理PSR-4
            if (isset($auto['psr-4'])) {
                $r = $auto['psr-4'];
                foreach ($r as $namespace_prefix => $file) {
                    $map[$namespace_prefix] = "$root/$file";
                }
            }
            // 加载所有 files
            if (isset($auto['files']) && version_compare(PHP_VERSION, '5.4.0') >= 0) {
                foreach ($auto['files'] as $file) {
                    require "$root/$file";
                }
            }
            // 加载所有 class
            if (isset($auto['classmap'])) {
                foreach ($auto['classmap'] as $file) {
                    require "$root/$file";
                }
            }
        }
        spl_autoload_register(function($cls) use ($root, $map) {
            foreach ($map as $namespace_prefix => $root) {
                if (strpos($cls, $namespace_prefix) === 0) {
                    $a = substr($cls, strlen($namespace_prefix));
                    $file = "$root/".str_replace("\\", "/", $a).'.php';
                    require $file;
                }
            }
        });
    }

    /**
     * 生成自动加载的文件
     * @param $root
     */
    static function gen_autoload_map($root)
    {
        $d = dir($root);

        $autoload = array();
        while (false !== ($entry = $d->read())) {
            $dir = "$root/$entry";
            if (!in_array($entry, array('.', '..')) && is_dir($dir)) {
                $f = $dir."/composer.json";
                if (is_file($f)) {
                    $j = json_decode(file_get_contents($f), true);
                    if ($j && isset($j['autoload'])) {
                        $autoload[$dir] = $j['autoload'];
                    }
                }
            }
        }
        $d->close();
        echo json_encode($autoload);
    }
}