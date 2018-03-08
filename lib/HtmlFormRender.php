<?php
/**
 * Created by PhpStorm.
 * User: xiaochi.wang
 * Date: 2017/2/16
 * Time: 10:39
 */

namespace lib;


class HtmlFormRender
{
    public static function render($form_info, $tpl = "%s")
    {
        foreach ($form_info as $name => $form_row) {
            if (!isset($form_row['type'])) {
                $type = 'text';
            } else {
                $type = $form_row['type'];
            }
            unset($form_row['type']);

            if (isset($form_row['id'])) {
                $id = $form_row['id'];
            } else {
                $form_row['id'] = $id = 'id_of_'.$type.'_'. $name;
            }
            if (isset($form_row['title'])) {
                $title = $form_row['title'];
            } else {
                $title = $name;
            }
            if (!isset($form_row['placeholder'])) {
//                $form_row['placeholder'] = $title;
            }

            if (isset($form_row['list'])) {
                $list = $form_row['list'];
                $form_row['list'] = $datalist_id  = 'datalist_id_of'.$name;
            }

            $s = '';
            foreach ($form_row as $key => $value) {
                if ($value === true) {
                    $s .= " $key ";
                } elseif ($value === false) {
                } elseif (is_scalar($value)) {
                    $s .= " $key=\"".htmlentities($value)."\" ";
                }
            }

            switch ($type) {
                case "text":
                case "email":
                case "number":
                    $s = "<input type='$type' name='$name' $s />";
                    break;
                case "textarea":
                    $s = "<textarea name='$name' $s ></textarea>";
            }
            $s = "<label for=\"$id\">".htmlspecialchars($title)."</label>$s";

            echo sprintf($tpl, $s);

            if (isset($form_row['list'])) {
                echo "<datalist id=\"$datalist_id\">";
                foreach ($list as $e) {
                    echo "<option>".htmlspecialchars($e)."</option>";
                }
                echo "</datalist>";
            }
        }
    }

}