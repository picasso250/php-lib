<?php
/**
 * 看住日志，如果不再增长，就报警
 *
 * @version 1.1 add $mute_time
 * @version 1.0 watch log
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/9
 * Time: 14:45
 */

namespace lib;


class LogWatcher
{
    /**
     * @var \Redis
     */
    static $redis;

    /**
     * @var QQ_Mail
     */
    static $mail;

    static $key_prefix = 'xiaochi:log_watch';
    static $receiver = array();
    static $title = '日志停止';
    static $content = '日志停止';

    /**
     * 给用户报警后，接下来的多长时间内不再报警
     * @var int
     */
    static $mute_time = 12 * 3600;

    static function watch($file)
    {
        $ttl = 3 * 30 * 24 * 3600;

        $key_prefix = self::$key_prefix;
        $key = "$key_prefix:$file";
        $redis = self::$redis;
        $last_size = $redis->get($key);
        $mail = self::$mail;

        $size = self::get_now_size($file);
        $redis->setex($key, $ttl, $size);

        $suppress_key = "$key_prefix:$file:suppress";
        if ($last_size == $size) {
            $suppress = $redis->get($suppress_key);
            if (!$suppress) {
                $title = self::$title;
                $content = date('c').' 日志停止了 '.$key;
                $r = $mail->send(self::$receiver, $title, $content);
                $redis->setex($suppress_key, $ttl, 1);
                echo date('c'),"\tFile $file\tSend mail to ",json_encode(self::$receiver),"\t";
                if ($r) {
                    echo "OK\n";
                } else {
                    echo $mail->mail->ErrorInfo,"\n";
                }
            }
        } else {
            $redis->setex($suppress_key, self::$mute_time, 0);
        }
    }

    private static function get_now_size($file)
    {
        if (!file_exists($file)) {
            return 0;
        }
        return filesize($file);
    }

}