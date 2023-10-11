<?php
namespace cabinet\models;

//use common\models\Role;
//use common\models\SysActionRoles;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "ut_user".
 *
 * @property integer $id
 * @property string $email
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $RoleID
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ut_user';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
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
            ['email', 'unique', 'targetClass' => '\cabinet\models\User', 'message' => Terms::translate('email_taken', 'login_form')],
            ['email', 'string', 'min' => 2, 'max' => 255],
            [['first_name', 'last_name'], 'string'],
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

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['userID' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByemail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }
    public static function findByemailInactive($email)
    {
        $r = static::findOne(['email' => $email, 'status' => self::STATUS_INACTIVE]);
//        echo "<pre>"; var_dump(['email' => $email, 'status' => self::STATUS_INACTIVE], $r); exit;
        return $r;
    }

    public static function findByclient($client_id, $email)
    {
        return static::find()->where(['client_id' => $client_id])->andWhere(['<>','email', $email])->all();
    }

    public static function findIdentityByPasswordResetToken($token)
    {
        return static::findOne(['password_reset_token' => $token]);
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
            'status' => self::STATUS_ACTIVE,
        ]);
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
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
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

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
/**
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['RoleID' => 'RoleID']);
    }
/**
    public static function can($action, $structure_id)
    {
        if(!Yii::$app->session->has('role')){
            $user = User::findOne(Yii::$app->user->id);
            Yii::$app->session->set('role', $user->RoleID);
        }

        $result = SysActionRoles::find()
        ->where([
            'action' => $action,
            'ItemId' => $structure_id,
            'RoleId' => Yii::$app->session->get('role')
        ])
        ->one();
        return ($result) ? true : false;
    }
/**/
//***********************************************************************

    public static function findClient($client)
    {
        $sql = "SELECT * FROM ut_client WHERE (alias = '$client')";
//        var_dump($client, $sql); exit;
        return Yii::$app->db->createCommand($sql)->queryOne();
    }

    public static function findBusiness($business)
    {
        $sql = "SELECT * FROM ut_business WHERE (alias = '$business')";
        //        var_dump($client, $sql); exit;
        return Yii::$app->db->createCommand($sql)->queryOne();
    }

}
