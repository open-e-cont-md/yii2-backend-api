<?php

namespace openecontmd\backend_api\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ut_lang".
 *
 * @property integer $ut_langID
 * @property string $ParentID
 * @property string $LangID
 * @property string $LangText
 * @property integer $OrderIndex
 * @property string $Alphabet
 * @property integer $CountryID
 * @property integer $isActive
 * @property integer $MenuText
 */
class SysLang extends \yii\db\ActiveRecord
{
    public function behaviors( ) {
        return [
            [
                'class' => 'sjaakp\sortable\Sortable',
                'orderAttribute' => 'OrderIndex'
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['OrderIndex', 'CountryID', 'isActive'], 'integer'],
            [['ParentID', 'LangID', 'LangText', 'Alphabet', 'MenuText'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ut_langID' => Yii::t('apl', 'ut_langID'),
            'ParentID' => Yii::t('apl', 'ParentID'),
            'LangID' => Yii::t('apl', 'LangID'),
            'LangText' => Yii::t('apl', 'LangText'),
            'OrderIndex' => Yii::t('apl', 'OrderIndex'),
            'Alphabet' => Yii::t('apl', 'Alphabet'),
            'CountryID' => Yii::t('apl', 'CountryID'),
            'isActive' => Yii::t('apl', 'isActive'),
            'MenuText' => Yii::t('apl', 'MenuText'),
        ];
    }


    public static function getLanguageNames(){
        $langs = self::find()->where(['isActive' => 1])->orderBy('OrderIndex')->asArray()->all();
        return array_keys(ArrayHelper::map($langs, 'MenuText', 'MenuText'));
    }

    public static function activeLangs()
    {
        $langs = self::find()->where(['isActive' => 1])->all();
        return ArrayHelper::map($langs, 'MenuText', 'MenuText');
    }

    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['ut_countryID' => 'CountryID']);
    }
}
