<?php
/**
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/8
 * Time: 19:02
 */

namespace lib;

use Redis;

class RedisHelper
{
    /**
     * @var \Redis
     */
    static $redis;
    static $back_up_file;
    public static function deleteAllKeys($pattern, $delete = false)
    {
        $redis = self::$redis;
        $i = null;
        $c = 0;
        $f = null;
        if (self::$back_up_file) {
            $f = fopen(self::$back_up_file, 'wb');
        }
        while (1) {
            $keys = $redis->scan($i, $pattern);
            foreach ($keys as $key) {
                $type = $redis->type($key);
                // backup
                if ($f) {
                    $dump = $redis->dump($key);
                    fwrite($f, "$key\n");
                    $len = strlen($dump);
                    fwrite($f, pack("N", $len). $dump."\n");
                }
                if ($delete) {
                    echo "delete $key\n";
                    $redis->delete($key);
                } else {
                    echo "$key\t$type\n";
                }

                $c++;
            }
            if (!$i) break;
        }
        echo "Total: $c\n";
        if ($f) {
            fclose($f);
        }
    }
    public static function restoreKeys($file)
    {
        $ttl = 3 * 30 * 24 * 3600;

        $f = fopen($file, "rb");
        $redis = self::$redis;
        while (1) {
            $key = fgets($f);
            if (!$key) break;
            $key = trim($key);
            echo "Restore $key\n";
            $s = fread($f, 4);
            $a = unpack('N', $s);
            $len = $a[1];
            $val = fread($f, $len);
            fgets($f);
            $redis->restore($key, $ttl, $val);
        }
        fclose($f);
    }

}
