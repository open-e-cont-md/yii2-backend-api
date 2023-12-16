<?php

namespace openecontmd\backend_api\models;

//use Yii;
//use yii\helpers\ArrayHelper;


class Helper
{

    public static function json_decode_safe($json) {
        if ($json && !empty($json))
        {
        $r =  (object)json_decode(
            str_replace(
                ["\n", "\r", "\u", "\t", "\f", "\b", "/", '"'],
                ["\\n", "\\r", "\\u", "\\t", "\\f", "\\b", "\/", "\""],
                $json));

        if (isset($r)) return $r;
        else return (object)['ru'=>'','ro'=>'','en'=>''];

/*
        str_replace(
            ["\n", "\r", "\u", "\t", "\f", "\b", "/", '"'],
            ["\\n", "\\r", "\\u", "\\t", "\\f", "\\b", "\/", "\""],
            $json),
            JSON_INVALID_UTF8_IGNORE); // Доступно с PHP 7.2.0.
*/
        }
        else
        {
            return (object)['ru'=>'','ro'=>'','en'=>''];
        }
    }

    public static function json_decode_lang($json, $lang) {
        if (isset($lang) && isset(self::json_decode_safe($json)->{$lang}) )
            return htmlspecialchars(self::json_decode_safe($json)->{$lang});
        else
            return '';
    }



    public static function GUID()
    {
        return sprintf(
            '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(16384, 20479),
            mt_rand(32768, 49151),
            mt_rand(0, 65535),
            mt_rand(0, 65535),
            mt_rand(0, 65535)
            );
    }

}
