<?php
/**
 * 监听log的增长，如果没有了报警
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/8
 * Time: 19:20
 */

use lib\LogWatcher;

if (php_sapi_name()!=='cli') die("no");

ini_set('display_errors', 1);

if (!isset($argv[1])) {
    echo "Usage: $argv[0] files...\n";
    exit(1);
}

require __DIR__.'/../lib/lib.php';
autoload_dir('lib', __DIR__.'/..');
\lib\Autoload::auto_load_psr(__DIR__.'/vendor');

$config = parse_ini_file(__DIR__.'/../config/redis.ini');

$redis = new Redis();
$host = $config['host'];
$redis->connect($host);
$auth = $config['auth'];
$redis->auth($auth);

$config = parse_ini_file(__DIR__.'/../config/mail.ini');

$mail = new \lib\QQ_Mail($config);

LogWatcher::$redis = $redis;
LogWatcher::$key_prefix = 'log_watch:'.gethostname();
LogWatcher::$mail = $mail;
LogWatcher::$receiver = $config['alarm_to'];

for ($i = 1; $i < $argc; $i++) {
    $file = $argv[$i];
    LogWatcher::watch($file);
}
