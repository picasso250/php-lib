<?php
/**
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/7
 * Time: 16:00
 */

namespace lib;


class HtmlRender
{
    public static function val2table($val)
    {
        if (is_string($val)) echo htmlspecialchars($val);
        if (!is_array($val)) echo htmlspecialchars(var_export($val, true));
        if (!$val) return;

        // [k:v,...]
        $is_kvs = self::is_kvs($val);
        if ($is_kvs) {
            self::kvs2table($val);
        } else if (self::is_table($val)) {
            self::table2table($val);
        } else {
            // is list
            self::list2list($val);
        }
    }

    private static function is_kvs($arr)
    {
        reset($arr);
        $key = key($arr);
        $is_kvs = !is_numeric($key);
        return $is_kvs;
    }

    private static function is_table($arr)
    {
        reset($arr);
        $key = key($arr);
        return
            ($key === 0 && is_array($arr[$key]) && self::is_kvs($arr[$key]));
    }

    private static function list2list($arr)
    {
        echo "<ul class='list-list'>";
        foreach ($arr as $e) {
            echo "<li>";
            self::val2table($e);
            echo "</li>";
        }
        echo "</ul>\n";
    }

    private static function kvs2table($kvs)
    {
        echo "<table class='kvs-table'><tbody>";
        foreach ($kvs as $k => $v) {
            echo "<tr><th>".htmlspecialchars($k)."</th><td>";
            self::val2table($v);
            echo "</td></tr>";
        }
    }

    private static function table2table($table)
    {
        $keys = array_keys($table[0]);
        echo "<table class='table-table'><thead>";
        foreach ($keys as $key) {
            echo "<th>".htmlspecialchars($key)."</th>";
        }
        echo "</thead><tbody>";
        foreach ($table as $row) {
            echo "<tr>";
            foreach ($keys as $key) {
                echo "<td>".htmlspecialchars($row[$key])."</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    }

}