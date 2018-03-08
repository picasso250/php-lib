<?php
/**
 * MySQL 的辅助函数
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/22
 * Time: 17:33
 */

namespace lib;

use PDO;

class MySQL_Helper
{
    public static function init_db($config)
    {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
        $db = new Pdo($dsn, $config['username'], $config['password']);
        $db->exec("set names utf8");
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $db;
    }

}