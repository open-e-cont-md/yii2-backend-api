<?php

namespace openecontmd\backend_api\models;

use Yii;

/**
 * This is the model class for table "ut_content".
 *
 * @property integer $contentID
 * @property string $ParentID
 * @property string $alias
 * @property string $Header
 * @property string $Body_ru
 * @property string $Body_ro
 * @property string $Body_en
 * @property string $ImageURL
 * @property string $Title
 * @property string $Keywords
 * @property string $Descr
 * @property integer $isPublic
 * @property integer $OrderIndex
 * @property string $Special_ru
 * @property string $Special_ro
 * @property string $Special_en
 * @property string $Special_Type
 * @property integer $noIndex
 * @property integer $noFollow
 * @property string $Special_Pattern
 * @property string $MenuHeader
 * @property string $public_date
 * @property string $last_update
 * @property string $sitemap_frequency
 */
class Content extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ut_content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'Header', 'Body_ru', 'Body_ro', 'Body_en', 'Title', 'Keywords', 'Descr', 'Special_ru', 'Special_ro', 'Special_en', 'MenuHeader', 'public_date', 'last_update', 'sitemap_frequency'], 'string'],
            [['isPublic', 'OrderIndex', 'noIndex', 'noFollow'], 'integer'],
            [['ParentID', 'ImageURL'], 'string', 'max' => 255],
            [['Special_Type', 'Special_Pattern'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contentID' => 'Content ID',
            'ParentID' => 'Parent ID',
            'alias' => 'Alias',
            'Header' => 'Header',
            'Body_ru' => 'Body Ru',
            'Body_ro' => 'Body Ro',
            'Body_en' => 'Body En',
            'ImageURL' => 'Image Url',
            'Title' => 'Title',
            'Keywords' => 'Keywords',
            'Descr' => 'Descr',
            'isPublic' => 'Is Public',
            'OrderIndex' => 'Order Index',
            'Special_ru' => 'Special Ru',
            'Special_ro' => 'Special Ro',
            'Special_en' => 'Special En',
            'Special_Type' => 'Special  Type',
            'noIndex' => 'No Index',
            'noFollow' => 'No Follow',
            'Special_Pattern' => 'Special  Pattern',
            'MenuHeader' => 'Menu Header',
            'public_date' => 'Public Date',
            'last_update' => 'Last Update',
            'sitemap_frequency' => 'Sitemap Frequency'
        ];
    }
