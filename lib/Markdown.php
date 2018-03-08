<?php

namespace lib;

/**
 * Markdown解释（自己造的）
 *
 * @version 1.0 基本的解释文件
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/3/15
 * Time: 16:06
 */
class Markdown
{
    /**
     * 解释一个文件
     * @param $file
     */
    static function echo_file($file) {
        foreach (file($file) as $line) {
            // 解释性标记忽略
            if (preg_match('/^\w+:/', $line)) continue;
            // 标题
            $line = preg_replace('/^#\s?(.+?)\s?#?$/', '<h1>$1</h1>', $line);
            // 颜色强调
            $line = preg_replace('/_([^_]+)_/', '<span class="color-notice">$1</span>', $line);
            // 粗体
            $line = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $line);
            echo nl2br($line);
        }
    }

}