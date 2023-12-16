<?php
namespace openecontmd\backend_api\models;

//use common\models\Role;
//use common\models\SysActionRoles;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Customer extends ActiveRecord
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

    public static function findClient($client)
    {
        $sql = "SELECT * FROM ut_client WHERE (alias = '$client')";
//        var_dump($client, $sql); exit;
        return Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_CLASS);
    }

    public static function findClientByAlias($alias)
    {
        $sql = "SELECT * FROM ut_client WHERE (alias = '$alias') /*AND (status = '".self::STATUS_ACTIVE."')*/";
//var_dump($alias, $sql); exit;
        return Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_CLASS);
    }
    public static function findClientByID($client_id)
    {
        $sql = "SELECT * FROM ut_client WHERE (client_id = '{$client_id}') /*AND (status = '".self::STATUS_ACTIVE."')*/";
//var_dump($client_id, $sql); exit;
        return Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_CLASS);
    }

    public static function findSubscription($client_id)
    {
        $sql = "SELECT subscription FROM ut_user_product WHERE (client_id = '$client_id')";
//var_dump($client_id, $sql); exit;
        $ret = Yii::$app->db->createCommand($sql)->queryOne(); // \PDO::FETCH_CLASS
        if (!$ret) return null;
        $sql = "SELECT json_get(caption, '".Yii::$app->language."') AS tariff FROM ut_tariff WHERE (type = 'facturare') AND (alias = '{$ret['subscription']}')";
        return Yii::$app->db->createCommand($sql)->queryOne(); // \PDO::FETCH_CLASS
    }

    public static function findClientByEmail($email)
    {
        $sql = "SELECT * FROM ut_client WHERE (email = '$email') AND (status = '".self::STATUS_ACTIVE."')";
        return Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_CLASS);
    }
    public static function findUserByEmail($email)
    {
        $sql = "SELECT * FROM ut_user WHERE (email = '$email') AND (status = '".self::STATUS_ACTIVE."')";
        return Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_CLASS);
    }
    public static function findBusinessesByClient($client)
    {
        $sql = "SELECT * FROM ut_business WHERE (client_alias = '$client') ORDER BY businessID";
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function findBusinessesByClientID($client_id)
    {
        $sql = "SELECT * FROM ut_business WHERE (client_id = '$client_id') ORDER BY businessID";
        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    public static function findBusinessesByClientSelected($client_id, $business_list)
    {
        $bl = ""; $bla = explode(',', $business_list);
        foreach ($bla as $v) {
            if ($bl != "") $bl .= ",";
            $bl .= "'".$v."'";
        }
        $sql = "SELECT * FROM ut_business WHERE (client_id = '$client_id') AND (business_token IN ({$bl})) ORDER BY businessID";
//var_dump($client, $sql); exit;
        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    public static function findBusinessesByToken($token)
    {
        $sql = "SELECT * FROM ut_business WHERE (business_token = '$token') ORDER BY businessID";
        return Yii::$app->db->createCommand($sql)->queryOne();
    }
    public static function findBusiness($business)
    {
        $sql = "SELECT * FROM ut_business WHERE (alias = '$business')";
        //        var_dump($client, $sql); exit;
        return Yii::$app->db->createCommand($sql)->queryAll();
    }

    public static function cntClients($client_id)
    {
        $sql = "SELECT COUNT(*) AS cnt FROM ut_user WHERE client_id = '{$client_id}'";
        return Yii::$app->db->createCommand($sql)->queryOne();
    }

    public static function findClaimsClient()
    {
        $sql = "SELECT alias, ut_user.client_id, caption, is_accepted, moment, ut_user.email
        FROM ut_user
        RIGHT OUTER JOIN ut_client ON (ut_user.client_id = ut_client.client_id)
        WHERE (IFNULL(is_accepted, 0) != 1) ORDER BY clientID";
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function findClaimsBusiness()
    {
        $sql = "SELECT client_alias, client_id, alias, caption, business_accepted, changes_accepted, last_changes, moment, mode, business_token
            FROM ut_business WHERE (IFNULL(business_accepted, 0) != 1) AND (IFNULL(business_claim, 0) = 1) ORDER BY businessID";
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function findClaimsBusinessActive()
    {
        $sql = "SELECT client_alias, client_id, alias, caption, business_accepted, changes_accepted, last_changes, moment, mode, business_token
            FROM ut_business WHERE (IFNULL(business_accepted, 0) = 1) AND (IFNULL(business_claim, 0) = 1) ORDER BY businessID";
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function findClaimsBusinessParameters()
    {
        $sql = "SELECT client_alias, client_id, alias, caption, business_accepted, changes_accepted, last_changes, moment, mode, business_token
            FROM ut_business WHERE (last_changes != '') AND (IFNULL(changes_claim, 0) = 1) ORDER BY businessID";
        return Yii::$app->db->createCommand($sql)->queryAll();
    }
    public static function findClaimsTariffs()
    {
        $sql = "SELECT
  ut_user_product.user_productID,
  ut_user_product.user_alias,
  ut_user_product.product_status,
  ut_user_product.tariff_alias,
  ut_user_product.date_request,
  ut_user_product.date_start,
  ut_user_product.date_stop,
  ut_user_product.profile_json,
  json_get(ut_tariff.caption, '".Yii::$app->language."') AS caption,
  ut_user.email
FROM
  ut_tariff
  RIGHT OUTER JOIN ut_user_product ON (ut_tariff.alias = ut_user_product.tariff_alias)
  LEFT OUTER JOIN ut_user ON (ut_user_product.client_id = ut_user.client_id)
WHERE product_status = 'request'";
        return Yii::$app->db->createCommand($sql)->queryAll();
    }


    static function getContext($business = '')
    {
        $context = [];
        if (!\Yii::$app->user->isGuest) {
            $_user = User::findByEmail(\Yii::$app->user->identity->email);
            $context['user'] = $_user;
            $context['business_aliases'] = explode(',', $_user['business_alias_list']);

            if ( ($_user->client_id > 0) && ($_user->status == 2) )
            {
                Yii::$app->params['client'] = $_user->client_id;
//echo "<pre>"; var_dump($_user); echo "</pre>"; exit;
                $_client = self::findClientByID($_user->client_id);
//echo "<pre>"; var_dump($_user, $_client); echo "</pre>"; exit;
                $_subscription = self::findSubscription($_user->client_id);
//echo "<pre>"; var_dump($_subscription); echo "</pre>"; exit;
                $context['client'] = $_client[0];
                if ($_subscription) $context['client']->subscription = $_subscription["tariff"]; else $context['client']->subscription = '';
//echo "<pre>"; var_dump(\Yii::$app->user); echo "</pre>"; exit;
                $_business = self::findBusinessesByClientSelected($_user->client_id, $_user->business_alias_list);
//                $context['selected_business'] = 0;
                $context['business'] = $_business;
//echo "<pre>"; var_dump($context); echo "</pre>"; exit;

                if ( ($business != '') && (count($_business) > 1) )
                {
                    foreach ($_business as $k => $v)
                        if ($v['business_token'] == $business) $context['selected_business'] = $k;
                } else {
                    $context['selected_business'] = 0;
                }
//echo "<pre>"; var_dump($context); echo "</pre>"; exit;
                Yii::$app->params['business'] = $business;
            }

            if ( ($_user->client_id > 0) && ($_user->status == 1) )
            {
                Yii::$app->params['client'] = $_user->client_id;
                //echo "<pre>"; var_dump($_user); echo "</pre>"; exit;
                $_client = self::findClientByID($_user->client_id);
                if (!$_client) return $context;
                //echo "<pre>"; var_dump($_user, $_client); echo "</pre>"; exit;
                $_subscription = self::findSubscription($_user->client_id);
                //echo "<pre>"; var_dump($_subscription); echo "</pre>"; exit;
                $context['client'] = $_client[0];
                if ($_subscription) $context['client']->subscription = $_subscription["tariff"]; else $context['client']->subscription = '';
                //echo "<pre>"; var_dump(\Yii::$app->user); echo "</pre>"; exit;
                $_business = self::findBusinessesByClientSelected($_user->client_id, $_user->business_alias_list);
                //                $context['selected_business'] = 0;
                $context['business'] = $_business;
//echo "<pre>"; var_dump($_business); echo "</pre>"; exit;

                if ( ($business != '') && (count($_business) > 1) )
                {
                    foreach ($_business as $k => $v)
                        if ($v['business_token'] == $business) $context['selected_business'] = $k;
                } else {
                    $context['selected_business'] = 0;
                }
                //echo "<pre>"; var_dump($context); echo "</pre>"; exit;
                Yii::$app->params['business'] = $business;
            }

            return $context;
        }
        else
            return null;
    }

    public function getContextByToken($business_token = '')
    {
        $context = [];
        if (!\Yii::$app->user->isGuest) {
            $_user = User::findByEmail(\Yii::$app->user->identity->email);
            $context['user'] = $_user;
            $context['business_aliases'] = explode(',', $_user['business_alias_list']);
            if ($_user->client_id > 0)
            {
                Yii::$app->params['client'] = $_user->client_id;
                $_client = self::findClientByID($_user->client_id);


                $context['client'] = $_client[0];
/**/
                if ($context['client']->profile_json != '') {
                    $context['client']->profile = json_decode($context['client']->profile_json);
                    //$context['client']['profile'] = json_decode($context['client']->profile_json);
                } else {
                    //$context['client']->profile = (object)null;
                    $context['client']->profile = json_decode('{"global_registration":"1","idno":"","no_tva":"1","tva":"","global_bank":"1","bank_name":"","bank_address":"","mdl_account":"","bank_code":"","global_juridical":"1","juridical_country_code":"MD","juridical_city":"","juridical_address":"","juridical_postal_index":"","global_contact":"1","country_code":"MD","city":"","address":"","postal_index":"","tva_rate":"20","tva_calc":"inner","invoice_pattern":"blank-invoice-qr","preferred_language":"ro"}');
                }

//echo "<pre>"; var_dump($context);exit;

                $_business = self::findBusinessesByClientID($_user->client_id);
                //$_business['profile_json']
//                $_business = self::findBusinessesByToken($business_token);
//echo "<pre>"; var_dump($_business);exit;
                //$context['selected_business'] = 0;
                $context['business'] = $_business;

                if ( ($business_token != '') && (count($_business) > 1) )
                {
                    foreach ($_business as $k => $v)
                        if ($v['business_token'] == $business_token) $context['selected_business'] = $k;
                }
                else
                    $context['selected_business'] = 0;
                Yii::$app->params['business'] = $business_token;

//echo "<pre>"; var_dump($_business, $business_token, $context['selected_business']); exit;

            }
            return $context;
        }
        else
            return null;
    }


    public static function qntCustomers($client_id = '')
    {
        $sql = "SELECT count(*) AS cnt FROM ut_customer WHERE (client_id = '{$client_id}')";
        $r = Yii::$app->db->createCommand($sql)->queryOne();
        return $r['cnt'];
    }
    public static function qntManagers()
    {
        $sql = "SELECT count(*) AS cnt FROM ut_user";
        $r = Yii::$app->db->createCommand($sql)->queryOne();
        return $r['cnt'];
    }
    public static function qntFacturas($where = '')
    {
        $sql = "SELECT count(*) AS cnt FROM ut_factura WHERE (1=1) $where ";
//var_dump($where, $sql); exit;
        $r = Yii::$app->db->createCommand($sql)->queryOne();
        return $r['cnt'];
    }

    public static function deletePattern($inner_hash)
    {
        $sql = "DELETE FROM ut_invoice_pattern WHERE (inner_hash = '$inner_hash')";
        $ret = Yii::$app->db->createCommand($sql)->execute();
        return $ret;
    }

}