/*
    public function getStructure()
    {
        return $this->hasOne(SysStructure::class, ['StructureID' => 'ParentID']);
    }
*/
    public static function groupFooter()
    {
        $query = "SELECT alias, Header FROM ut_footer_header WHERE (isPublic = 1) AND (menu_alias = 'footer') ORDER BY OrderIndex";
        $ret = Yii::$app->db->createCommand($query)->queryAll();
        return $ret;
    }
    public static function menuByGroup()
    {
        $query = "SELECT alias, MenuHeader, footer FROM ut_content WHERE (isPublic = 1) AND (footer != '') ORDER BY OrderIndex";
        $ret = Yii::$app->db->createCommand($query)->queryAll();
        return $ret;
    }

    public static function menuByBranch($alias)
    {
        $query = "SELECT Header, alias FROM ut_footer_header WHERE (isPublic = 1) AND (menu_alias = '{$alias}') ORDER BY OrderIndex";
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
        return $res;
    }

    public static function branchByAlias($alias, $lang)
    {
        $query = "SELECT footer FROM ut_content WHERE (business_alias != '') AND (json_get(alias, '{$lang}') = '{$alias}')";
        $res = Yii::$app->db->createCommand($query)->queryOne();    //  \PDO::FETCH_CLASS
        return $res;
    }

    public static function aliasesByAlias($alias = 'home', $lang = 'ro')
    {
        $alias = ($alias != '') ? $alias : 'home';
        $query = "SELECT alias FROM ut_content WHERE (json_get(alias, '{$lang}') = '{$alias}')";
        $res = Yii::$app->db->createCommand($query)->queryOne();    //  \PDO::FETCH_CLASS
        if ($res) return $res; else return ['alias'=>['ro'=> '','ru'=> '','en'=> '']];
    }

    public static function menuByParent($parents = [], $menu_show = 0)
    {
        $in = "'xxx'"; foreach ($parents as $v) $in .= ",'$v'";
        $query = "SELECT alias, MenuHeader, noFollow, noIndex, ParentID, footer FROM ut_content
            WHERE (isPublic = 1) ".($menu_show > 0 ? 'AND (menu_show = 1)' : '')." AND (ParentID IN ($in))
            ORDER BY OrderIndex";
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
        return $res;
    }
    public static function menuByBranch2($branch, $menu_show = 0, $business = [])
    {
        $p = [];
        foreach ($business as $k => $v) $p[$k] = "'".$v."'";
        $list = implode(',', $p);

        $query = "SELECT ut_content.alias, MenuHeader, noFollow, noIndex, ut_content.ParentID, ut_content.footer, ut_content.business_alias FROM ut_content
            LEFT OUTER JOIN ut_footer_header ON (ut_content.footer = ut_footer_header.alias)
            WHERE (ut_content.isPublic = 1) ".($menu_show > 0 ? 'AND (menu_show = 1)' : '')." AND (ut_footer_header.menu_alias = '$branch')
            AND (ut_content.business_alias = '' OR ut_content.business_alias = '@@template@@' OR ut_content.business_alias IN ($list))
            ORDER BY ut_footer_header.OrderIndex, ut_content.OrderIndex";
//        var_dump($query);exit;
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
        return $res;
    }

    public static function menuByBusiness($business = [])
    {
        $p = [];
        if (count($business) > 0) {
        foreach ($business as $k => $v) $p[$k] = "'".$v."'";
        $list = implode(',', $p);
        }
        else
        {
            $list = "''";
        }
        $query = "SELECT business_alias, pattern_alias, header, doc_url, use_pattern FROM ut_business_doc
            WHERE (business_alias IN ($list)) AND (is_active = 1) ORDER BY OrderIndex";
//var_dump($query);exit;
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
        return $res;
    }
    public static function patternByAlias($alias = '@@@')
    {
        $p = [];
        foreach ($business as $k => $v) $p[$k] = "'".$v."'";
        $list = implode(',', $p);
        $query = "SELECT business_alias, pattern_alias, header, doc_url FROM ut_business_doc
            WHERE (pattern_alis = '$alias') AND (business_alias = '@@template@@') AND (is_active = 1) ORDER BY OrderIndex";
        //        var_dump($query);exit;
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
        return $res;
    }

    public static function menuFooter($parents = [])
    {
        $p = [];
        foreach ($parents as $k => $v) $p[$k] = "'".$v."'";
        $list = implode(',', $p);
        $query = "
            SELECT
            ut_content.alias,
            ut_content.MenuHeader,
            ut_content.noFollow,
            ut_content.noIndex,
            ut_content.ParentID,
            ut_content.site_branch
            FROM
            ut_content
            INNER JOIN sys_structure ON (ut_content.ParentID = sys_structure.StructureID)
            WHERE (ut_content.ParentID IN ($list))
            AND (ut_content.isPublic = 1)
            ORDER BY
            sys_structure.OrderIndex,
            ut_content.OrderIndex
            ";
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
        $items = [];
        if(count($parents) > 1){
            foreach($res as $item){
                $items[$item['ParentID']][] = $item;
            }
        }else{
            $items = $res;
        }
        return $items;
    }

    public static function getTextWidget($alias, $lang = 'en')
    {
        $query = "SELECT body_{$lang} AS body FROM ut_text_widget WHERE (alias = '$alias')";
        $ret = Yii::$app->db->createCommand($query)->queryOne();
        return $ret['body'];
    }


    public static function getBranchByAlias($alias, $suffix = '/', $ln = null)
    {
        $lang = (!$ln) ? Yii::$app->language : $ln;
        $query = "SELECT json_get(alias, '$lang') AS alias FROM ut_site_branch WHERE sys_alias = '$alias'";
        $ret = Yii::$app->db->createCommand($query)->queryOne();
        return ($ret['alias'] != '') ? $ret['alias'].$suffix : '';
    }

    public static function getAliasesByAlias($alias, $suffix = '')
    {
        $lang = Yii::$app->language;
        $query = "SELECT
            json_get(alias, 'ru') AS alias_ru,
            json_get(alias, 'ro') AS alias_ro,
            json_get(alias, 'en') AS alias_en
            FROM ut_content
            WHERE json_get(alias, 'en') = '$alias'";
        $ret = Yii::$app->db->createCommand($query)->queryOne();
//        return ($ret['alias_ro'] != '') ? $ret['alias'].$suffix : '';
        return $ret['alias_'.$lang];
    }
    public static function getCaptionByAlias($alias, $suffix = '')
    {
        $lang = Yii::$app->language;
        $query = "SELECT
            caption
            FROM ut_site_branch
            WHERE sys_alias = '$alias'";
        $ret = Yii::$app->db->createCommand($query)->queryOne();
        //        return ($ret['alias_ro'] != '') ? $ret['alias'].$suffix : '';
        return $ret['caption'];
    }

    public static function getBranchesByAlias($alias)
    {
        $lang = Yii::$app->language;
        $query = "SELECT alias FROM ut_site_branch WHERE sys_alias = '$alias'";
        $ret = Yii::$app->db->createCommand($query)->queryOne();
        return json_decode($ret['alias']);
    }
    public static function getBranchesByAliasLang($alias)
    {
        $lang = Yii::$app->language;
        $query = "SELECT alias FROM ut_site_branch WHERE json_get(alias, '$lang') = '$alias'";
        $ret = Yii::$app->db->createCommand($query)->queryOne();
        return json_decode($ret['alias']);
    }

    public static function getContentPage($alias, $language = '', $business = '')
    {
        $lang = $language != '' ? $language : Yii::$app->language;

        $query = "SELECT json_get(ut_content.alias, '@@lang@@') AS alias,
              ut_content.alias AS aliases,
              ut_content.pattern,
              json_get(ut_content.Header, '@@lang@@') AS header,
              json_get(ut_content.MenuHeader, '@@lang@@') AS MenuHeader,
              ut_content.Special_@@lang@@ AS special_content,
/*              ut_content.Special_Type AS special_type,*/
/*              ut_special_type.open_tag,*/
/*              ut_special_type.close_tag,*/
              ut_content.Body_@@lang@@ AS body,
              ut_content.ImageURL AS image,
              ut_content.public_date,
              ut_content.last_update,
              json_get(ut_content.Title, '@@lang@@') AS seo_title,
              json_get(ut_content.Descr, '@@lang@@') AS seo_description,
              json_get(ut_content.Keywords, '@@lang@@') AS seo_keywords,
              ut_content.noIndex AS no_index,
              ut_content.OrderIndex AS order_index,
        	  json_get(ut_content.ImageAlt, '@@lang@@') AS image_alt,
        	  json_get(ut_content.ImageTitle, '@@lang@@') AS image_title,
        	  ut_content.ImageAltShow AS image_alt_show,
        	  ut_content.ImageTitleShow AS image_title_show,
        	  ut_content.noFollow AS no_follow,
        	  ut_content.noIndex AS no_index,
              ut_content.footer,
              ut_content.credentials,
              ut_content.isPublic,
              ut_content.menu_show,
              ut_content.business_alias
/*              ut_content.site_branch*/
        	FROM
        	ut_content
/*              LEFT OUTER JOIN ut_special_pattern ON (ut_special_pattern.alias = ut_content.Special_Pattern)*/
/*              LEFT OUTER JOIN ut_special_type ON (ut_content.Special_Type = ut_special_type.alias)*/
            WHERE
/*              ut_content.isPublic = 1            AND*/
            (ut_content.business_alias = '' OR ut_content.business_alias = '@@template@@' OR ut_content.business_alias = '".addslashes($business)."') AND
            ut_content.alias LIKE '%@@aliases@@%'
            AND
            json_get(ut_content.alias, '@@lang@@') = '@@aliases@@'
            ORDER BY ut_content.business_alias DESC, ut_content.OrderIndex";

        $query = str_replace(['@@lang@@', '@@aliases@@'], [$lang, $alias], $query);
//var_dump($query); exit;
        return Yii::$app->db->createCommand($query)->queryOne();
    }

    public static function getBusinessPage($alias, $language = '', $business = '')
    {
        $lang = $language != '' ? $language : Yii::$app->language;

        $query = "SELECT *,
            pattern_alias AS alias,
            json_get(header, '{$lang}') AS header,
            header AS MenuHeader,
            body_{$language} AS body,


            '' AS image,
            '' AS image_alt,
            '' AS image_title,
            0 AS image_alt_show,
            0 AS image_title_show,
            is_active AS isPublic,
            '' AS last_update
        	FROM ut_business_doc
            WHERE
            (business_alias = '".addslashes($business)."') AND
            (pattern_alias LIKE '%@@aliases@@%')
            /*AND json_get(alias, '@@lang@@') = '@@aliases@@'*/";

        $query = str_replace(['@@lang@@', '@@aliases@@'], [$lang, $alias], $query);
//var_dump($query); exit;
        return Yii::$app->db->createCommand($query)->queryOne();
    }

    public static function checkBusinessPageP($alias, $language = '', $business = '')
    {
        $lang = $language != '' ? $language : Yii::$app->language;

        $query = "SELECT pattern_alias
        	FROM ut_business_doc
            WHERE
            (business_alias = '@@template@@') AND
            (pattern_alias LIKE '%@@aliases@@%')";

        $query = str_replace(['@@lang@@', '@@aliases@@'], [$lang, $alias], $query);
//var_dump($query); exit;
        return Yii::$app->db->createCommand($query)->queryOne();
    }

    public static function getBusinessPageP($alias, $language = '', $business = '')
    {
        $lang = $language != '' ? $language : Yii::$app->language;

        $query = "SELECT *,
            pattern_alias AS alias,
            json_get(header, '{$lang}') AS header,
            header AS MenuHeader,
            body_{$language} AS body,
            use_pattern,

            '' AS image,
            '' AS image_alt,
            '' AS image_title,
            0 AS image_alt_show,
            0 AS image_title_show,
            is_active AS isPublic,
            '' AS last_update
        	FROM ut_business_doc
            WHERE
            (business_alias = '@@template@@') AND
            (pattern_alias LIKE '%@@aliases@@%')
            /*AND json_get(alias, '@@lang@@') = '@@aliases@@'*/";

        $query = str_replace(['@@lang@@', '@@aliases@@'], [$lang, $alias], $query);
//var_dump($query); exit;
        return Yii::$app->db->createCommand($query)->queryOne();
    }

    public static function getPatternsSource($language = '')
    {
        $lang = $language != '' ? $language : Yii::$app->language;
        $query = "SELECT alias, json_get(caption, '{$lang}') AS caption, pattern_prefix, pattern_suffix, is_highlight FROM ut_text_pattern ORDER BY OrderIndex";
        return Yii::$app->db->createCommand($query)->queryAll();
    }

    public static function getPatternsMenu($language = '', $active = false)
    {
        $lang = $language != '' ? $language : Yii::$app->language;
        $query = "SELECT pattern_alias, json_get(header, '{$lang}') AS caption FROM ut_business_doc WHERE (business_alias = '@@template@@')";
        If ($active) $query .= " AND (is_active = 1)";
        return Yii::$app->db->createCommand($query)->queryAll();
    }
    public static function getDocList($business, $language = '', $active = false)
    {
        $lang = $language != '' ? $language : Yii::$app->language;
        $query = "SELECT pattern_alias, json_get(header, '{$lang}') AS caption FROM ut_business_doc WHERE (business_alias = '$business')";
        If ($active) $query .= " AND (is_active = 1)";
        return Yii::$app->db->createCommand($query)->queryAll();
    }


    public static function getProducts($alias, $lang = 'ru')
    {
        $query = "SELECT
  json_get(ut_product.caption, '$lang') AS product_caption,
  json_get(ut_scope.caption, '$lang') AS scope_caption,
  ut_scope.alias AS scope_alias,
  json_get(ut_tariff.caption, '$lang') AS tariff_caption,
  ut_tariff.amount,
  ut_tariff.currency,
  ut_user_product.tariff_alias,
  ut_user_product.product_status,
  ut_scope.icon AS scope_icon,
  ut_scope.icon_bold AS scope_icon_bold,
  ut_product.icon AS product_icon,
  ut_product.icon_bold AS product_icon_bold,
  ut_user_product.product_alias
FROM
  ut_product
  RIGHT OUTER JOIN ut_user_product ON (ut_product.alias = ut_user_product.product_alias)
  LEFT OUTER JOIN ut_tariff ON (ut_user_product.tariff_alias = ut_tariff.alias)
  LEFT OUTER JOIN ut_scope ON (ut_product.scope_alias = ut_scope.alias)
  WHERE (ut_user_product.user_alias = '$alias')
    AND (ut_scope.is_active = 1)
    AND (ut_product.is_active = 1)
/*    AND (ut_tariff.is_active = 1)*/
  ORDER BY ut_scope.sort_order, ut_product.sort_order";
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
//var_dump($query);
        return $res;
    }

    public static function getAllProducts2($email, $lang = 'ru')
    {
        $query = "SELECT productID,
  json_get(ut_product.caption, '$lang') AS product_caption,
  json_get(ut_scope.caption, '$lang') AS scope_caption,
  ut_scope.alias AS scope_alias,
  json_get(ut_tariff.caption, '$lang') AS tariff_caption,
  ut_tariff.amount,
  ut_tariff.currency,
  ut_user_product.tariff_alias,
  ut_user_product.product_status,
  ut_scope.icon AS scope_icon,
  ut_scope.icon_bold AS scope_icon_bold,
  ut_product.icon AS product_icon,
  ut_product.icon_bold AS product_icon_bold,
  ut_user_product.product_alias,
  ut_user_product.user_productID,
  ut_user_product.profile_json
FROM
  ut_product
  RIGHT OUTER JOIN ut_user_product ON (ut_product.alias = ut_user_product.product_alias)
  LEFT OUTER JOIN ut_tariff ON (ut_user_product.tariff_alias = ut_tariff.alias)
  LEFT OUTER JOIN ut_scope ON (ut_product.scope_alias = ut_scope.alias)
  WHERE
  (ut_scope.is_active = 1) AND (ut_product.is_active = 1) AND (ut_tariff.is_active = 1)
/*  AND (ut_user_product.product_status = 'pending') */
  AND (ut_user_product.user_alias = '{$email}')

  ORDER BY ut_scope.sort_order, ut_product.sort_order";
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
//var_dump($query); exit;
        return $res;
    }

    public static function getAllProducts($alias, $lang = 'ru')
    {
        $query = "SELECT
  json_get(ut_product.caption, '$lang') AS product_caption,
  json_get(ut_scope.caption, '$lang') AS scope_caption,
  ut_scope.alias AS scope_alias,
  json_get(ut_tariff.caption, '$lang') AS tariff_caption,
  ut_tariff.amount,
  ut_tariff.currency,
  ut_user_product.tariff_alias,
  ut_user_product.product_status,
  ut_scope.icon AS scope_icon,
  ut_scope.icon_bold AS scope_icon_bold,
  ut_product.icon AS product_icon,
  ut_product.icon_bold AS product_icon_bold,
  ut_user_product.product_alias,
  ut_user_product.is_active,
/*  json_get(ut_user_product.caption, '$lang') AS caption,*/
  ut_product.is_accompanying,
  user_productID AS product_id,
date_request,
date_start,
date_stop
FROM
  ut_product
  RIGHT OUTER JOIN ut_user_product ON (ut_product.alias = ut_user_product.product_alias)
  LEFT OUTER JOIN ut_tariff ON (ut_user_product.tariff_alias = ut_tariff.alias)
  LEFT OUTER JOIN ut_scope ON (ut_product.scope_alias = ut_scope.alias)
  WHERE (ut_user_product.user_alias = '$alias') /*AND (ut_scope.is_active = 1) AND (ut_tariff.is_active = 1)*/
  ORDER BY ut_scope.sort_order, ut_product.is_accompanying, ut_product.sort_order";
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
//var_dump($query);exit;
        return $res;
    }

    public static function getProductsByBusiness($client_id, $business, $lang = 'ru')
    {
        $query = "SELECT DISTINCT
  json_get(ut_product.caption, '$lang') AS product_caption,
  json_get(ut_scope.caption, '$lang') AS scope_caption,
  ut_scope.alias AS scope_alias,
  json_get(ut_tariff.caption, '$lang') AS tariff_caption,
  ut_tariff.amount,
  ut_tariff.currency,
  ut_user_product.tariff_alias,
  ut_user_product.product_status,
  ut_scope.icon AS scope_icon,
  ut_scope.icon_bold AS scope_icon_bold,
  ut_product.icon AS product_icon,
  ut_product.icon_bold AS product_icon_bold,
  ut_user_product.product_alias
FROM
  ut_product
  RIGHT OUTER JOIN ut_user_product ON (ut_product.alias = ut_user_product.product_alias)
  LEFT OUTER JOIN ut_tariff ON (ut_user_product.tariff_alias = ut_tariff.alias)
  LEFT OUTER JOIN ut_scope ON (ut_product.scope_alias = ut_scope.alias)
  WHERE (ut_user_product.client_id = '$client_id') AND (ut_scope.is_active = 1) AND (ut_tariff.is_active = 1)
/*  AND (ut_user_product.business_alias = '$business')*/
  AND (ut_user_product.product_status IN ('active','demo','suspended','pending'))
  ORDER BY ut_scope.sort_order, ut_product.sort_order";
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
//var_dump($query);
        return $res;
    }

    public static function getUserProduct($client_id, $business, $product_alias, $lang = 'ru')
    {
        $query = "SELECT
  json_get(ut_product.caption, '$lang') AS product_caption,
  json_get(ut_scope.caption, '$lang') AS scope_caption,
  ut_scope.alias AS scope_alias,
  json_get(ut_tariff.caption, '$lang') AS tariff_caption,
  ut_tariff.amount,
  ut_tariff.currency,
  ut_user_product.tariff_alias,
  ut_user_product.product_status,
  ut_scope.icon AS scope_icon,
  ut_scope.icon_bold AS scope_icon_bold,
  ut_product.icon AS product_icon,
  ut_product.icon_bold AS product_icon_bold,
  ut_user_product.product_alias,
  ut_user_product.profile_json
FROM
  ut_product
  RIGHT OUTER JOIN ut_user_product ON (ut_product.alias = ut_user_product.product_alias)
  LEFT OUTER JOIN ut_tariff ON (ut_user_product.tariff_alias = ut_tariff.alias)
  LEFT OUTER JOIN ut_scope ON (ut_product.scope_alias = ut_scope.alias)
  WHERE (ut_user_product.client_id = '$client_id')
/*  AND (ut_user_product.business_alias = '$business')*/
  AND (ut_scope.is_active = 1) AND (ut_tariff.is_active = 1)
  AND (ut_user_product.product_alias = '$product_alias')
  ORDER BY ut_scope.sort_order, ut_product.sort_order";
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
//var_dump($query);
        return $res;
    }

    public static function getProductByClientID($client_id, $lang = 'ru')
    {
        $query = "
    SELECT ut_user_product.user_productID, ut_user_product.product_alias
    FROM ut_user_product
    WHERE (client_id = '$client_id')
";
//var_dump($query);exit;
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
        return $res;
    }
    public static function getProductsByClientID($client_id, $lang = 'ru')
    {
        $query = "SELECT * FROM ut_user_product WHERE (client_id = '$client_id')";
        //var_dump($query);exit;
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
        return $res;
    }


    public static function getProductByID($client_id, $business_alias, $product_alias, $lang = 'ru')
    {
        $query = "
    SELECT ut_user_product.user_productID, ut_user_product.product_alias
    FROM ut_user_product
    WHERE
        (client_id = '$client_id') AND
/*        (business_alias = '$business_alias') AND*/
        (product_alias = '$product_alias')
";
//var_dump($query);exit;
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
        return $res;
    }

    public static function getClientOptions($client_id, $lang = 'ru')
    {
//var_dump($client_alias, $business_alias, $product_alias, $lang);exit;
        $pr = self::getProductByClientID($client_id);
//var_dump($pr);exit;
        $query = "SELECT *, json_get(ut_integration.caption, '$lang') AS option_caption, ut_api_keys.remark
            FROM ut_user_option
            INNER JOIN ut_integration ON (ut_user_option.integration_alias = ut_integration.integration_alias)
            INNER JOIN ut_api_keys ON (ut_user_option.integration_alias = ut_api_keys.integration_alias) AND (ut_api_keys.product_id = '".$pr[0]['user_productID']."')
            WHERE (user_product_id = '".$pr[0]['user_productID']."')
            ORDER BY sort_order, caption";
//var_dump($query); exit;
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS

        return $res;
    }

    public static function getUserOptions($client_id, $business_alias, $product_alias, $lang = 'ru', $integration_alias = '')
    {
        //var_dump($client_alias, $business_alias, $product_alias, $lang);exit;
        $pr = self::getProductByID($client_id, $business_alias, $product_alias);
        $where = ($integration_alias != '') ? " AND (ut_api_keys.integration_alias = '$integration_alias') " : "";

        $query = "SELECT
            ut_api_keys.is_active,
            ut_api_keys.for_money,
            'facturare'       AS product_alias,
            ut_integration.caption AS caption,
            ut_api_keys.outs,
            ut_integration.in_flag,
            ut_integration.out_flag,
            ut_api_keys.remark,
            ImageURL AS image,
            icon
            FROM ut_api_keys
            INNER JOIN ut_integration ON (ut_api_keys.integration_alias = ut_integration.integration_alias)
            WHERE (ut_api_keys.product_id = '".$pr[0]['user_productID']."')
            $where
            ORDER BY ut_integration.sort_order, ut_integration.caption";

//var_dump($query); exit;

        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS

        return $res;
    }

    public static function getAllIntegrationsByTariff($tariff_alias, $lang = 'ru')
    {
//        $query = "SELECT * FROM ut_integration INNER JOIN ut_user_option ON (ut_user_option.integration_alias = ut_integration.integration_alias)
//            WHERE (ut_user_option.is_active = 1) ORDER BY sort_order";
        $query = "SELECT DISTINCT *, '' AS Body_ru, '' AS Body_ro, '' AS Body_en
            FROM ut_integration
            WHERE FIND_IN_SET('$tariff_alias', tariff_list )
            ORDER BY sort_order";
/* INNER JOIN ut_user_option ON (ut_user_option.integration_alias = ut_integration.integration_alias)
INNER JOIN ut_api_keys ON (ut_api_keys.integration_alias = ut_integration.integration_alias)
AND (ut_user_option.is_active = 1)
*/
//var_dump($query); exit;
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
        return $res;
    }

    public static function getAllIntegrationsId($user_product_id, $tariff_alias, $lang = 'ru')
    {
        //        $query = "SELECT * FROM ut_integration INNER JOIN ut_user_option ON (ut_user_option.integration_alias = ut_integration.integration_alias)
        //            WHERE (ut_user_option.is_active = 1) ORDER BY sort_order";
        $query = "SELECT DISTINCT
        *, '' AS Body_ru, '' AS Body_ro, '' AS Body_en
        FROM ut_integration
        INNER JOIN ut_api_keys ON (ut_api_keys.integration_alias = ut_integration.integration_alias)
            WHERE
/*(ut_api_keys.is_active = 1) AND*/
            FIND_IN_SET('$tariff_alias', tariff_list )
            AND product_id = '$user_product_id'
            ORDER BY sort_order";
//var_dump($query); exit;
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
        return $res;
    }

    public static function getAllIntegrationsIdByBusiness($user_product_id, $business_id, $tariff_alias, $lang = 'ru')
    {
        //        $query = "SELECT * FROM ut_integration INNER JOIN ut_user_option ON (ut_user_option.integration_alias = ut_integration.integration_alias)
        //            WHERE (ut_user_option.is_active = 1) ORDER BY sort_order";
        $query = "SELECT DISTINCT
        *, '' AS Body_ru, '' AS Body_ro, '' AS Body_en
        FROM ut_integration
        INNER JOIN ut_api_keys ON (ut_api_keys.integration_alias = ut_integration.integration_alias)
            WHERE
/*(ut_api_keys.is_active = 1) AND*/
            FIND_IN_SET('$tariff_alias', tariff_list )
            AND product_id = '$user_product_id'
            AND business_id = '$business_id'
            ORDER BY in_flag DESC, out_flag, for_money, sort_order";
        //var_dump($query); exit;
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
        return $res;
    }

    public static function getUserIntegrations($user_product_id, $lang = 'ru')
    {
        $query = "SELECT DISTINCT * FROM ut_integration INNER JOIN ut_user_option ON (ut_user_option.integration_alias = ut_integration.integration_alias)
            WHERE (ut_user_option.is_active = 1) AND user_product_id = '$user_product_id' ORDER BY sort_order";
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
//var_dump($query);exit;
        return $res;
    }

    public static function getIntegrationCaptionByAlias($alias, $ln = null)
    {
        $lang = (!$ln) ? Yii::$app->language : $ln;
        $query = "SELECT json_get(caption, '$lang') AS caption FROM ut_integration WHERE integration_alias = '$alias'";
        $res = Yii::$app->db->createCommand($query)->queryOne();    //  \PDO::FETCH_CLASS
        //var_dump($query, $res); exit;
        return $res['caption'];
    }

    public static function getUserOptionByID($client_id, $business_alias, $product_alias, $option_alias, $lang = 'ru')
    {
//var_dump($client_alias, $business_alias, $product_alias, $option_alias, $lang); exit;
        $pr = self::getProductByID($client_id, $business_alias, $product_alias);
//        var_dump($client_alias, $business_alias, $product_alias, $pr);exit;
/*
        $query = "SELECT *,
        '$client_alias' AS client_alias, '$business_alias' AS business_alias, '$product_alias' AS product_alias, '{$pr[0]['user_productID']}' AS product_id
        FROM ut_user_option WHERE (user_product_id = '{$pr[0]['user_productID']}') AND (user_optionID = '$option_id')";
*/
/*
        $query = "SELECT
        ut_user_option.user_optionID,
        ut_integration.caption,
        ut_integration.Body_ru,
        ut_integration.Body_ro,
        ut_integration.Body_en,
        ut_integration.settings_json,
        ut_api_keys.outs,
        ut_user_option.is_active,
        ut_user_option.for_money,
        ut_integration.in_flag,
        ut_integration.out_flag,
        ut_api_keys.remark,
        ut_integration.integration_alias,
        ImageURL AS image,
        icon

        FROM ut_user_option
        INNER JOIN ut_integration ON (ut_user_option.integration_alias = ut_integration.integration_alias)
        INNER JOIN ut_api_keys ON (ut_user_option.integration_alias = ut_api_keys.integration_alias) AND (ut_api_keys.product_id = '{$pr[0]['user_productID']}')

        WHERE (user_product_id = '{$pr[0]['user_productID']}') AND (user_optionID = '$option_id')";
*/

        $query = "SELECT
        0 AS user_optionID,
        ut_integration.caption,
        ut_integration.Body_ru,
        ut_integration.Body_ro,
        ut_integration.Body_en,
        ut_integration.settings_json,
        ut_api_keys.outs,
        ut_api_keys.is_active,
        ut_api_keys.for_money,
        ut_integration.in_flag,
        ut_integration.out_flag,
        ut_api_keys.remark,
        ut_integration.integration_alias,
        ImageURL AS image,
        icon,
        help_alias
        FROM ut_integration
        INNER JOIN ut_api_keys ON (ut_integration.integration_alias = ut_api_keys.integration_alias) AND (ut_api_keys.product_id = '{$pr[0]['user_productID']}')
        WHERE (ut_api_keys.product_id = '{$pr[0]['user_productID']}')
        AND (ut_api_keys.business_id = '{$business_alias}')
        AND ( ut_integration.integration_alias = '{$option_alias}')";

        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
//var_dump($query); exit;
        return $res;
    }

    public static function getUserOuts($client_ids, $business_token, $option_id, $list_outs, $lang = 'ro', $xceptions = '')
    {
//var_dump($client_ids, $option_id, $list_outs, $xceptions); exit;
        $lo = explode(',', $list_outs);

        if ($list_outs) {
            $li = "'',";
            foreach($lo as $vo) {
                $li .= "'".$vo."'";
            }
        } else $li = '';
//var_dump($list_outs, $li); exit;

        $where1  = ($li != '') ? " AND (ut_api_keys.integration_alias IN ($li) ) " : "";
        $where1 .= ($xceptions != '') ? " AND (ut_api_keys.integration_alias NOT IN ($xceptions) ) " : "";
        $ret = [];
/*
        $p = []; $l = explode(',', $list_outs);
        if (count($l) > 0) {
            foreach ($l as $k => $v) $p[$k] = "'".$v."'";
            $list = implode(',', $p);
        }
        else
        {
            $list = "''";
        }
*/
        $query = "SELECT profile_json FROM ut_user_product WHERE client_id = '{$client_ids['cid']}'";
//echo "<pre>"; var_dump($client_ids, $query); echo "</pre>"; exit;
        $res = Yii::$app->db->createCommand($query)->queryOne();
        $jd = json_decode($res['profile_json']); $op = [];
//        echo "<pre>"; var_dump($jd->appointed); echo "</pre>";  exit;
        foreach ($jd->options as $kj => $vj) {
            if ($vj->out == 1) array_push($op, "'".$kj."'");
        }
        $list = implode(',', $op);
        $where2 = ($list != '') ? "AND (ut_api_keys.integration_alias IN (".$list."))" : "";

//        echo "<pre>"; var_dump($op, $list); echo "</pre>"; // exit;

        $query = "SELECT
       ut_integration.in_flag,
       ut_integration.out_flag,
       ut_api_keys.is_active,
    ut_api_keys.for_money,
    ut_api_keys.client_id,
    ut_api_keys.business_id,
    ut_api_keys.product_id,
    ut_api_keys.option_id,
    ut_api_keys.integration_alias,
    json_get(ut_integration.caption, '$lang') AS caption,
    IFNULL(ut_api_keys.outs, '') AS outs
    FROM
    ut_api_keys
    INNER JOIN ut_integration ON (ut_api_keys.integration_alias = ut_integration.integration_alias)
    WHERE (client_id = '{$client_ids['client_id']}') AND (business_id = '{$client_ids['business_id']}')
    $where1 $where2
    ORDER BY ut_integration.sort_order, caption
";
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
        foreach ($res as $k => $v) $ret[$v['integration_alias']] = $v['caption'];
//echo "<pre>"; var_dump($query, $res); exit;
        return $ret;
    }

    public static function setUserOuts($context_ids, $list_outs, $is_active, $option)
    {
//        $query = "UPDATE ut_user_option SET is_active = '$is_active' WHERE (user_optionID = '$option_id')";
//        echo "<pre>"; var_dump($query); echo "</pre>"; //exit;
//        $r = Yii::$app->db->createCommand($query)->execute();

        $query = "UPDATE ut_api_keys SET is_active = '$is_active', outs = '$list_outs'
        WHERE (client_id = '{$context_ids['client_id']}') AND (business_id = '{$context_ids['business_id']}') AND (integration_alias = '{$option}')";
//echo "<pre>"; var_dump($query); echo "</pre>"; exit;
        $r = Yii::$app->db->createCommand($query)->execute();
        return $r;
    }


    public static function getUserPatterns($client_id = '', $key)
    {
//        if ($client_alias != '')
//            $query = "SELECT * FROM ut_invoice_pattern WHERE (invoice_patternID = '$key') AND (client_alias = '$client_alias')";
//        else
        $query = "SELECT * FROM ut_invoice_pattern WHERE (invoice_patternID = '$key') AND ( (client_id = '$client_id') OR (client_id = 0) )";
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
//var_dump($query);
        return $res;
    }
    public static function getUserPatternsHash($client_id = '', $hash)
    {
        //        if ($client_alias != '')
            //            $query = "SELECT * FROM ut_invoice_pattern WHERE (invoice_patternID = '$key') AND (client_alias = '$client_alias')";
            //        else
            $query = "SELECT * FROM ut_invoice_pattern WHERE (inner_hash = '$hash') AND ( (client_id = '$client_id') OR (client_id = 0) )";
            $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
            //var_dump($query);
            return $res;
    }

    public static function getScopes($client_id, $lang = 'ru')
    {
        $query = "SELECT DISTINCT
  json_get(ut_scope.caption, '$lang') AS scope_caption,
  ut_scope.alias AS scope_alias,
  ut_scope.icon AS scope_icon,
  ut_scope.icon_bold AS scope_icon_bold
FROM
  ut_product
  RIGHT OUTER JOIN ut_user_product ON (ut_product.alias = ut_user_product.product_alias)
  LEFT OUTER JOIN ut_scope ON (ut_product.scope_alias = ut_scope.alias)
  WHERE (ut_user_product.client_id = '$client_id') AND (ut_scope.is_active = 1)
  ORDER BY ut_scope.sort_order";
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
//        echo "<pre>"; var_dump($query, $res); echo "</pre>"; exit;
        return $res;
    }

    public static function getAllScopes($lang = 'ru')
    {
        $query = "SELECT DISTINCT
  json_get(ut_scope.caption, '$lang') AS scope_caption,
  ut_scope.alias AS scope_alias,
  ut_scope.icon AS scope_icon,
  ut_scope.icon_bold AS scope_icon_bold
FROM
  ut_scope
  WHERE
  (ut_scope.is_active = 1)
  ORDER BY ut_scope.sort_order";
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS

        return $res;
    }


    public static function getScopesByBusiness($client_id, $business, $lang = 'ru')
    {
        $query = "SELECT DISTINCT
  json_get(ut_scope.caption, '$lang') AS scope_caption,
  ut_scope.alias AS scope_alias,
  ut_scope.icon AS scope_icon,
  ut_scope.icon_bold AS scope_icon_bold
FROM
  ut_product
  RIGHT OUTER JOIN ut_user_product ON (ut_product.alias = ut_user_product.product_alias)
  LEFT OUTER JOIN ut_scope ON (ut_product.scope_alias = ut_scope.alias)
  WHERE (ut_user_product.client_id = '$client_id') AND (ut_scope.is_active = 1)
/*  AND (ut_user_product.business_alias = '$business')*/
  AND (ut_user_product.product_status IN ('active','demo','suspended','pending'))
  ORDER BY ut_scope.sort_order";
//var_dump($query); exit;
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS

        return $res;
    }

    public static function getActualTariff($type, $lang = 'ro')
    {
        $query = "SELECT tariffID AS tariff_is, type, alias, json_get(caption, '$lang') AS tariff_caption, amount, currency
            FROM ut_tariff WHERE (type = '$type') AND (is_active = 1) ORDER BY OrderIndex";
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
//var_dump($query);
        return $res;
    }

    public static function getTariff($type, $frequency)
    {
        $query = "SELECT alias, caption, amount, currency, label, is_highlight FROM ut_tariff WHERE (type = '$type')
            ORDER BY OrderIndex";
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
        //var_dump($query);
        return $res;
    }

    public static function getTariffRecord($alias, $type = '', $lang = 'ru', $status = 'active')
    {
        $query = "SELECT
            ut_tariff_record.tariff_alias,
            ut_tariff_record.tariff_status,
            ut_tariff_record.tariff_type,
            ut_tariff_record.tariff_pattern,
            ut_tariff_record.tariff_value,
            json_get(ut_tariff_pattern.caption, '$lang') AS caption,
            ut_tariff_pattern.positive,
            ut_tariff_pattern.negative,
            ut_tariff_pattern.remark_id,
            ut_tariff_pattern.remark,
            ut_tariff_pattern.icon,
            ut_tariff_record.tariff_limit,
            json_get(ut_tariff_record.sub_tariff_caption, '$lang') AS sub_tariff_caption,
            json_get(ut_tariff_record.sub_tariff_value, '$lang') AS sub_tariff_value,
            ut_tariff_record.sub_scale,
            ut_tariff_record.sub_value,
            ut_tariff_pattern.is_show
            FROM ut_tariff_pattern
            RIGHT OUTER JOIN ut_tariff_record ON (ut_tariff_pattern.alias = ut_tariff_record.tariff_pattern)
            WHERE (ut_tariff_record.tariff_alias = '$alias') AND (ut_tariff_record.tariff_type = '$type') AND
            (ut_tariff_record.tariff_status != 'negative') AND (is_active = 1) AND (ut_tariff_record.tariff_status = '$status')
            ORDER BY OrderIndex";
//var_dump($query);
        $res = Yii::$app->db->createCommand($query)->queryAll();    //  \PDO::FETCH_CLASS
        return $res;
    }

    public static function updateUserProductSettings($alias, $product_alias, $settings)
    {
        $query = "UPDATE ut_user_product SET settings_json = '$settings' WHERE (user_alias = '$alias') AND (product_alias = '$product_alias')";
        $res = Yii::$app->db->createCommand($query)->execute();    //  \PDO::FETCH_CLASS
        //var_dump($query);
        return $res;
    }

    public static function updateUserOptionSettings($alias, $product_id, $settings, $option, $post = null)
    {
        $set = '';
        if ($post) {
            (strval($post['is_active']) != '') && $set .= ", is_active = '".$post['is_active']."'";
            if (is_array($post['outs']) && (count($post['outs']) > 0) )
            {
                $o = '';
                foreach ($post['outs'] as $k => $v) {
                    if ($v ==1) { if ($o != '') $o = $o.','; $o .= $k; }
                }
                $set .= ", outs = '".$o."'";
            }
        }
//        $query = "UPDATE ut_user_product SET settings_json = '$settings' WHERE (user_alias = '$alias') AND (product_alias = '$product_alias')";
        $query = "UPDATE ut_user_option SET settings_json = '$settings' {$set} WHERE (user_optionID = '$option') AND (user_product_id = '$product_id')";
//var_dump($query); exit;
        $res = Yii::$app->db->createCommand($query)->execute();    //  \PDO::FETCH_CLASS
        //var_dump($query);
        return $res;
    }

    public static function checkKeys($integration_alias, $key1 = null, $key2 = null, $key3 = null)
    {
        $where = "";
        switch ($integration_alias)
        {
            default:
            case 'api': break;
            case 'bitrix24': $where .= " AND (test_token_code = '$key1') "; $where .= " AND (test_token_access = '$key2') "; break;
            case '1c_buh': $where .= " AND (key_1 = '$key1') "; $where .= " AND (key_2 = '$key2') "; break;
        }
        $query = "SELECT * FROM ut_api_keys WHERE (integration_alias = '$integration_alias') $where";
        $res = Yii::$app->db->createCommand($query)->queryOne();   //  \PDO::FETCH_CLASS
        return $res;
    }

    public static function checkIntegrationsByID($client_id, $business_id, $in_flag = 'Y', $out_flag = 'Y', $lang = 'ro')
    {
        $where = '';
        if ($in_flag != '-')  { if ($in_flag == 'Y')  $where .= ' AND (ut_integration.in_flag = 1) ';  else $where .= ' AND (in_flag = 0) '; }
        if ($out_flag != '-') { if ($out_flag == 'Y') $where .= ' AND (ut_integration.out_flag = 1) '; else $where .= ' AND (out_flag = 0) '; }

        $query = "SELECT DISTINCT *, '' AS Body_ru, '' AS Body_ro, '' AS Body_en,
        json_get(ut_integration.caption, '{$lang}') AS caption
        FROM ut_api_keys
        INNER JOIN ut_integration ON (ut_api_keys.integration_alias = ut_integration.integration_alias)
        WHERE (client_id = '{$client_id}') AND (business_id = '{$business_id}') {$where}
        ORDER BY ut_integration.sort_order";   //  \PDO::FETCH_CLASS

        $res = Yii::$app->db->createCommand($query)->queryAll();   //  \PDO::FETCH_CLASS
        $ret = [];
        foreach ($res as $k => $v) $ret[$v['integration_alias']] = $v;
//echo "<pre>"; var_dump($query, $res, $ret); exit;
        return $ret;
    }
    public static function checkIntegrationsByAlias($client_id, $business_alias)
    {
        $res_c = Yii::$app->db->createCommand("SELECT clientID FROM ut_client WHERE (client_id = '{$client_id}')")->queryOne();
        $res_b = Yii::$app->db->createCommand("SELECT businessID FROM ut_business WHERE (client_id = '{$client_id}') AND (alias = '{$business_alias}')")->queryOne();
        $res = Yii::$app->db->createCommand("SELECT * FROM ut_api_keys WHERE (client_id = '{$res_c['clientID']}') AND (business_id = '{$res_b['businessID']}')")->queryAll();   //  \PDO::FETCH_CLASS
        $ret = [];
        foreach ($res as $k => $v) $ret[$v['integration_alias']] = $v;
        return $ret;
    }

    public static function getKeys($context_ids, $option)
    {
        $query = "SELECT * FROM ut_api_keys WHERE
            (client_id = '{$context_ids['client_id']}') AND
            (business_id = '{$context_ids['business_id']}') AND
            (product_id = '{$context_ids['product_id']}') AND
            (option_id = '{$context_ids['option_id']}') AND
            (integration_alias = '{$option}')";
//var_dump($query); exit;
        $res = Yii::$app->db->createCommand($query)->queryOne();   //  \PDO::FETCH_CLASS
        return $res;
    }

    public static function updateKeys($context_ids, $key_set, $option)
    {
        $settings = " last_request_timestamp = '".time()."'";
        foreach ($key_set as $k => $v) $settings .= ",\n\t {$k} = '{$v}'";
        $query = "UPDATE ut_api_keys SET
            $settings
            WHERE
            (client_id = '{$context_ids['client_id']}') AND
            (business_id = '{$context_ids['business_id']}') AND
            (product_id = '{$context_ids['product_id']}') AND
            (option_id = '{$context_ids['option_id']}') AND
            (integration_alias = '{$option}')";
        $res = Yii::$app->db->createCommand($query)->execute();
//var_dump($query); exit;
        return $res;
    }

    public static function updateTokens($key_id, $key_set)
    {
        $set = '';
        foreach ($key_set as $k => $v)
        {
            if ($set != '') $set .= ', ';
            $set .= "$k = '$v'";
        }
        $query = "UPDATE ut_api_keys SET {$set} WHERE (api_keysID = '$key_id')";
//echo $query; exit;
        $res = Yii::$app->db->createCommand($query)->execute();    //  \PDO::FETCH_CLASS
    }

    public static function updateCode($integration, $key, $hash, $code)
    {
        $settings = " last_request_timestamp = '".time()."'";
        $settings .= ",\n\t token_code = '{$code}'";
        $query = "UPDATE ut_api_keys SET
            $settings
            WHERE
            (integration_alias = '{$integration}') AND
            (hash = '{$hash}') AND
            (option_id = '{$key}')";

            /**
            $log = "\n------------------------\n";
            $log .= date("Y.m.d G:i:s")."\n";
            //	    $log .= "Post: ".print_r(Yii::$app->request->post(), 1)."\n";
            //	    $log .= "Get: ".print_r(Yii::$app->request->get(), 1)."\n";
            $log .= "query: ".print_r($query, 1)."\n";
            $log .= "------------------------\n";
            file_put_contents(__DIR__."/code_response.log", $log, FILE_APPEND);
            /**/


            $res = Yii::$app->db->createCommand($query)->execute();
            //var_dump($query); exit;
            return $res;
    }

    public static function getUserContextIDs($client_id, $business_token, $product_alias, $option_id = 0)
    {
        $return = [];
        $query = "SELECT clientID FROM ut_client WHERE (client_id = '{$client_id}')";
        $res_c = Yii::$app->db->createCommand($query)->queryOne();
//echo "<pre>"; var_dump($query, $res_c); echo "</pre>"; exit;
        $return['client_id'] = $res_c['clientID'];
        $return['cid'] = $client_id;
        $query = "SELECT businessID FROM ut_business WHERE (client_id = '{$client_id}') AND (business_token = '{$business_token}')";
//echo "<pre>"; var_dump($query); echo "</pre>"; exit;
        $res_b = Yii::$app->db->createCommand($query)->queryOne();
        $return['business_id'] = $res_b['businessID'];
        $return['business_token'] = $business_token;
        $query = "SELECT user_productID FROM ut_user_product WHERE (client_id = '{$client_id}') AND (product_alias = '{$product_alias}')";
        $res_p = Yii::$app->db->createCommand($query)->queryOne();
        $return['product_id'] = $res_p['user_productID'];
        $return['product_alias'] = $product_alias;

        //$query = "SELECT integration_alias FROM ut_user_option WHERE (user_product_id = '{$return['product_id']}') AND (user_optionID = '{$option_id}')";
        //$res_o = Yii::$app->db->createCommand($query)->queryOne();    //  \PDO::FETCH_CLASS
        $return['option_id'] = 0; // $option_id;
        $return['option_alias'] = ''; //$res_o['integration_alias'];
        return $return;
    }

    public static function getBusiness($business_token)
    {
        $query = "SELECT * FROM ut_business WHERE (business_token = '{$business_token}')";
        //var_dump($query); exit;
        $res = Yii::$app->db->createCommand($query)->queryOne();   //  \PDO::FETCH_CLASS
        return $res;
    }
    public static function getStatuses($lang, $menu = false)
    {
        $where = $menu ? " WHERE (stat_show = 1) " : "";
        $query = "SELECT alias, json_get(caption, '{$lang}') AS status, json_get(caption_multy, '{$lang}') AS status_short, color
            FROM ut_status $where ORDER BY sort_order";
        //var_dump($query); exit;
        $res = Yii::$app->db->createCommand($query)->queryAll();   //  \PDO::FETCH_CLASS
        return $res;
    }

    public static function getBranch($branch, $pi = null)
    {
        //$branch = $this->context->action->controller->module->controller->module->requestedRoute;
        $pi = Yii::$app->request->pathInfo;
        $pa = explode('/', $pi);
        $context = Customer::getContext();
//var_dump('===', $pa); echo '<pre>'; var_dump($branch, $pa); echo '<pre><br clear="all">'; //exit;
//var_dump($branch, $pa); echo '<br clear="all">'; //exit;

        if ( ($context['user']->first_name != '') || ($context['user']->last_name != '') ) {
            $name = trim($context['user']->first_name.' '.$context['user']->last_name);
        }
        else {
            $name = trim($context['user']->email);
        }

        if ( ($pa[0] == 'dashboard') || ($pa[0] == '') )
        {
            $branch_header = Terms::translate($branch, 'branch').((isset($context['client']->caption)) ? ' - '.$context['client']->caption : '');
        }
//        elseif ( ($pa[0] == 'incoming/list') )
//        {
//            $branch_header = Terms::translate($branch, 'branch').((isset($context['client']->caption)) ? ' - '.$context['client']->caption : '');
//        }
        else if ($branch == 'integration/productlist')
        {
            $branch_header = Terms::translate($branch, 'branch').((isset($context['client']->caption)) ? ' - '.$context['client']->caption : '');
        }
        else if ($pa[0] == 'department')
        {
            if ($pa[1] == 'new')
            {
                $branch = 'department/new';
                $branch_header = Terms::translate($branch, 'branch');
            }
            else
            {
                $b = self::getBusiness($pa[1]);
                $branch = 'department/*';
                $branch_header = Terms::translate($branch, 'branch').' - '.json_decode($b['caption'])->{Yii::$app->language};
            }
        }

        else if ($pa[0] == 'products')
        {
            if ( !isset($pa[1]) )
            {
                $branch = 'integration/productselect';
                $branch_header = Terms::translate($branch, 'branch');
            }
            if ( isset($pa[1]) && ($pa[1] == 'select') ) {
                $branch = 'integration/productselect';
                $branch_header = Terms::translate($branch, 'branch').' - e-Cont.md';
            }
        }

        else if ($pa[0] == 'integration')
        {
            if (isset($pa[3]) && $pa[3] == 'edit')
            {
                $branch = 'integration/edit';
                $branch_header = Terms::translate($branch, 'branch').' - '. self::getIntegrationCaptionByAlias($pa[2], );
            }
            else if (!isset($pa[2]))
            {
                $branch = 'integration/list';
                $branch_header = Terms::translate($branch, 'branch'); //.' - '.$pa[1];
            }
            else
            {
                $b = self::getBusiness($pa[1]);
                $branch = 'integration/list';
                $branch_header = Terms::translate($branch, 'branch').' - '.json_decode($b['caption'])->{Yii::$app->language};
            }

            //    var_dump($pa[4]);
            //    var_dump($branch, $branch_header);exit;
        }

        else if ($pa[0] == 'pattern')
        {
            if (isset($pa[1]) && $pa[1] == 'edit') $branch = 'pattern_edit/*';
            else if (isset($pa[1]) && $pa[1] == 'copy') $branch = 'pattern_copy/*';
            else if (isset($pa[1]) && $pa[1] == 'view') $branch = 'pattern_view/*';
            else if (isset($pa[1]) && $pa[1] == 'new') $branch = 'pattern_new/*';
            else $branch = 'pattern_list/*';

            $branch_header = Terms::translate($branch, 'branch'); //.' - '.$pa[1];

            //var_dump($pa); //exit;
            //var_dump($branch, $branch_header);exit;
        }
        else if ( ($pa[0] == 'invoice') && ($pa[1] == 'incoming') )
        {
            $branch = 'incoming/list';
            $branch_header = Terms::translate($branch, 'branch').((isset($context['client']->caption)) ? ' - '.$context['client']->caption : '');
        }
        else if ( ($pa[0] == 'invoice') && ($pa[1] == 'outgoing') )
        {
//var_dump($branch, $pa); exit;
//            $b = self::getBusiness($pa[2]); $b = json_decode($b['caption'])->{Yii::$app->language};
//var_dump($b['caption']); exit;

//var_dump($b); exit;

            if ($branch == 'invoice/list')
            {
                $b = self::getBusiness($pa[2]); $b = json_decode($b['caption'])->{Yii::$app->language};
                $branch_header = Terms::translate($branch, 'branch').' - '.$b;
            }
            else if ($pa[2] == 'view')
            {
                $b = self::getBusiness($pa[3]); $b = json_decode($b['caption'])->{Yii::$app->language};
                $branch_header = Terms::translate($branch, 'branch').' - '.$b;
            }
            else if ($pa[2] == 'edit')
            {
                $b = self::getBusiness($pa[3]); $b = json_decode($b['caption'])->{Yii::$app->language};
                $branch_header = Terms::translate($branch, 'branch').' - '.$b;
            }
            else if ($pa[3] == 'new')
            {
                $branch = 'invoice/new';
                $b = self::getBusiness($pa[2]); $b = json_decode($b['caption'])->{Yii::$app->language};
                $branch_header = Terms::translate($branch, 'branch').' - '.$b;
            }
            else if ($pa[2] == 'copy')
            {
                $branch = 'invoice/copy';
                $b = self::getBusiness($pa[3]); $b = json_decode($b['caption'])->{Yii::$app->language};
                $branch_header = Terms::translate($branch, 'branch').' - '.$b;
            }



            else if ( ($pa[1] != 'edit') && ($pa[1] != 'view') && !isset($pa[2]) )
            {
//                $b = self::getBusiness($pa[2]);
                $branch = 'invoice_list/*';
                if ($b)
                {
                    $branch_header = Terms::translate($branch, 'branch').' - '.Terms::translate('business', 'cabinet').': '.json_decode($b['caption'])->{Yii::$app->language};
                }
                else
                {
                    $branch_header = Terms::translate($branch, 'branch').' - '.Terms::translate('business', 'cabinet').': '.json_decode($context['business'][0]['caption'])->{Yii::$app->language};
                }
            }
            else if ($pa[1] == 'edit')
            {
                $b = self::getBusiness($pa[2]);
                $branch = 'invoice_edit/*';
                $branch_header = Terms::translate($branch, 'branch').' - '.Terms::translate('business', 'cabinet').': '.json_decode($b['caption'])->{Yii::$app->language};
            }
            else if ($pa[1] == 'copy')
            {
//                echo "<pre>"; var_dump($pi, $pa); exit;
                $b = self::getBusiness($pa[2]);
                $branch = 'invoice_copy/*';
                $branch_header = Terms::translate($branch, 'branch').' - '.Terms::translate('business', 'cabinet').': '.json_decode($b['caption'])->{Yii::$app->language};
            }
            else if ($pa[1] == 'view')
            {
                $b = self::getBusiness($pa[2]);
                $branch = 'invoice_view/*';
                $branch_header = Terms::translate($branch, 'branch').' - '.Terms::translate('business', 'cabinet').': '.json_decode($b['caption'])->{Yii::$app->language};
            }
            else if ($pa[1] == 'sign')
            {
                $b = self::getBusiness($pa[2]);
                $branch = 'invoice_view/*';
                $branch_header = Terms::translate($branch, 'branch').' - '.Terms::translate('business', 'cabinet').': '.json_decode($b['caption'])->{Yii::$app->language};
            }
            else if ( isset($pa[2]) && ($pa[2] == 'new') )
            {
                //        var_dump($pa); exit;
                $b = self::getBusiness($pa[1]);
                $branch = 'invoice_new/*';
                $branch_header = Terms::translate($branch, 'branch').' - '.Terms::translate('business', 'cabinet').': '.json_decode($b['caption'])->{Yii::$app->language};
            }
            else
            {
                //echo "<pre>"; var_dump($branch, $pa); exit;
                $branch_header = Terms::translate($branch, 'branch').' - '.$b;
            }
        }
        else if ($pa[0] == 'service')
        {
            //    var_dump($pa); //exit;
            if ( ($pa[1] != 'edit') && ($pa[1] != 'view') && !isset($pa[2]) )
            {
                $b = self::getBusiness($pa[1]);
                $branch = 'integration/*';
                if ($b)
                {
                    $branch_header = Terms::translate($branch, 'branch').' - '.json_decode($b['caption'])->{Yii::$app->language};
                }
                else
                {
                    $branch_header = Terms::translate($branch, 'branch').' - '.json_decode($context['business'][0]['caption'])->{Yii::$app->language};
                }
            }
            else if ($pa[1] == 'edit')
            {
                $branch = 'invoice_edit/*';
                $branch_header = Terms::translate($branch, 'branch');
            }
            else if ($pa[1] == 'view')
            {
                $branch = 'invoice_view/*';
                $branch_header = Terms::translate($branch, 'branch');
            }
            else if ( isset($pa[2]) && ($pa[2] == 'new') )
            {
                //        var_dump($pa); exit;
                $branch = 'invoice_new/*';
                $branch_header = Terms::translate($branch, 'branch');
            }
            else
            {
                $branch_header = Terms::translate($branch, 'branch').$b['caption'];
            }
        }
        else if ($branch == 'manager/edit')
        {
/*
            $name = Customer::findUserByEmail($pa[1]);
            if ( ($name[0]->first_name != '') || ($name[0]->last_name != '') ) {
                $name = trim($name[0]->first_name.' '.$name[0]->last_name);
            }
            else {
                $name = trim($name[0]->email);
            }
*/
            $branch_header = Terms::translate($branch, 'branch'); //.' - '.$name;
        }
        else if ($pa[0] == 'manager')
        {
            if ($branch == 'manager/invite') {
                $branch_header = Terms::translate($branch, 'branch');
            } else {
                if ($context['user']->is_principal)
                    $branch_header = Terms::translate($branch.'_principal', 'branch').' - '.$name;
                else
                    $branch_header = Terms::translate($branch, 'branch').' - '.$name;
            }
        }

        else if ($pa[0] == 'client')
        {
            if ( !isset($pa[1]) ) {
                $branch_header = Terms::translate('client/*', 'branch');
                $branch_header .= ((isset($context['client']->caption)) ? ' - '.$context['client']->caption : '');
            }
            else if (isset($pa[1]) && ($pa[1] == 'edit')) {
                $branch_header = Terms::translate('client_edit/*', 'branch');
                $cli = Client::findCustomer($pa[2]);
                if ($cli && $cli->caption != '') $branch_header .= ' - ' . $cli->caption;
                else if ($cli && $cli->first_name != '') $branch_header .= ' - ' . $cli->first_name . ' ' . $cli->last_name;
                //var_dump($pa[2], Client::findCustomer($pa[2])->caption);
            }
            else if (isset($pa[1]) && ($pa[1] == 'new'))  {
                $branch_header = Terms::translate('client_new/*', 'branch');
                $branch_header .= ((isset($context['client']->caption)) ? ' - '.$context['client']->caption : '');
            }
        }
        else if ($pa[0] == 'customer')
        {
            if (isset($context['user']['username']) && ($context['user']['username'] == 'ask') )
            {
                $branch = 'customer_show/*';
                $branch_header = Terms::translate($branch, 'branch');
            }
            else if (isset($context['user']['client_id']) && ($context['user']['client_id'] == 0) )
            {
                $branch = 'customer_start/*';
                $branch_header = Terms::translate($branch, 'branch');
            }
            else
            {
                $branch = 'customer_edit/*';
                $branch_header = Terms::translate($branch, 'branch').((isset($context['client']->caption)) ? ' - '.$context['client']->caption : '');
            }
        }
        else
        {
            $branch_header = Terms::translate($branch, 'branch');
        }

        return
//            "[".$branch."] ".
            $branch_header;
    }
}
