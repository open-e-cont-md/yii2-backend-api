<?php

namespace openecontmd\backend_api\models;

use Yii;

/**
 * This is the model class for table "sys_formdescription".
 *
 * @property integer $FormDescriptionID
 * @property string $TableName
 * @property string $FormHeader
 * @property string $FieldName
 * @property string $FieldType
 * @property integer $FieldOrder
 * @property integer $isRequired
 * @property integer $isVisible
 * @property integer $isReadOnly
 * @property integer $Width
 * @property string $RoleRightsIDs
 * @property string $SelectSrc
 * @property integer $FormNum
 */
class SysFormdescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_formdescription';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['FieldOrder', 'isRequired', 'isVisible', 'isReadOnly', 'BreakLine', 'FormNum', 'Width'], 'integer'],
            [['UserRightsIDs', 'RoleRightsIDs', 'SelectSrc', 'HeaderLine'], 'string'],
            [['TableName', 'FieldName'], 'string', 'max' => 250],
            [['FormHeader'], 'string', 'max' => 150],
            [['FieldType'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'FormDescriptionID' => Yii::t('apl', 'FormDescriptionID'),
            'TableName' => Yii::t('apl', 'TableName'),
            'FormHeader' => Yii::t('apl', 'FormHeader'),
            'FieldName' => Yii::t('apl', 'FieldName'),
            'FieldType' => Yii::t('apl', 'FieldType'),
            'FieldOrder' => Yii::t('apl', 'FieldOrder'),
            'isRequired' => Yii::t('apl', 'isRequired'),
            'isVisible' => Yii::t('apl', 'isVisible'),
            'isReadOnly' => Yii::t('apl', 'isReadOnly'),
        	'HeaderLine' => Yii::t('apl', 'HeaderLine'),
        	'BreakLine' => Yii::t('apl', 'BreakLine'),
            'RoleRightsIDs' => Yii::t('apl', 'RoleRightsIDs'),
            'SelectSrc' => Yii::t('apl', 'SelectSrc'),
            'FormNum' => Yii::t('apl', 'FormNum'),
            'Width' => Yii::t('apl', 'Width'),
        ];
    }

    /***
     * @return \yii\db\ActiveQuery
     */

    public function getTable()
    {
        return $this->hasOne(SysTablelist::className(), ['SysTableName' => 'TableName']);
    }
}
