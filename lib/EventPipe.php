<?php
/**
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/4
 * Time: 16:39
 */

namespace lib;

class EventPipe
{

    private $name;

    /**
     * @var \Redis
     */
    static $redis;

    public function __construct($name)
    {
        $this->name = "event:".$name;
    }

    public function push($type, $id)
    {
        $info = json_encode(compact('type', 'id'));
        return self::$redis->rPush($this->name, $info);
    }

    public function pop()
    {
        $a = self::$redis->lPop($this->name);
        $o = json_decode($a, true);
        return $o;
    }
}