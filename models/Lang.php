<?php

namespace openecontmd\backend_api\models;

use Yii;

/**
 * This is the model class for table "ut_lang".
 *
 * @property integer $langID
 * @property string $ParentID
 * @property string $CountryCode
 * @property string $MenuText
 * @property integer $isActive
 * @property integer $OrderIndex
 * @property string $FlagIcon
 */
class Lang extends \yii\db\ActiveRecord
{
    //Переменная, для хранения текущего объекта языка
    static $current = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ut_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['isActive', 'OrderIndex'], 'integer'],
            [['ParentID'], 'string', 'max' => 255],
            [['CountryCode'], 'string', 'max' => 3],
            [['MenuText'], 'string', 'max' => 20],
            [['FlagIcon'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'langID' => 'Lang ID',
            'ParentID' => 'Parent ID',
            'CountryCode' => 'Country Code',
            'MenuText' => 'Menu Text',
            'isActive' => 'Is Active',
            'OrderIndex' => 'Order Index',
            'FlagIcon' => 'Flag Icon',
        ];
    }


    public static function translate($item, $ln = null)
    {
        $lang = (!$ln) ? Yii::$app->language : $ln;
//var_dump($item, $lang, $ln); exit;
        return json_decode($item)->$lang;
    }


//Получение текущего объекта языка
    static function getCurrent()
    {
        if( self::$current === null ){
            self::$current = self::getDefaultLang();
        }
        return self::$current;
    }

//Установка текущего объекта языка и локаль пользователя
    static function setCurrent($url = null)
    {
        $language = self::getLangByUrl($url);
        self::$current = ($language === null) ? self::getDefaultLang() : $language;
        Yii::$app->language = self::$current->MenuText;
    }

//Получения объекта языка по умолчанию
    static function getDefaultLang()
    {
        return Lang::find()->where('`OrderIndex` = :default', [':default' => 1])->one();
    }

//Получения объекта языка по буквенному идентификатору
    static function getLangByUrl($url = null)
    {
        if ($url === null) {
            return null;
        } else {
            $language = Lang::find()->where('MenuText = :url', [':url' => $url])->one();
            if ( $language === null ) {
                return null;
            }else{
                return $language;
            }
        }
    }

//	Получение списка месяцев
    public static function getMonthList()
    {
    	$lang = Yii::$app->language;
    	$query = "SELECT alias AS month_alias, json_get(caption_short, '$lang') AS month_short, json_get(caption_long, '$lang') AS month_long, json_get(caption_long2, '$lang') AS month_long2, order_index FROM ut_month ORDER BY order_index";
    	$res = Yii::$app->db->createCommand($query)->queryAll(\PDO::FETCH_CLASS);
    	return $res;
    }
//	Получение месяца по алиасу
    public static function getMonthByAlias($alias)
    {
    	$lang = Yii::$app->language;
    	$query = "SELECT alias AS month_alias, json_get(caption_short, '$lang') AS month_short, json_get(caption_long, '$lang') AS month_long, json_get(caption_long2, '$lang') AS month_long2, order_index FROM ut_month WHERE (alias = '$alias')";
    	$res = Yii::$app->db->createCommand($query)->queryAll(\PDO::FETCH_CLASS);
    	return $res;
    }

//	Получение списка дней недели
    public static function getDayList()
    {
    	$lang = Yii::$app->language;
    	$query = "SELECT alias AS day_alias, json_get(caption_short, '$lang') AS day_short, json_get(caption_long, '$lang') AS day_long, order_index FROM ut_weekday ORDER BY order_index";
    	$res = Yii::$app->db->createCommand($query)->queryAll(\PDO::FETCH_CLASS);
    	return $res;
    }
//	Получение дня недели по номеру
    public static function getDayByNumber($num)
    {
    	$lang = Yii::$app->language;
    	$query = "SELECT alias AS day_alias, json_get(caption_short, '$lang') AS day_short, json_get(caption_long, '$lang') AS day_long, order_index FROM ut_weekday WHERE (order_index = '$num')";
    	$res = Yii::$app->db->createCommand($query)->queryAll(\PDO::FETCH_CLASS);
    	return $res;
    }

//	Форматирование короткой даты с годом
    public static function formatDateShort($date_str)
    {
    	$res = date('d', strtotime($date_str)) . '&nbsp;' .
			Lang::getMonthByAlias(date('M', strtotime($date_str)))[0]->month_short . '&nbsp;' .
			date('Y', strtotime($date_str));
    	return $res;
    }
//	Форматирование короткой даты без года
    public static function formatDateShort2($date_str)
    {
        $res = date('d', strtotime($date_str)) . '&nbsp;' .
            Lang::getMonthByAlias(date('M', strtotime($date_str)))[0]->month_short.'.';
            return $res;
    }

//	Форматирование длинной даты
    public static function formatDateLong($date_str)
    {
    	$res =
        	//Lang::getDayByNumber(date('w', strtotime($date_str)))[0]->day_short . ' ' .
    		date('d', strtotime($date_str)) . '&nbsp;' .
    		Lang::getMonthByAlias(date('M', strtotime($date_str)))[0]->month_short . ' ' .
    		date('Y', strtotime($date_str));
    		return $res;
    }
//	Форматирование длинной даты со склонением
    public static function formatDateLong2($date_str)
    {
    	$res =
        	//Lang::getDayByNumber(date('w', strtotime($date_str)))[0]->day_short . ' ' .
    		date('d', strtotime($date_str)) . '&nbsp;' .
    		Lang::getMonthByAlias(date('M', strtotime($date_str)))[0]->month_long2 . ' ' .
    		date('Y', strtotime($date_str));
    		return $res;
    }
//	Форматирование даты для новости
    public static function formatDateNews($date_str)
    {
    	$res = date('d', strtotime($date_str)) . '&nbsp;' .
     		Lang::getMonthByAlias(date('M', strtotime($date_str)))[0]->month_long2 . '&nbsp;' .
    		date('Y', strtotime($date_str));
    		return $res;
    }

}