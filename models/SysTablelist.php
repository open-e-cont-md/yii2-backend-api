<?php

namespace openecontmd\backend_api\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "sys_tablelist".
 *
 * @property integer $TablelistID
 * @property string $SysTableName
 * @property string $Icon
 */
class SysTablelist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_tablelist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SysTableName'], 'string', 'max' => 150],
            [['Icon'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TablelistID' => 'Tablelist ID',
            'SysTableName' => 'Sys Table Name',
            'Icon' => 'Icon',
        ];
    }

    public function getRows()
    {
        return $this->hasMany(SysTabledescription::className(), ['TableName' => 'SysTableName']);
    }

    /***
     * @param $params
     * @return ActiveDataProvider
     * model search
     */
    public function search($params)
    {
        $query = self::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        /**
         * Setup your sorting attributes
         * Note: This is setup before the $this->load($params)
         * statement below
         */
        $dataProvider->setSort([
            'attributes' => [
                'SysTableName',
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'SysTableName', $this->SysTableName]);
        $query->andFilterWhere(['like', 'Icon', $this->Icon]);

        /* Setup your custom filtering criteria */


        return $dataProvider;
    }
}
