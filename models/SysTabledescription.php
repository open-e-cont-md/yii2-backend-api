<?php

namespace openecontmd\backend_api\models;

use Yii;

/**
 * This is the model class for table "sys_tabledescription".
 *
 * @property integer $TableDescriptionID
 * @property string $TableName
 * @property string $FieldName
 * @property string $FieldType
 * @property string $FieldDescriptionAlign
 * @property integer $FieldVisible
 * @property string $FieldAlign
 * @property string $UserRigtsIDs
 * @property string $RoleRightsIDs
 * @property string $OrderFields
 * @property integer $SortIndex
 */
class SysTabledescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_tabledescription';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['FieldVisible', 'SortIndex'], 'integer'],
            [['UserRigtsIDs', 'RoleRightsIDs'], 'string'],
            [['TableName', 'FieldName', 'OrderFields'], 'string', 'max' => 250],
            [['FieldType'], 'string', 'max' => 50],
            [['FieldDescriptionAlign', 'FieldAlign'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TableDescriptionID' => Yii::t('apl', 'TableDescriptionID'),
            'TableName' => Yii::t('apl', 'TableName'),
            'FieldName' => Yii::t('apl', 'FieldName'),
            'FieldType' => Yii::t('apl', 'FieldType'),
            'FieldDescriptionAlign' => Yii::t('apl', 'FieldDescriptionAlign'),
            'FieldVisible' => Yii::t('apl', 'FieldVisible'),
            'FieldAlign' => Yii::t('apl', 'FieldAlign'),
            'UserRigtsIDs' => Yii::t('apl', 'UserRigtsIDs'),
            'RoleRightsIDs' => Yii::t('apl', 'RoleRightsIDs'),
            'OrderFields' => Yii::t('apl', 'OrderFields'),
            'SortIndex' => Yii::t('apl', 'SortIndex'),
        ];
    }

    public static function getKeyName($table)
    {
        return substr($table['SysTableName'], 3) . 'ID';
    }

    /***
     * @return \yii\db\ActiveQuery
     */

    public function getTable()
    {
        return $this->hasOne(SysTablelist::className(), ['SysTableName' => 'TableName']);
    }

    public function getForm()
    {
        return $this->hasOne(SysFormdescription::className(), ['FieldName' => 'FieldName']);
    }
}
