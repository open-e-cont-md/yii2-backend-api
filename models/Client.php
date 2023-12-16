<?php
namespace openecontmd\backend_api\models;

//use common\models\Role;
//use common\models\SysActionRoles;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Client extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
/*
    public $password_hash;
    public $password_repeat;
    public $email;
*/
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ut_customer';
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

    public static function findCustomer($key)
    {
        $sql = "SELECT * FROM ut_customer WHERE (unique_key = '$key')";
        $ret = Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_CLASS);
//echo "<pre>"; var_dump($sql, $ret); echo "</pre>"; exit;
        if ($ret) $ret = $ret[0];
        return $ret;
    }

    public static function findCustomerByID($client)
    {
        $sql = "SELECT * FROM ut_customer WHERE (customerID = '$client')";
        $ret = Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_CLASS);
        if ($ret) $ret = $ret[0];
//echo "<pre>"; var_dump($sql, $ret); echo "</pre>"; exit;
        return $ret;
    }

    public static function deleteCustomer($client)
    {
        $sql = "DELETE FROM ut_customer WHERE (unique_key = '$client')";
        $ret = Yii::$app->db->createCommand($sql)->execute();
        return $ret;
    }

    public static function deleteCustomerByID($client)
    {
        $sql = "DELETE FROM ut_customer WHERE (customerID = '$client')";
        $ret = Yii::$app->db->createCommand($sql)->execute();
        return $ret;
    }

    public static function findCustomerByEmail($client_id, $email)
    {
        $sql = "SELECT * FROM ut_customer WHERE (client_id = '$client_id') AND (contact_email = '$email')";
//echo "<pre>"; var_dump($sql); echo "</pre>"; exit;
        $ret = Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_CLASS);
        if ($ret) $ret = $ret[0];
        return $ret;
    }
    public static function findCustomerByIDNO($client_id, $idno)
    {
        $sql = "SELECT * FROM ut_customer WHERE (client_id = '$client_id') AND (idno = '$idno')";
        $ret = Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_CLASS);
        if ($ret) $ret = $ret[0];
        return $ret;
    }

}
