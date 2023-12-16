<?php

namespace openecontmd\backend_api\models;

use Yii;

class Tariff extends \yii\db\ActiveRecord
{

    public static function getTariffObject($alias, $type = '', $status = 'pending')
    {

        //  ==============  JSON  ===========================
        $jd = (object) [];
        $jd->alias = $alias;
        $jd->status = $status;
        $jd->start_date = date("Y-m-d", time());
        $jd->stop_date = date("Y-m-d", time() + 3600 * 24 * 14);
        $jd->caption = (object) [];
        $query = "SELECT
json_get(caption, 'ru') AS caption_ru,
json_get(caption, 'ro') AS caption_ro,
json_get(caption, 'en') AS caption_en,
amount FROM ut_tariff WHERE (alias = '{$alias}')";
//echo "<pre>"; var_dump($query); echo "</pre>"; exit;
        $res = Yii::$app->db->createCommand($query)->queryOne();
        $jd->caption->ru = stripslashes($res['caption_ru']);
        $jd->caption->ro = stripslashes($res['caption_ro']);
        $jd->caption->en = stripslashes($res['caption_en']);
        $jd->currency = 'MDL';
        $jd->tariff_rate = intval($res['amount']);
        $jd->extention_rate = intval(0);
        $jd->options_rate = intval(0);
        $jd->total_rate = intval($res['amount']);
//echo "<pre>"; var_dump($jd); echo "</pre>"; exit;

        $jd->tariff = (object) [];

        $query = "SELECT
ut_tariff_record.tariff_alias,
ut_tariff_record.tariff_status,
ut_tariff_record.tariff_type,
ut_tariff_record.tariff_pattern,
ut_tariff_record.tariff_value,
json_get(ut_tariff_pattern.caption, 'ru') AS caption_ru,
json_get(ut_tariff_pattern.caption, 'ro') AS caption_ro,
json_get(ut_tariff_pattern.caption, 'en') AS caption_en,
ut_tariff_pattern.positive,
ut_tariff_pattern.negative,
ut_tariff_pattern.remark_id,
ut_tariff_pattern.remark,
ut_tariff_pattern.icon,
ut_tariff_record.tariff_limit,
json_get(ut_tariff_record.sub_tariff_caption, 'ru') AS sub_tariff_caption_ru,
json_get(ut_tariff_record.sub_tariff_caption, 'ro') AS sub_tariff_caption_ro,
json_get(ut_tariff_record.sub_tariff_caption, 'en') AS sub_tariff_caption_en,
json_get(ut_tariff_record.sub_tariff_value, 'ru') AS sub_tariff_value_ru,
json_get(ut_tariff_record.sub_tariff_value, 'ro') AS sub_tariff_value_ro,
json_get(ut_tariff_record.sub_tariff_value, 'en') AS sub_tariff_value_en,
ut_tariff_record.sub_scale,
ut_tariff_record.sub_value,
ut_tariff_pattern.is_show,
OrderIndex
FROM ut_tariff_pattern
RIGHT OUTER JOIN ut_tariff_record ON (ut_tariff_pattern.alias = ut_tariff_record.tariff_pattern)
WHERE (ut_tariff_record.tariff_alias = '$alias') AND (ut_tariff_record.tariff_type = '$type') AND
(ut_tariff_record.tariff_status != 'negative') AND (is_active = 1) ORDER BY OrderIndex";
        //var_dump($query);
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS

        foreach ($res as $k => $v) {
//            echo "<pre>"; var_dump($v); echo "</pre>"; //exit;
            $jd->tariff->{$v['tariff_pattern']} = (object) [];
            $jd->tariff->{$v['tariff_pattern']}->caption = (object) [];
            $jd->tariff->{$v['tariff_pattern']}->caption->ru = stripslashes(nl2br($v['caption_ru']));
            $jd->tariff->{$v['tariff_pattern']}->caption->ro = stripslashes(nl2br($v['caption_ro']));
            $jd->tariff->{$v['tariff_pattern']}->caption->en = stripslashes(nl2br($v['caption_en']));
            $jd->tariff->{$v['tariff_pattern']}->icon = $v['icon'];
            $jd->tariff->{$v['tariff_pattern']}->is_show = isset($v['is_show']) ? (bool)$v['is_show'] : false;
            $jd->tariff->{$v['tariff_pattern']}->sort_order = $v['OrderIndex'];
            $jd->tariff->{$v['tariff_pattern']}->limit = isset($v['tariff_limit']) ? (int)$v['tariff_limit'] : 0;
            $jd->tariff->{$v['tariff_pattern']}->tariff_value = (object) [];
            $jd->tariff->{$v['tariff_pattern']}->tariff_value->ru = stripslashes(nl2br($v['sub_tariff_value_ru']));
            $jd->tariff->{$v['tariff_pattern']}->tariff_value->ro = stripslashes(nl2br($v['sub_tariff_value_ro']));
            $jd->tariff->{$v['tariff_pattern']}->tariff_value->en = stripslashes(nl2br($v['sub_tariff_value_en']));
            $jd->tariff->{$v['tariff_pattern']}->extention = (object) [];
            $jd->tariff->{$v['tariff_pattern']}->extention->tariff_caption = (object) [];
            $jd->tariff->{$v['tariff_pattern']}->extention->tariff_caption->ru = stripslashes(nl2br($v['sub_tariff_caption_ru']));
            $jd->tariff->{$v['tariff_pattern']}->extention->tariff_caption->ro = stripslashes(nl2br($v['sub_tariff_caption_ro']));
            $jd->tariff->{$v['tariff_pattern']}->extention->tariff_caption->en = stripslashes(nl2br($v['sub_tariff_caption_en']));
            $jd->tariff->{$v['tariff_pattern']}->extention->tariff_value = (object) [];
            $jd->tariff->{$v['tariff_pattern']}->extention->tariff_value->ru = stripslashes(nl2br($v['sub_tariff_value_ru']));
            $jd->tariff->{$v['tariff_pattern']}->extention->tariff_value->ro = stripslashes(nl2br($v['sub_tariff_value_ro']));
            $jd->tariff->{$v['tariff_pattern']}->extention->tariff_value->en = stripslashes(nl2br($v['sub_tariff_value_en']));
            $jd->tariff->{$v['tariff_pattern']}->extention->scale = $v['sub_scale'];
            $jd->tariff->{$v['tariff_pattern']}->extention->value = $v['sub_value'];
        }
//        echo "<pre>"; print_r($jd, false); echo "</pre>"; exit;
        return $jd;
    }

