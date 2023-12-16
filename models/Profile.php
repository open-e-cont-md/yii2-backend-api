<?php

namespace openecontmd\backend_api\models;

//use frontend\components\sms\OrangeHTTP;
use Yii;
use yii\base\NotSupportedException;
//use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use openecontmd\backend_api\models\Terms;

class Profile extends ActiveRecord implements IdentityInterface
{
    private $_id;
    private $userID;
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
/*
    private $password_hash;
    private $email;
    private $mobile;
    private $client_alias;
    private $first_name;
    private $last_name;
*/
    //public $email;
//    public $password_hash;
    public $password_repeat;
//    public $password_reset_token;
    public $rememberMe = true;
    public $scenario;
    public $claim;
    public $preferred_language;
//    public $auth_key;

    private $_user;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ut_user';
    }

    public function getId()
    {
        $this->_id = $this->userID;
        return $this->_id;
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
/*
    public static function getDb()
    {
        return Yii::$app->get('facturare_db');
    }
*/
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userID' => 'User ID',
            'ParentID' => 'Parent ID',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'password_hash' => 'Password',
            'password_repeat' => 'Password repeat',
            'first_name' => 'Firstname',
            'last_name' => 'Lastname',
            'client_alias' => 'Client Alias',
            'client_id' => 'Client ID',
            'is_principal' => 'Principal',
            'role' => 'Role',
            'business_alias_list' => 'Business List',
            'preferred_language' => 'Preferred Language'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['is_register', 'is_active', 'is_suspended', 'is_deleted', 'email_verified', 'phone_verified', 'xml_sync', 'confirm_password'], 'integer'],
//            [['register_date', 'first_visit', 'last_visit', 'last_using', 'create_date', 'birth_date', 'passport_validity', 'last_sync'], 'safe'],
            [['email', 'password_hash'], 'required', 'message' => Terms::translate('fill_form', 'login_form')],  //  ??????
//            ['password_hash', 'required', 'on' => 'register'],
            ['email', 'unique', 'targetClass' => User::className(),  'message' => Terms::translate('email_taken', 'login_form')],
//            [['login_email'], 'unique'],
//            [['ParentID', 'login_email', 'password_hash', 'mobile_phone', 'country_phcode', 'country_ansi2', 'first_name', 'last_name', 'title', 'tmp_field'], 'string', 'max' => 255],
//            [['purchaser_key', 'web_id', 'passport_number'], 'string', 'max' => 50],
//            [['passport_country', 'preferred_language'], 'string', 'max' => 3],
            ['password_hash', 'string', 'min' => 6],
            [['email', 'password_hash'], 'required', 'on' => 'register'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password_hash', 'message' => Terms::translate('password_dont_match', 'login_form')],
//            [['solei_reward_card'], 'string', 'max' => 100],
//            [['purchaser_key'], 'unique'],
            //['email', 'email'],  //  ?????????
/*
            ['mobile', 'string', 'min' => 6],
            ['first_name', 'string', 'min' => 1],
            ['last_name', 'string', 'min' => 1],
            ['client_alias', 'string', 'min' => 3],
            ['is_principal', 'integer'],
            ['role', 'string', 'min' => 3],
            ['business_alias_list', 'string', 'min' => 3],
*/
        ];
    }
