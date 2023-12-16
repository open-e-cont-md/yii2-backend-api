<?php

namespace openecontmd\backend_api\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ut_structure".
 *
 * @property string $StructureID
 * @property string $ParentID
 * @property string $FolderName
 * @property string $ChildType
 * @property integer $OrderIndex
 * @property string $TreeCode
 * @property string $RoleRightsIDs
 * @property string $icon
 */
class Structure extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_structure';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['OrderIndex', 'sys_node_only'], 'integer'],
            [['StructureID', 'ParentID'], 'string', 'max' => 36],
            [['FolderName', 'ChildType', 'TreeCode', 'sys_menu_header'], 'string', 'max' => 255],
            [['icon', 'sys_alias'], 'string', 'max' => 200],
            [['StructureID'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'StructureID' => Yii::t('apl', 'StructureID'),
            'ParentID' => Yii::t('apl', 'ParentID'),
            'FolderName' => Yii::t('apl', 'FolderName'),
            'ChildType' => Yii::t('apl', 'ChildType'),
            'OrderIndex' => Yii::t('apl', 'OrderIndex'),
            'TreeCode' => Yii::t('apl', 'TreeCode'),
            'RoleRightsIDs' => Yii::t('apl', 'RoleRightsIDs'),
            'icon' => Yii::t('apl', 'icon'),
            'sys_menu_header' => 'Menu Header',
            'sys_node_only' => 'Node Only',
            'sys_alias' => 'Alias'
        ];
    }

    public function search($params) {
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
                'StructureID',
                'ParentID',
                'FolderName',
                'ChildType',
                'OrderIndex',
                'TreeCode',
                'RoleRightsIDs',
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'ParentID', $this->FolderName]);
        $query->andFilterWhere(['like', 'FolderName', $this->FolderName]);
        $query->andFilterWhere(['like', 'ChildType', $this->ChildType]);
        $query->andFilterWhere(['like', 'OrderIndex', $this->OrderIndex]);

        /* Setup your custom filtering criteria */


        return $dataProvider;
    }

    public function getParent()
    {
        return $this->hasOne(self::className(), ['StructureID' => 'ParentID']);
    }

    public function getParents()
    {
        return $this->hasMany(self::className(), ['ParentID' => 'StructureID'])->orderBy('OrderIndex');
    }

    public static function getTreeParents($item, $actives = [])
    {
        if (isset($item)) {
            $actives[$item->StructureID] = $item->StructureID;
            if ($item->parent) {
                $actives[$item->ParentID] = $item->ParentID;
                return self::getTreeParents($item->parent, $actives);
            }
        }
        else
        {
            $actives = [];
        }
        return $actives;
    }
}
