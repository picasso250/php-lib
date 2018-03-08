<?php
/**
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/7
 * Time: 12:49
 */

namespace lib;

class Model
{
    /**
     * @var \PDO
     */
    static $db;

    public function __construct($info, $id = null)
    {
        if ($info) {
            foreach ($info as $k => $v) {
                $this->$k = $v;
            }
        }
    }

    static function timestamp($t = null)
    {
        $format = 'Y-m-d H:i:s';
        if ($t === null) {
            $t = time();
        }
        return date($format, $t);
    }
    static function getById($id)
    {
        $table = get_called_class();
        $db = self::$db;
        $stmt = $db->prepare("SELECT * FROM `$table` where id=? limit 1");
        $stmt->execute(array($id));
        return $stmt->fetch();
    }
    static function findOne($where)
    {
        $a = self::find($where, 1);
        if ($a) {
            return $a[0];
        }
        return false;
    }
    static function find($where = array(), $n = 1000)
    {
        $table = get_called_class();
        $db = self::$db;
        $ws = " ";
        if ($where) {
            foreach ($where as $key => $value) {
                $s[] = "`$key`=:$key";
            }
            $ws = " WHERE ".implode(' AND ', $s);
        }
        $stmt = $db->prepare("SELECT * FROM `$table` $ws LIMIT $n");
        $stmt->execute($where);
        return $stmt->fetchAll();
    }
    static function insert($kvs)
    {
        $table = get_called_class();
        $db = self::$db;
        if (empty($kvs)) {
            die("no kvs to insert $table");
        }
        foreach ($kvs as $key => $value) {
            $s[] = "`$key`";
            $t[] = ":$key";
        }
        $keys = implode(',', $s);
        $vals = implode(',', $t);
        $stmt = $db->prepare("INSERT `$table` ($keys) VALUES ($vals)");
        $stmt->execute($kvs);
        return $db->lastInsertId();
    }
    static function updateById($kvs, $id)
    {
        $table = get_called_class();
        $db = self::$db;
        if (empty($kvs)) {
            die("no kvs to update $table by id($id)");
        }
        foreach ($kvs as $key => $value) {
            $s[] = "`$key`=:$key";
        }
        $kvs['id'] = $id;
        $sets = implode(',', $s);
        $stmt = $db->prepare("UPDATE `$table` set $sets where id=:id");
        $stmt->execute($kvs);
        return $db->lastInsertId();
    }
    static function update($kvs, $where = array())
    {
        if (empty($kvs)) {
            die("no kvs to update");
        }
        $table = get_called_class();
        $db = self::$db;
        $ws = " ";
        if ($where) {
            foreach ($where as $key => $value) {
                $s[] = "`$key`=:$key";
            }
            $ws = " WHERE ".implode(' AND ', $s);
        }
        $s = array();
        foreach ($kvs as $key => $value) {
            $s[] = "`$key`=:$key";
        }
        $sets = implode(',', $s);
        $sql = "UPDATE `$table` SET $sets $ws ";
        $stmt = $db->prepare($sql);
        $stmt->execute(array_merge($kvs,$where));
        return $stmt->rowCount();
    }

}