/**
    public function save($runValidation = true, $attributeNames = NULL)
    {
        echo '<pre>'; var_dump($this); exit;
    }
/**/

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['userID' => $id, 'status' => self::STATUS_ACTIVE/*, 'is_deleted' => 0*/]);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    /**
     * @param string $email_confirm_token
     * @return static|null
     */
    public static function findByEmailConfirmToken($email_confirm_token)
    {
        return static::findOne(['verification_token' => $email_confirm_token, 'status' => self::STATUS_INACTIVE]);
    }

    /**
     * Removes email confirmation token
     */
    public function removeEmailConfirmToken()
    {
        $this->verification_token = null;
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public static function newid()
    {

        list($usec, $sec) = explode(" ",microtime());
        $currentTimeMillis = $sec.substr($usec, 2, 3);

        $tmp = rand(0,1)?'-':'';
        $nextLong = $tmp.rand(1000, 9999).rand(1000, 9999).rand(1000, 9999).rand(100, 999).rand(100, 999);

        $valueBeforeMD5 = $currentTimeMillis.':'.$nextLong;
        $valueAfterMD5 = md5($valueBeforeMD5);

        $raw = strtoupper($valueAfterMD5);
        return  substr($raw,0,8).'-'.substr($raw,8,4).'-'.substr($raw,12,4).'-'.substr($raw,16,4).'-'.substr($raw,20);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
//            'is_active' => self::STATUS_ACTIVE,
//            'is_deleted' => self::STATUS_DELETED,
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
/**/
    public function beforeSave($insert)
    {

//        var_dump($insert, $this->status); exit;

        if (parent::beforeSave($insert)) {
            if ($insert) {
                //$this->email = $this->email;
//                $this->is_active = self::STATUS_DELETED;
                $this->created_at = time();
//                $this->passport_number = '1';
//                $this->purchaser_key = self::newid();
//                $this->passport_country = '1';
//                $this->solei_reward_card = '1';
//                $this->preferred_language = Yii::$app->language; //'ro';
                $this->ParentID = '6c9598dba0bb7f8da03f8a8f12acf592';
                $this->status = self::STATUS_INACTIVE;
                $this->username = $this->claim;
                $this->client_id = 0; //date("ym", time()) . strval(random_int(10000, 99999));
                $this->setPassword($this->password_hash);
                $this->generateAuthKey();
                $this->generateEmailConfirmToken();
            }
            return true;
        }
        return false;
    }
/**/
    public function confirmPassword($user_id) {
        $update =  //(new \yii\db\Query())
        Yii::$app->db->createCommand()->update('ut_user', ['confirm_password' => '1'], 'userID = ' . $user_id)->execute();
    }

    public function generateEmailConfirmToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['email' => $username/*, 'is_active' => 1, 'is_deleted' => self::STATUS_DELETED*/]);
    }
/*
    public function save($runValidation = true, $attributeNames = NULL)
    {
//        var_dump($this);exit;
        $this->save();
    }
*/
    public function checkClaim($service = '', $branch = '', $frequency = '', $mode = 'request') {
        $flag = true;
        $flag &= ($service == 'facturare');
        $flag &= in_array($branch, ['free', 'base', 'profi', 'enterprise']);
        $flag &= in_array($frequency, ['monthly', 'yearly', 'all', '']);
        $flag &= in_array($mode, ['demo', '']);

        return ($flag) ? $service.';'.$branch.';'.$frequency.';'.$mode : 'facturare;free;monthly;demo'; //'ask';
    }

    public function setClaim($email, $claim = '', $where = '') {

        if ($where == '') $where = $claim;
        $query_flag = true;
        $cl = explode(';', $claim);
        if (isset($cl['2']) && ($cl['2'] == 'all') ) $cl['2'] = '';
//echo "<pre>"; var_dump(1, $email, $claim, $where, $cl); //exit;
        if ($cl[0] != 'ask') {

            Yii::$app->db->createCommand("START TRANSACTION")->execute();

            if ($cl[1] != 'free') {
                $subscription = $cl[1];
                $cl[1] = 'free';
            } else $subscription = '';

            $query = "INSERT INTO ut_user_product
                (ParentID, user_alias, product_alias, tariff_alias, product_status, subscription, date_request, date_start, date_stop, is_active, credentials_json)
                VALUES ('9fa79e94cb968288b52ebcecb16e3de9', '$email', '{$cl['0']}', '{$cl['1']}', '{$cl['2']}', '{$subscription}', NOW(), NULL, NULL, '1', '')";
//echo "<pre>"; var_dump(2, $email, $claim, $where, $cl, $query); exit;
//var_dump($query);
            $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;
            $query = "UPDATE ut_user SET username = '' WHERE (email = '{$email}') AND (username = '{$where}')";
//var_dump($query);
            $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;

            Yii::$app->db->createCommand("COMMIT")->execute();
        }
        return $query_flag;
    }

}
