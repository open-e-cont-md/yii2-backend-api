<?php

namespace openecontmd\backend_api\models;

use Yii;
//use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ut_terms".
 *
 * @property integer $termsID
 * @property string $ParentID
 * @property string $Domain
 * @property string $Alias
 * @property string $Body
 */
class Terms extends \yii\db\ActiveRecord
{
    static function getDb() {
        return Yii::$app->db;
    }
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'ut_terms';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['Body'], 'string'],
			[['ParentID', 'Domain', 'Alias'], 'string', 'max' => 255],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'termsID' => 'Terms ID',
			'ParentID' => 'Parent ID',
			'Domain' => 'Domain',
			'Alias' => 'Alias',
			'Body' => 'Body',
		];
	}

	public static function translate($alias, $domain = 'econt', $ln = null)
	{
	    $terms = Terms::find()->select(['Body', 'Alias'])->where(['Domain' => $domain, 'Alias' => $alias])->indexBy('Alias')->one();
//var_dump($alias, $domain, $ln, $terms);//exit;
	    return isset($terms['Body']) && !empty($terms['Body']) ? Lang::translate($terms['Body'], $ln) : '';

/**  Временно отключено!  04.06.2019
		if (!Yii::$app->session->has('translations')) {
			$terms = Terms::find()->select(['Body', 'Alias'])->where(['Domain' => $domain])->all();
			Yii::$app->session->set('translations', ArrayHelper::map($terms, 'Alias', 'Body'));
		}

		$translations = Yii::$app->session->get('translations');
		if (!empty($translations[$alias])) {
			return Lang::translate($translations[$alias]);
		} else {
			$terms = Terms::find()->select(['Body', 'Alias'])->where(['Domain' => $domain, 'Alias' => $alias])->indexBy('Alias')->one();
			return !empty($terms['Body']) ? Lang::translate($terms['Body']) : '';
		}
/**/
	}

	//	Получение мест по номеру
	public static function getPcsByNumber($num)
	{
	    $lang = Yii::$app->language;
	    switch ( substr(strval($num), -1) )
	    {
	        default:
	        case "0":
	        case "5":
	        case "6":
	        case "7":
	        case "8":
	        case "9":
	            $res = Terms::translate('bg_3', 'common_form');
	            break;
	        case "1":
	            $res = Terms::translate('bg_1', 'common_form');
	            break;
	        case "2":
	        case "3":
	        case "4":
	            $res = Terms::translate('bg_2', 'common_form');
	            break;
	    }
	    return $res;
	}

}
