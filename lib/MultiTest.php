<?php
/**
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/3/9
 * Time: 12:06
 */

namespace lib;

/**
 * 并行测试类
 *
 * @version 1.1 before 回调 在每此脚本运行前都执行一次
 * @version 1.0 基本的 run 以及 before和 after钩子
 * Class MultiTest
 * @package lib
 */
class MultiTest
{
    public $task_list = array();
    /**
     * 开始的回调
     * @var Callable
     */
    public $before = null;
    /**
     * 结束的回调
     * @var Callable
     */
    public $after = null;
    public function run()
    {
        $pid_list = array();
        foreach ($this->task_list as $e) {
            list($script, $num) = $e;
            if (!is_file($script)) {
                echo "no file '$script'\n";
                exit(1);
            }
            while ($num > 0) {
                $pid = pcntl_fork();
                if ($pid < 0) {
                    echo "fork failed\n";
                    exit(1);
                }
                if ($pid > 0) {
                    // parent
                    $num--;
                    $pid_list[]= $pid;
                    continue;
                }
                // child
                if ($this->before) {
                    $before = $this->before;
                    $before();
                }
                include $script;
                exit();
            }
        }
        // wait all
        foreach ($pid_list as $pid) {
            pcntl_waitpid($pid, $status);
            if ($status != 0) {
                echo "something wrong\n";
            }
        }
        if ($this->after) {
            $after = $this->after;
            $after();
        }
    }
}