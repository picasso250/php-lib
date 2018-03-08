<?php
/**
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/3/9
 * Time: 12:33
 */

namespace lib;

/**
 * 对 curl 的包装
 * @version 1.0 基本get 和 post json 方法
 * Class Curl
 * @package lib
 */
class Curl
{
    public $response;
    public $info;
    function get($url, $options = array())
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);

        if ($options) {
            curl_setopt_array($ch, $options);
        }
        $this->response = curl_exec($ch);
        $this->info = curl_getinfo($ch);

        curl_close($ch);
        return $this->response;
    }
    function post($url, $data, $options = array())
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        if ($options) {
            curl_setopt_array($ch, $options);
        }
        $this->response = curl_exec($ch);
        $this->info = curl_getinfo($ch);

        curl_close($ch);
        return $this->response;
    }
    function json()
    {
        return json_decode($this->response, true);
    }

}