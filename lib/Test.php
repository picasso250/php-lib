<?php
/**
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/3/9
 * Time: 14:53
 */

namespace lib;

/**
 * 基本的测试辅助类
 * @version 1.1 add assert_array_partial_equal()
 * @version 1.0 assert assert_true assert_equal assert_array_key_exists
 * Class Test
 * @package lib
 */
class Test
{

    static $total = 0;
    static $fail = 0;

    static function assert($t, $msg = null)
    {
        static::$total++;
        if ($t !== true) {
            self::$fail++;
            $backtrace = debug_backtrace();
            $b = $backtrace[0];
            $file = $b['file'];
            $f = basename($file);
            $line = $b['line'];
            $c = trim(self::get_file_line($file, $line));
            echo date('c'),"\t$f:$line\t$c","\n";
        }
    }
    static function assert_true($t, $msg = null)
    {
        static::$total++;
        if ($t !== true) {
            self::$fail++;
            $backtrace = debug_backtrace();
            $b = $backtrace[0];
            $file = $b['file'];
            $f = basename($file);
            $line = $b['line'];
            $c = trim(self::get_file_line($file, $line));
            echo date('c'),"\t$f:$line\t$c","\n";
        }
    }

    /**
     * @param $a mixed Expect
     * @param $b mixed Real value
     * @param null $msg
     */
    static function assert_equal($a, $b, $msg = null)
    {
        static::$total++;
        if ($a != $b) {
            self::$fail++;
            $backtrace = debug_backtrace();
            $bt = $backtrace[0];
            $file = $bt['file'];
            $f = basename($file);
            $line = $bt['line'];
            $c = trim(self::get_file_line($file, $line));
            $detail = '';
            if (is_scalar($a) || is_scalar($b)) {
                $detail = "\t".self::equal_detail($a, $b)."\t";
            }
            echo date('c'),"\t$f:$line\t$c$detail","\n";
        }
    }
    static function assert_array_key_exists($arr, $keys, $msg = null)
    {
        foreach ($keys as $key) {
            static::$total++;
            if (!array_key_exists($key, $arr)) {
                self::$fail++;
                $backtrace = debug_backtrace();
                $b = $backtrace[0];
                $file = $b['file'];
                $f = basename($file);
                $line = $b['line'];
                $c = trim(self::get_file_line($file, $line));
                echo date('c'),"\t$f:$line\t$c\t'$key' do not exists","\n";
            }
        }
    }

    /**
     * @param $file
     * @param $line
     * @return string 那一行的内容
     */
    private static function get_file_line($file, $line)
    {
        $a = file($file);
        if (isset($a[$line-1])) {
            return $a[$line-1];
        }
        return '';
    }

    /**
     * 获取一个变量的字符串表示形式
     *
     * @param $var
     */
    private static function get_represent($var)
    {
        if (is_scalar($var)) {
            $represent = var_export($var, true);
        } else {
            $represent = gettype($var);
        }
        return $represent;
    }

    /**
     * 校验数组是否部分相等（通过指定部分key）
     * @param $a
     * @param $b
     * @param $keys
     */
    public static function assert_array_partial_equal($a, $b, $keys)
    {
        foreach ($keys as $key) {
            static::$total++;
            if (!array_key_exists($key, $a)) {
                self::$fail++;
                $backtrace = debug_backtrace();
                $bt = $backtrace[0];
                $file = $bt['file'];
                $f = basename($file);
                $line = $bt['line'];
                $c = trim(self::get_file_line($file, $line));
                echo date('c'),"\t$f:$line\t$c\t'$key' do not exists in A","\n";
            }
            if (!array_key_exists($key, $b)) {
                self::$fail++;
                $backtrace = debug_backtrace();
                $bt = $backtrace[0];
                $file = $bt['file'];
                $f = basename($file);
                $line = $bt['line'];
                $c = trim(self::get_file_line($file, $line));
                echo date('c'),"\t$f:$line\t$c\t'$key' do not exists in B","\n";
            }
            if (array_key_exists($key, $a) && array_key_exists($key, $b) && $a[$key] != $b[$key]) {
                self::$fail++;
                $backtrace = debug_backtrace();
                $bt = $backtrace[0];
                $file = $bt['file'];
                $f = basename($file);
                $line = $bt['line'];
                $c = trim(self::get_file_line($file, $line));
                $detail = self::equal_detail($a[$key], $b[$key]);
                echo date('c'),"\t$f:$line\t$c\t$detail","\n";
            }
        }
    }

    /**
     * @param $a
     * @param $b
     * @return string
     */
    private static function equal_detail($a, $b)
    {
        $aa = self::get_represent($a);
        $bb = self::get_represent($b);
        $detail = "Expect $aa, Got $bb";
        return $detail;
    }
}