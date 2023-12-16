<?php

namespace openecontmd\backend_api\models;

//use Yii;

/**
 * This is the model class for table "sys_action_roles".
 *
 * @property integer $id
 * @property string $action
 * @property string $ItemId
 * @property integer $RoleId
 * @property integer $ItemType
 */
class SysActionRoles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_action_roles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RoleId', 'ItemType'], 'integer'],
            [['action', 'ItemId'], 'string', 'max' => 55],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action' => 'Action',
            'ItemId' => 'Item ID',
            'RoleId' => 'Role ID',
            'ItemType' => 'Item Type',
        ];
    }
}
