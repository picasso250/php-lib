<?php
/**
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/8
 * Time: 19:20
 */

if (php_sapi_name()!=='cli') die("no");

ini_set('display_errors', 1);

use lib\MultiTest;

require __DIR__.'/../lib/lib.php';
autoload_dir('lib', __DIR__.'/..');

$t = new MultiTest();
$t->task_list = array(
    array(__DIR__.'/a.php', 5),
    array(__DIR__.'/b.php', 6),
);
$t->run();