<?php
/**
 * @version 1.0 basic function
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/8
 * Time: 19:20
 */

if (php_sapi_name()!=='cli') die("no");

ini_set('display_errors', 1);

require __DIR__.'/../lib/lib.php';
autoload_dir('lib', __DIR__.'/..');

$root = __DIR__ . '/vendor';
if (!is_dir($root)) {
    echo "'$root' not dir\n";
    exit(1);
}
\lib\Autoload::gen_autoload_map($root);