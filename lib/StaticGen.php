<?php
/**
 * 静态网站生成器
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/17
 * Time: 11:21
 */

namespace lib;

class StaticGen
{

    static function process_dir($src, $dst, $ignore = array())
    {
        if ($handle = opendir($src)) {
            /* This is the correct way to loop over the directory. */
            while (false !== ($entry = readdir($handle))) {
                if ($entry === '.' || $entry === '..' || strpos($entry, '_') === 0 || strpos($entry, '.') === 0 || in_array($entry, $ignore)) {
                    continue;
                }
                echo "$entry\n";

                $new_src = "$src/$entry";
                $new_dst = "$dst/$entry";
                if (is_dir($new_src)) {
                    if (!is_dir($new_dst)) {
                        mkdir($new_dst);
                    }
                    self::process_dir($new_src, $new_dst);
                } else if (preg_match('/\.html$/', $new_src)) {
                    echo "$new_src => $new_dst\n";
                    ob_start();
                    include $new_src;
                    $content = ob_get_contents();
                    ob_end_clean();
                    file_put_contents($new_dst, $content);
                }
            }
            closedir($handle);
        }
    }

}