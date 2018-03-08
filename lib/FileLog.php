<?php
/**
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/7
 * Time: 17:26
 */

namespace lib;

/*
 *	Log
 * 将日志写在文件中
 * CI2自带的log系统，往往会使得业务逻辑的log埋没在框架的log中
 * 使用方式：
 * 在 config 中填写 mihoyo_log_file
 * 调用方式
 * $this->mihoyolog->info("%s hello", "world");
 * @author 王霄池
 * @date 2016年11月2日20:01:18
 */
class FileLog
{
    public $file = 'app.log';
    public function __construct($file)
    {
        $this->file = $file;
    }
    public function __call($name, $args)
    {
        $level = strtoupper($name);
        $dbt = debug_backtrace();
        $trace = $dbt[1];
        $this->_do_log($trace, $level, $args);
    }

    /**
     * @param $trace
     * @param $level
     * @param $args
     */
    private function _do_log($trace, $level, $args)
    {
        $line = "$level\t" . date('c') . " " . sprintf("%s:%s", basename($trace['file']), $trace['line']) . "\t";
        $line .= call_user_func_array('sprintf', $args) . "\n";
        error_log($line, 3, $this->file);
    }
}