    public static function getAllIntegrationsByTariff($tariff_alias)
    {
        //        $query = "SELECT * FROM ut_integration INNER JOIN ut_user_option ON (ut_user_option.integration_alias = ut_integration.integration_alias)
        //            WHERE (ut_user_option.is_active = 1) ORDER BY sort_order";
        $query = "SELECT DISTINCT integration_alias,
amount_monthly, ImageURL, icon, in_flag, out_flag, sort_order,
json_get(caption, 'ru') AS caption_ru,
json_get(caption, 'ro') AS caption_ro,
json_get(caption, 'en') AS caption_en
FROM ut_integration WHERE FIND_IN_SET('$tariff_alias', tariff_list) ORDER BY sort_order";
//var_dump($query); exit;
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS

        //  ==============  JSON  ===========================
        $jd = (object) [];
        foreach ($res as $k => $v) {
            $jd->{$v['integration_alias']} = (object) [];
            $jd->{$v['integration_alias']}->caption = (object) [];
            $jd->{$v['integration_alias']}->caption->ru = nl2br($v['caption_ru']);
            $jd->{$v['integration_alias']}->caption->ro = nl2br($v['caption_ro']);
            $jd->{$v['integration_alias']}->caption->en = nl2br($v['caption_en']);
            $jd->{$v['integration_alias']}->image = $v['ImageURL'];
            $jd->{$v['integration_alias']}->icon = $v['icon'];
            $jd->{$v['integration_alias']}->sort_order = $v['sort_order'];
            $jd->{$v['integration_alias']}->in = $v['in_flag'];
            $jd->{$v['integration_alias']}->out = $v['out_flag'];
            $jd->{$v['integration_alias']}->value = isset($v['amount_monthly']) ? (int)$v['amount_monthly'] : 0;
        }
        return $jd;
    }

    public static function getProductByAlias($client_id, $lang = 'ru')
    {
        $query = "SELECT
  ut_user_product.user_productID,
  ut_user_product.product_status,
  ut_user_product.tariff_alias,
  ut_user_product.date_request,
  ut_user_product.date_start,
  ut_user_product.date_stop,
  ut_user_product.profile_json,
  json_get(ut_tariff.caption, '$lang') AS caption
FROM
  ut_tariff
  RIGHT OUTER JOIN ut_user_product ON (ut_tariff.alias = ut_user_product.tariff_alias)
WHERE client_id = '$client_id'
ORDER BY date_start DESC, user_productID DESC";
//var_dump($query);exit;
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
        return $res;
    }

}
