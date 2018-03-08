<?php
/**
 * 命令行文件模板
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/8
 * Time: 19:20
 */

// 防止用户访问（如果你不小心提交到服务器上）
if (php_sapi_name()!=='cli') die("no");

ini_set('display_errors', 1);

if (!isset($argv[1])) {
    echo "Usage: $argv[0] arg\n";
    exit(1);
}

$name = $argv[1];

require __DIR__.'/../lib/lib.php';
autoload_dir('lib', __DIR__.'/..');

do_it();