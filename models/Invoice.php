<?php
namespace openecontmd\backend_api\models;

//use common\models\Role;
//use common\models\SysActionRoles;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Invoice extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ut_client';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'unique', 'targetClass' => '\cabinet\models\User', 'message' => 'This email has already been taken.'],
            ['email', 'string', 'min' => 2, 'max' => 255],

            ['alias', 'required', 'on' => 'register'],

//            [['first_name', 'last_name'], 'string'],
/*
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\cabinet\models\User', 'message' => 'This email address has already been taken.'],
*/
            ['password_hash', 'string', 'min' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'id'),
            'email' => Yii::t('form', 'email'),
            'auth_key' => Yii::t('form', 'auth_key'),
            'password_hash' => Yii::t('form', 'password_hash'),
            'password_reset_token' => Yii::t('form', 'password_reset_token'),
            'email' => Yii::t('form', 'email'),
            'status' => Yii::t('form', 'status'),
            'created_at' => Yii::t('form', 'created_at'),
            'updated_at' => Yii::t('form', 'updated_at'),
//            'RoleID' => Yii::t('form', 'RoleID')
        ];
    }

    public static function findInvoice($key)
    {
        $sql = "SELECT * FROM ut_factura WHERE (inner_hash = '$key')";
        //var_dump($key, $sql); exit;
        return Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_CLASS);
    }
    public static function findInvoiceNumber($client_id, $business)
    {
        $sql = "SELECT digits_from_string(outer_number) AS seq_num FROM ut_factura WHERE (client_id = '$client_id') AND (business_alias = '$business') ORDER BY moment DESC LIMIT 1";
//var_dump($business, $sql); exit;
        return Yii::$app->db->createCommand($sql)->queryOne();
    }
    public static function getBusinessPrefix($client_id, $business_id, $option_alias = 'invoices_manual')
    {
        $sql = "SELECT invoice_prefix FROM ut_api_keys
        WHERE (client_id = '{$client_id}') AND (business_id = '{$business_id}') AND (integration_alias = '{$option_alias}')";
//var_dump($sql);exit;
        return Yii::$app->db->createCommand($sql)->queryOne();
    }
    public static function getStatuses($status)
    {
        $sql = "SELECT alias, caption FROM ut_status WHERE FIND_IN_SET(alias, (SELECT accepted FROM ut_status WHERE alias = '$status')) OR (alias = '$status')";
        return Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_CLASS);
    }
    public static function getStatusCaption($status)
    {
        $sql = "SELECT caption FROM ut_status WHERE (alias = '$status')";
        return Yii::$app->db->createCommand($sql)->queryOne();
    }

    public static function getCustomers($client_id)
    {
        $r = [];
        $sql = "SELECT customerID, first_name, last_name, caption, idno, currency, contact_email FROM ut_customer WHERE (client_id = '$client_id')";
        $ret = Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_CLASS);
        foreach ($ret as $v) {
            $r[$v->customerID]['caption'] = ($v->caption != '') ? $v->caption : '';
            $r[$v->customerID]['caption'] .= ( ($v->last_name != '') || ($v->first_name != '') ) ? (($v->caption != '') ? ' / ' : '').trim($v->first_name.' '.$v->last_name) : '';
            $r[$v->customerID]['caption'] .= ($v->idno != '') ? ' / '.$v->idno : '';
            $r[$v->customerID]['caption'] .= ($v->contact_email != '') ? ' / '.$v->contact_email : '';
            $r[$v->customerID]['currency'] = $v->currency;
        }
        return $r;
    }

    public static function getCustomerById($client_id, $id)
    {
        $r = [];
        $sql = "SELECT customerID, first_name, last_name, caption, idno, currency, contact_email FROM ut_customer
            WHERE (client_id = '$client_id') AND (customerID = '$id')";
        $ret = Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_CLASS);
        foreach ($ret as $v) {
            $r[$v->customerID]['caption'] = ($v->caption != '') ? $v->caption : '';
            $r[$v->customerID]['caption'] .= ( ($v->last_name != '') || ($v->first_name != '') ) ? (($v->caption != '') ? ' / ' : '').trim($v->first_name.' '.$v->last_name) : '';
            $r[$v->customerID]['caption'] .= ($v->idno != '') ? ' / '.$v->idno : '';
            $r[$v->customerID]['caption'] .= ($v->contact_email != '') ? ' / '.$v->contact_email : '';
            $r[$v->customerID]['currency'] = $v->currency;
        }
        return $r;
    }

}
