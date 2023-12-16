<?php
namespace openecontmd\backend_api\controllers;

use Yii;
//use openecontmd\backend_api\models\Client;
use openecontmd\backend_api\models\Customer;
use openecontmd\backend_api\models\SysTabledescription;
//use openecontmd\backend_api\models\SysFormdescription;
use openecontmd\backend_api\models\SysTablelist;
//use openecontmd\backend_api\models\Structure;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\base\ErrorException;
use openecontmd\backend_api\models\User;
//use openecontmd\backend_api\models\Invoice;
use openecontmd\backend_api\models\Terms;
use openecontmd\backend_api\models\Content;
use openecontmd\backend_api\models\Tariff;
use openecontmd\backend_api\models\Profile;

/*
use Yii;
use common\models\SysTabledescription;
use common\models\SysTablelist;
use yii\data\Pagination;
use cabinet\models\Content;
use cabinet\models\Tariff;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use cabinet\models\Terms;
use cabinet\models\Customer;
use yii\web\Response;
use cabinet\models\Profile;
use cabinet\models\User;
use yii\web\NotFoundHttpException;
use yii\base\ErrorException;
use docs\models\Document;
*/

/**
 * Site controller
 */
class CustomerController extends BaseController
{
    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'dashboard', 'reportfull', 'idno', 'buyersearch', 'buyerajax', 'buyer', 'md5', 'alias'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get', 'post'],
//                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex($product = '', $frequency = 'all')
    {
//echo "<pre>"; print_r(Yii::$app->request->post(), false); echo "</pre>"; exit;
//        echo "<pre>"; var_dump(Yii::$app->request->post(), $product, $frequency, Yii::$app->user->identity->client_id); echo "</pre>"; exit;

        if (!empty(Yii::$app->request->post()))
        {
            $p = Yii::$app->request->post();
//echo "<pre>"; var_dump($p, $query); echo "</pre>"; exit;

            $business = (isset($p['business'])) ? $p['business'] : ['ru' => 'Главный офис', 'ro' => 'Biroul principal', 'en' => 'Main office'];

            $p = self::_prepareData($p, 'ut_client');
//            $g = Yii::$app->request->get();
            switch ($p['mode']) {
                default:

                case 'update':
                    Yii::$app->db->createCommand("START TRANSACTION")->execute();
                    if ($p['alias'] != $p['client']) {
                        $res = Yii::$app->db->createCommand("SELECT alias FROM ut_client WHERE (alias = '{$p['alias']}')")->queryOne();
                        if ($res) {
                            Yii::$app->db->createCommand("ROLLBACK")->execute();
                            Yii::$app->session->setFlash('error', Terms::translate('double_alias', 'alert').': "'.$p['alias'].'"');
                            break;
                        }
                    }


//echo "<pre>"; print_r($p, false); echo "</pre>"; exit;
                    //$p = self::_prepareData($p, 'ut_business');
                    $profile = json_decode($p['profile']);
//echo "<pre>"; var_dump(stripslashes($p['profile']), $profile); echo "</pre>"; exit;
//print_r($p, false);
//echo "<pre>"; var_dump($p['profile'], $profile); echo "</pre>"; exit;

                    $profile_str = json_encode($profile, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT);
//echo "<pre>"; var_dump($p['profile'], $profile, $profile_str); echo "</pre>"; exit;
//echo "<pre>"; print_r($profile, false); echo "</pre>"; exit;

//echo "<pre>"; print_r($p, false); print_r($profile, false); echo "</pre>"; exit;

                    $query_img = '';
                    if (isset($p['ImageURL']))  $query_img .= " ImageURL  = '{$p['ImageURL']}',";
                    if (isset($p['ImageURL2'])) $query_img .= " ImageURL2 = '{$p['ImageURL2']}',";
                    if (isset($p['ImageURLi']))  $query_img .= " ImageURLi  = '{$p['ImageURLi']}',";
                    //if (isset($p['ImageURLv1']))  $query .= "ImageURLv1  = '{$p['ImageURLv1']}',";

                    if ($p['client'] == $p['alias'])
                    {
                        $query = "UPDATE ut_client SET contact_phone = '".$p['mobile']."', caption = '".$p['caption']."', caption_long = '".$p['caption_long']."', site_url = '".$p['site_url']."',
                        tariff_alias = '".$p['tariff_alias']."', ".$query_img." idno = '".$profile->idno."', tva = '".$profile->tva."',
                        country_code = '".$profile->country_code."', city = '".$profile->city."', postal_index = '".$profile->postal_index."',
                        address = '".$profile->address."', profile_json = '".$profile_str."', preferred_language = '".$p['preferred_language']."'
                        WHERE (alias = '{$p['client']}')";
//var_dump($query);exit;
                        $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;
                    }
                    else    //  Изменение алиаса клиента
                    {
                        $query = "UPDATE ut_client SET alias = '".$p['alias']."', contact_phone = '".$p['mobile']."', caption = '".$p['caption']."', caption_long = '".$p['caption_long']."', site_url = '".$p['site_url']."',
                        tariff_alias = '".$p['tariff_alias']."', ".$query_img." idno = '".$profile->idno."', tva = '".$profile->tva."',
                        country_code = '".$profile->country_code."', city = '".$profile->city."', postal_index = '".$profile->postal_index."',
                        address = '".$profile->address."', profile_json = '".$profile_str."', preferred_language = '".$p['preferred_language']."'
                        WHERE (alias = '{$p['client']}')";
//var_dump($query);exit;
                        $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;

                        $query = "UPDATE ut_business SET client_alias = '{$p['alias']}' WHERE (client_id = '{$p['client_id']}')";
                        $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;

                        $query = "UPDATE ut_user_product SET user_alias = '{$p['alias']}' WHERE (client_id = '{$p['client_id']}')";
                        $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;

                        $query = "UPDATE ut_user SET client_alias = '{$p['alias']}' WHERE (client_id = '{$p['client_id']}')";
                        $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;

                    }
//echo "<pre>"; var_dump($query, $query_flag); echo "</pre>"; exit;
//                    $query_flag = true;
                    if ($query_flag)
                    {
                        Yii::$app->db->createCommand("COMMIT")->execute();
                        Yii::$app->session->setFlash('success', Terms::translate('changes_saved', 'alert'));
                    }
                    else
                    {
                        Yii::$app->db->createCommand("ROLLBACK")->execute();
                        Yii::$app->session->setFlash('info', Terms::translate('nothing_saved', 'alert'));
                    }
                break;

                case 'create':
//echo "<pre>"; var_dump($p, $business); exit;
                    Yii::$app->db->createCommand("START TRANSACTION")->execute();
                        $res = Yii::$app->db->createCommand("SELECT alias FROM ut_client WHERE (alias = '{$p['alias']}')")->queryOne();
                        if ($res) {
                            Yii::$app->db->createCommand("ROLLBACK")->execute();
                            Yii::$app->session->setFlash('error', Terms::translate('double_alias', 'alert').$p['alias'].'"!');
                            break;
                        }
                        else
                        {
                            $res = Yii::$app->db->createCommand("SELECT client_alias FROM ut_user WHERE (client_alias = '{$p['alias']}')")->queryOne();
                            if ($res) {
                                Yii::$app->db->createCommand("ROLLBACK")->execute();
                                Yii::$app->session->setFlash('error', Terms::translate('double_alias', 'alert').$p['alias'].'"!');
                                break;
                            }
                        }
                    $profile = json_decode($p['profile'], JSON_OBJECT_AS_ARRAY);
//print_r($profile, false); //exit;
//                    $profile = '{"global_registration":"1","idno":"","no_tva":"1","tva":"","global_bank":"1","bank_name":"","bank_address":"","mdl_account":"","bank_code":"","global_juridical":"1","juridical_country_code":"MD","juridical_city":"","juridical_address":"","juridical_postal_index":"","global_contact":"1","country_code":"MD","city":"","address":"","postal_index":"","tva_rate":"20","tva_calc":"inner","invoice_pattern":"blank-invoice-qr","preferred_language":"ro"}';
$p['profile'] = $profile; //json_decode($profile, JSON_PRETTY_PRINT);
$profile = json_encode($p['profile'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT);
//print_r($p, false); print_r($profile, false); echo "</pre>"; exit;

//echo "<pre>"; print_r($p, false); print_r($profile, false); echo "</pre>"; exit;

//  Создане клиента  ======================================================================================================
                    $cid = Yii::$app->user->identity->client_id;
                    //$cid = date("ym", time()) . strval(random_int(10000, 99999));

                    $client_token = md5(rand(1, 9999).time().'e-cont_salt'.$p['alias']);
                    $business_token = md5(rand(1, 9999).time().'67890'.$p['alias']);
                    $test_client_token = md5(rand(1, 9999).time().'econtmd_salt'.$p['alias']);
                    $test_business_token = md5(rand(1, 9999).time().'09876'.$p['alias']);

                    $query = "INSERT INTO ut_client (ParentID, moment, alias, client_id, contact_email, contact_phone, caption, caption_long, site_url, is_accepted, tariff_alias,
                        country_code, city, postal_index, address, client_token, profile_json, preferred_language, idno, tva, subscription) VALUES (
                        '7e4d670a47694696718654a864dc375b', NOW(), '".$p['alias']."', '{$cid}', '".$p['email']."', '".$p['mobile']."', '".$p['caption']."', '".$p['caption_long']."', '".$p['site_url']."', 0, '".$p['tariff_alias']."',
                        '".$p['profile']['country_code']."', '".$p['profile']['city']."', '".$p['profile']['postal_index']."', '".$p['profile']['address']."',
                    '{$client_token}', '{$profile}', '".$p['preferred_language']."', '".$p['profile']['idno']."', '".$p['profile']['tva']."', '{}')";
                    $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;
                    $r = Yii::$app->db->createCommand('SELECT LAST_INSERT_ID() AS client_id')->queryOne();
                    $client_id = $r['client_id'];

//echo "<pre>"; var_dump($query, $query_flag, $r, $client_id); echo "</pre>"; exit;

                    $query = "UPDATE ut_user SET client_alias = '".$p['alias']."', mobile = '".$p['mobile']."', /*client_id = '{$cid}',*/
                        is_principal = 1, role = 'senior_manager', business_alias_list = '{$business_token}'
                        WHERE (email = '{$p['email']}')";
                    $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;
//echo "<pre>"; var_dump($query, $query_flag); echo "</pre>"; exit;

//  Создане подразделения  ======================================================================================================
                    $query = "INSERT INTO ut_business (ParentID, moment, client_alias, client_id, alias,
                        caption,
                        description, pattern_goal, pattern_item,
                        pattern_remark, copyright, developed, link,
                        contact_email, contact_phone, preferred_language, mode, warn_days, business_accepted, changes_accepted, invoice_pattern, email_pattern,
                        country_code, juridical_country_code, business_token, profile_json, prefix ) VALUES (
                        '40eaba29684c28e470ce9a1c7b345104', NOW(), '".$p['alias']."', '{$cid}', 'office',
                        '{\"ru\":\"".addslashes($business['ru'])."\",\"ro\":\"".addslashes($business['ro'])."\",\"en\":\"".addslashes($business['en'])."\"}',
                        '{\"ru\":\"\",\"ro\":\"\",\"en\":\"\"}', '{\"ru\":\"\",\"ro\":\"\",\"en\":\"\"}', '{\"ru\":\"\",\"ro\":\"\",\"en\":\"\"}',
                        '{\"ru\":\"\",\"ro\":\"\",\"en\":\"\"}', '{\"ru\":\"\",\"ro\":\"\",\"en\":\"\"}', '{\"ru\":\"\",\"ro\":\"\",\"en\":\"\"}', '{\"ru\":\"\",\"ro\":\"\",\"en\":\"\"}',
                        '{$p['email']}','{$p['mobile']}', 'ro', 'test', 3, 1, 0, 'blank-invoice-qr', 'default', 'MD', 'MD', '{$business_token}', '{$profile}', 'Oficiu')";
                    //                        {"ro":"","ru":"","en":""}         '{\"ru\":\"\",\"ro\":\"\",\"en\":\"\"}',
                    $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;
                    $r = Yii::$app->db->createCommand('SELECT LAST_INSERT_ID() AS business_id')->queryOne();
                    $business_id = $r['business_id'];

//echo "<pre>"; var_dump($query, $query_flag, $r, $business_id); echo "</pre>"; exit;

//  Создане пакета услуг  ======================================================================================================
                    $query = "SELECT user_productID, product_alias, tariff_alias FROM ut_user_product WHERE (user_alias = '{$p['email']}')";
                    $res = Yii::$app->db->createCommand($query)->queryOne();

//echo "<pre>"; var_dump($query, $res); echo "</pre>"; exit;

                    $user_product_id = $res['user_productID'];
//echo "<pre>"; var_dump($query, $res, $user_product_id); echo "</pre>"; exit;
                    // Установка тарифных ограничений
                    $tariff = Content::getTariffRecord($res['tariff_alias'], $res['product_alias'], Yii::$app->language);
                    $limit_invoice = $limit_customer = $limit_manager = $limit_business = $limit_pattern = '0';
//echo "<pre>"; var_dump($tariff); echo "</pre>"; exit;
                    foreach ($tariff as $v) {
                        switch ($v['tariff_pattern']) {
                            case 'email_limits':        $limit_invoice  = $v['tariff_limit']; break;
                            case 'clients_nos':         $limit_customer = $v['tariff_limit']; break;
                            case 'additional_staff':    $limit_manager  = $v['tariff_limit']; break;
                            case 'offices_nos':         $limit_business = $v['tariff_limit']; break;
                            case 'invoice_patterns':    $limit_pattern  = $v['tariff_limit']; break;
                        }
                    }
                    $query = "UPDATE ut_client SET
                        limit_invoice = '{$limit_invoice}',
                        limit_customer = '{$limit_customer}',
                        limit_manager = '{$limit_manager}',
                        limit_business = '{$limit_business}',
                        limit_pattern = '{$limit_pattern}'
                        WHERE (clientID = '{$client_id}')";
                    $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;
//echo "<pre>"; var_dump($query, $query_flag); echo "</pre>"; exit;
//echo "<pre>"; var_dump($tariff); echo "</pre>"; exit;

                    //  ==============  JSON  ===========================
                    $jd = (object) [];
                    $jd->profile = (object) [];
                    $jd->options = (object) [];
                    $jd->appointed = (object) [];
                    $jd->appointed->tariff = (object) [];
                    $jd->appointed->options = (object) [];

                    $tariff_obj = Tariff::getTariffObject($res['tariff_alias'], $res['product_alias'], 'demo');
                    $jd->profile = $tariff_obj;

                    $options = Tariff::getAllIntegrationsByTariff($res['tariff_alias']);
                    $jd->options = $options;

                    foreach ($options as $k => $v) {
                        if ($v->value > 0) {
                            $jd->appointed->options->{$k} = (object) [];
                            $jd->appointed->options->{$k}->amount = $v->value;
                        }
                    }

//echo "<pre>"; var_dump($jd); echo "</pre>"; //exit;
                    $po = json_encode($jd, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
//echo "<pre>"; var_dump($jd); echo "</pre>"; exit;

                    $query = "UPDATE ut_user_product SET user_alias = '".$p['alias']."', client_id = '{$cid}', product_status = 'demo',
                        date_start = DATE(NOW()), date_stop = DATE(DATE_ADD(NOW(), INTERVAL 14 DAY)),
                        profile_json = '{$po}'
                        WHERE (user_alias = '{$p['email']}')";
                    $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;

                    //  Создане пакета опций и интеграций для подразделения
//echo "<pre>"; var_dump($query, $res, false); echo "</pre>"; exit;
                    $query = "SELECT integration_alias FROM ut_integration WHERE FIND_IN_SET('".$p['tariff_alias']."', tariff_list) ORDER BY sort_order";
                    $res = Yii::$app->db->createCommand($query)->queryAll();
//echo "<pre>"; var_dump($p, $query, $res, $free_options, $money_options); echo "</pre>"; exit;

                    $free_options = $money_options = [];
                    foreach ($jd->options as $k => $v) {
                        if ($v->value > 1) array_push($money_options, $k);
                        else array_push($free_options, $k);
                    }
//echo "<pre>"; var_dump($p, $free_options, $money_options); echo "</pre>"; exit;

                    foreach ($res as $v) { // var_dump($v['integration_alias']);
                        $option_id = null;
                        $key_1 = $key_2 = $key_3 = $test_key_1 = $test_key_2 = $test_key_3 = $url_back =
                        $token_code = $token_access = $token_refresh = $test_token_code = $test_token_access = $test_token_refresh = '';

                        $token_code = $client_token; //md5(rand(1, 9999).time().$v['integration_alias']);
                        $token_access = $business_token; //md5(rand(1, 9999).time().$v['integration_alias']);
                        $token_refresh = md5(rand(1, 9999).time().'abc'.$v['integration_alias']);
                        $test_token_code = $test_client_token; //md5(rand(1, 9999).time().$v['integration_alias']);
                        $test_token_access = $test_business_token; //md5(rand(1, 9999).time().$v['integration_alias']);
                        $test_token_refresh = md5(rand(1, 9999).time().'def'.$v['integration_alias']);

                        switch ($v['integration_alias']) {
                            case 'amocrm':
                                $key_1 = 'https://diginetit.amocrm.ru';
                                $url_back = Yii::$app->params['api_url'].'code/amocrm';
                                $test_key_1 = Yii::$app->params['api_url'].'off/amocrm';
                            break;
                            case '1c_buh':
                                $key_1 = Yii::$app->params['api_url'];
                                $key_2 = '/facturare/v1/put/invoice_1c';
                            break;
                            case 'invoices_api':
                                $key_1 = Yii::$app->params['api_url'];
                                $key_2 = '/facturare/v1/put/invoice';
                            break;
                            case 'opencart':
                                $key_1 = Yii::$app->params['api_url'];
                                $key_2 = '/facturare/v1/put/invoice_oc';
                            break;
                            case 'oscommerce':
                                $key_1 = Yii::$app->params['api_url'];
                                $key_2 = '/facturare/v1/put/invoice_co';
                            break;
                            case 'prestashop':
                                $key_1 = Yii::$app->params['api_url'];
                                $key_2 = '/facturare/v1/put/invoice_ps';
                            break;
                            case 'woocommerce':
                                $key_1 = Yii::$app->params['api_url'];
                                $key_2 = '/facturare/v1/put/invoice_wc';
                            break;
                            default:
                                $key_1 = $key_2 = $key_3 = $test_key_1 = $test_key_2 = $test_key_3 =
                                $token_code = $token_access = $token_refresh = $test_token_code = $test_token_access = $test_token_refresh = '';
                            break;
                        }

                        //if (in_array($v['integration_alias'], $money_options)) {
/*
                            $query = "INSERT INTO ut_user_option (ParentID, user_product_id, integration_alias, is_active, for_money)
                            VALUES ('6f4e1cdf4a744813fc3b35164f7baa8d', '{$user_product_id}', '{$v['integration_alias']}', 1, 1)";
                            $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;
                            //var_dump($query); //echo "</pre>"; //exit;
                            $r = Yii::$app->db->createCommand('SELECT LAST_INSERT_ID() AS option_id')->queryOne();
*/
                            $option_id = 0; //$r['option_id'];
                            $for_money = (int)in_array($v['integration_alias'], $money_options);
                            $is_active = (int)($for_money || in_array($v['integration_alias'], $free_options));

                            $query = "INSERT INTO ut_api_keys (ParentID, client_id, business_id, product_id, option_id,
                                      integration_alias, key_1, key_2, key_3, test_key_1, test_key_2, test_key_3,
                                      token_code, token_access, token_refresh, test_token_code, test_token_access, test_token_refresh, url_back, for_money, is_active)
                                      VALUES ('4a4f8aae76389540655195601c7d8e01', '{$client_id}', '{$business_id}', '{$user_product_id}', '{$option_id}',
                                      '{$v['integration_alias']}', '{$key_1}', '{$key_2}', '{$key_3}', '{$test_key_1}', '{$test_key_2}', '{$test_key_3}',
                                      '{$token_code}', '{$token_access}', '{$token_refresh}', '{$test_token_code}', '{$test_token_access}', '{$test_token_refresh}',
                                      '{$url_back}', '{$for_money}', '{$is_active}')";
//echo "<pre>"; var_dump($query); echo "</pre>"; exit;
                            $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;

                        //}
                        //else if (!in_array($v['integration_alias'], $free_options)) {
/*
                            $query = "INSERT INTO ut_user_option (ParentID, user_product_id, integration_alias, is_active, for_money)
                            VALUES ('6f4e1cdf4a744813fc3b35164f7baa8d', '{$user_product_id}', '{$v['integration_alias']}', 1, 0)";
                            $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;
                            //var_dump($query); //echo "</pre>"; //exit;
                            $r = Yii::$app->db->createCommand('SELECT LAST_INSERT_ID() AS option_id')->queryOne();
*
                            $option_id = 0; //$r['option_id'];

                            $query = "INSERT INTO ut_api_keys (ParentID, client_id, business_id, product_id, option_id,
                                      integration_alias, key_1, key_2, key_3, test_key_1, test_key_2, test_key_3,
                                      token_code, token_access, token_refresh, test_token_code, test_token_access, test_token_refresh)
                                      VALUES ('4a4f8aae76389540655195601c7d8e01', '{$client_id}', '{$business_id}', '{$user_product_id}', '{$option_id}',
                                      '{$v['integration_alias']}', '{$key_1}', '{$key_2}', '{$key_3}', '{$test_key_1}', '{$test_key_2}', '{$test_key_3}',
                                      '{$token_code}', '{$token_access}', '{$token_refresh}', '{$test_token_code}', '{$test_token_access}', '{$test_token_refresh}')";
                            $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;
                            //var_dump($query); //echo "</pre>"; //exit;
                        //}
*/
                    }

//exit;

//echo "<pre>"; var_dump($p, $query, $res, false);
//echo "</pre>"; exit;



/*
                    $query = "INSERT INTO ut_user_option (ParentID, alias, caption, site_url, is_accepted, tariff_alias, frequency,
                        country_code, city, postal_index, address) VALUES (
                        '7e4d670a47694696718654a864dc375b', '".$p['alias']."', '".$p['caption']."', '".$p['site_url']."', 0, '".$p['tariff_alias']."', '".$p['frequency']."',
                        '".$p['country_code']."', '".$p['city']."', '".$p['postal_index']."', '".$p['address']."')";
                    $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;
*/

                    if ($query_flag)
                    {
                        Yii::$app->db->createCommand("COMMIT")->execute();
                        Yii::$app->session->setFlash('success', Terms::translate('changes_saved', 'alert'));
                    }
                    else
                    {
                        Yii::$app->db->createCommand("ROLLBACK")->execute();
                        Yii::$app->session->setFlash('info', Terms::translate('nothing_saved', 'alert'));
                    }
                    //            var_dump($query1, $query_flag); exit;
                break;

            }

        }

        if (!Yii::$app->user->isGuest)
        {
            //Yii::$app->view->params['help'] = Document::getContentHelp('client_edit');
//echo "<plaintext>"; var_dump(Yii::$app->view->params['help']);exit;
            $_user = User::findByEmail(\Yii::$app->user->identity->email);
            $context['user'] = $_user;
//echo "<plaintext>"; var_dump(\Yii::$app->user->identity->email, $_user->client_alias, $_user->client_id); exit;
            //if ($_user->client_id > 0)
            if ($_user->client_id > 0)
            {
                Yii::$app->params['client'] = $_user->client_id;
                $_client = Customer::findClientByID($_user->client_id);
                $context['client'] = (object)null;
                if (count($_client) == 0) return $this->render('client_edit', ['context' => $context, 'post' => null]);
//echo "<pre>"; var_dump($_user->client_id, $_client); echo "</pre>"; exit;

                $context['client'] = $_client[0];

//echo "<pre>"; var_dump(self::isJson($_client[0]->profile_json), $context['client']->profile_json); echo "</pre>"; //exit;

                $context['client']->profile_json =
                    (isset($_client[0]->profile_json) && $_client[0]->profile_json != '')
                    ? ( self::isJson($_client[0]->profile_json)
                        ? $_client[0]->profile_json
                        : json_encode($_client[0]->profile_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT))

                    : '{"global_registration":"1","idno":"","no_tva":"1","tva":"","global_bank":"1","bank_name":"","bank_address":"","mdl_account":"","bank_code":"","global_juridical":"1","juridical_country_code":"MD","juridical_city":"","juridical_address":"","juridical_postal_index":"","global_contact":"1","country_code":"MD","city":"","address":"","postal_index":"","tva_rate":"20","tva_calc":"inner","invoice_pattern":"blank-invoice-qr","preferred_language":"ru"}';

                    $_business = Customer::findBusinessesByClientID($_user->client_id);

//echo "<pre>"; var_dump($context['client']->profile_json); echo "</pre>"; exit;

                if (count($_business) > 0)
                {
                    $context['selected_business'] = 0;
                    $context['business'] = $_business;
                }
//echo "<plaintext>"; var_dump($context);exit;
                return $this->render('client_edit', ['context' => $context, 'help' => isset($help) ? $help : null]);
            }
            else
            {
                if ( ($product != '') && ($frequency != '') ) {
                    $ch = Profile::checkClaim('facturare', $product, $frequency);
                    Profile::setClaim($context['user']['email'], 'facturare;'.$product.';'.$frequency, $context['user']['username']);
                        return $this->redirect("/".Yii::$app->language."/client");
                }

                else
                {
                    $ch = Profile::checkClaim('facturare', 'free', 'monthly');
                    Profile::setClaim($context['user']['email'], 'facturare;free;monthly', $context['user']['username']);
                    //return $this->redirect("/".Yii::$app->language."/client_start");
                    //return $this->render('client_start', ['context' => $context]);
                    return $this->render('client_edit', ['context' => $context]);
                }

                if (isset($p)) {
                    return $this->render('client_start', ['context' => $context, 'post' => $p]);
                }
                else {
                    if ($context['user']['username'] == 'ask') {
                        return $this->render('client_start_show', ['context' => $context]);
                    } else {
                        //return $this->render('client_start', ['context' => $context]);
                        return $this->render('client_edit', ['context' => $context]);
                    }
                }

            }
        }
    }

    public function actionDashboard()
    {
        if (!\Yii::$app->user->isGuest) {
            $_user = User::findByEmail(\Yii::$app->user->identity->email);
            $context['user'] = $_user;
            if ($_user->client_id > 0)
            {
                Yii::$app->params['client'] = $_user->client_id;
                $_client = Customer::findClientByID($_user->client_id);
                $context['client'] = $_client[0];
                $_business = Customer::findBusinessesByClientID($_user->client_id);
                if (count($_business) > 0)
                {
                    $context['selected_business'] = 0;
                    $context['business'] = $_business;
                }
                Yii::$app->session->setFlash('success', Terms::translate('welcome', 'alert'));
                return $this->render('client_dashboard', ['context' => $context]);
            }
        }
    }

    public function actionReportfull()
    {
        $request_data = Yii::$app->request->get();
        if (!isset($request_data['page'])) $request_data['page'] = Yii::$app->session->get('page');
        if (!isset($request_data['limit'])) $request_data['limit'] = Yii::$app->session->get('limit');
        if (!isset($request_data['offset'])) $request_data['offset'] = Yii::$app->session->get('offset');
        if (!isset($request_data['post_flag'])) $request_data['post_flag'] = '0';

        if (!isset($request_data['sort'])) $request_data['sort'] = null;
        if (!isset($request_data['type'])) $request_data['type'] = null;
        //        if (!isset($request_data['post_flag'])) $request_data['post_flag'] = '0';

        $request_data['tab'] = 'ut_factura';
        $request_data['item'] = 'ut_factura';

        $reset_flag = false;
        //var_dump($request_data);//exit;
        //echo "<pre>"; var_dump($request_data); echo "</pre>";
        //	??? !!!

        if (empty($request_data['sort']) && $request_data['tab'] == 'ut_content')
        {
            $request_data['sort'] = 'OrderIndex';
            $request_data['type'] = 'ASC';
        }
        if (empty($request_data['sort']) && $request_data['tab'] == 'ut_document')
        {
            $request_data['sort'] = 'OrderIndex';
            $request_data['type'] = 'ASC';
        }

        if (empty($request_data['sort']) && $request_data['tab'] == 'ut_news_line')
        {
            $request_data['sort'] = 'moment';
            $request_data['type'] = 'DESC';
        }
        if (empty($request_data['sort']) && $request_data['tab'] == 'ut_offer')
        {
            $request_data['sort'] = 'moment';
            $request_data['type'] = 'DESC';
        }

        if ( (Yii::$app->session->get('table_name') != $request_data['tab']) || (Yii::$app->session->get('item') != $request_data['item']) )
        {
            Yii::$app->session->set('search_name',  null);
            Yii::$app->session->set('search_value',  null);
            Yii::$app->session->set('search_sort',  null);
            Yii::$app->session->set('search_sort_type',  null);
            $request_data['page'] = 1;
            $reset_flag = true;
        }
        else if	(
            (Yii::$app->session->get('search_sort') != $request_data['sort']) ||
            (Yii::$app->session->get('search_sort_type') != $request_data['type'])
            )
        {
            //        	Yii::$app->session->set('search_name',  null);
            //        	Yii::$app->session->set('search_value',  null);
            //        	Yii::$app->session->set('search_sort',  null);
            //        	Yii::$app->session->set('search_sort_type',  null);
            $request_data['page'] = 1;
        }

        Yii::$app->session->set('table_name',  $request_data['tab']);
        Yii::$app->session->set('item',  $request_data['item']);
        Yii::$app->session->set('search_sort',  $request_data['sort']);
        Yii::$app->session->set('search_sort_type',  $request_data['type']);

        $table = $this->findTable($request_data);
        $columns = SysTabledescription::find()
        ->where(['TableName' => $request_data['tab'], 'FieldVisible' => 1])
        ->orderBy('OrderFields')
        ->indexBy('FieldName')
        ->all();

        unset($columns['client_alias']); //$columns = array_values($columns);
//echo "<pre>"; var_dump($columns);exit;












        $default_order = '1';
        foreach ($columns as $v) { if ($v['FieldOrderDefault'] == 1) $default_order = $v['OrderFields']; }

        $rows = array_keys($columns);
//var_dump($rows);exit;

//      !!!!!!!!!!!!!!  Удаление алиавса клиента   !!!!!!!!!!!!!!!
        //unset($rows[0]);
        //$rows = array_values($rows);
        //var_dump($rows);exit;


        $key_name = SysTabledescription::getKeyName($table);
        $this->layout = 'main_datatable';
        $view = 'report_full';
        $data = [];
        try {
            $query = (new \yii\db\Query())
            ->select('*')
            ->from($request_data['tab'])
            ->where(['ParentID' => $request_data['item']]);

            (isset($request_data['sort'])) ? $query->orderBy($request_data['sort'] .' '. $request_data['type']) : $query->orderBy($key_name .  ' DESC');
            $post_data = (Yii::$app->request->post()) ? Yii::$app->request->post() : [];
            if (!isset($post_data['search_reset'])) $post_data['search_reset'] = null;
            if (!isset($post_data['search_name'])) $post_data['search_name'] = null;
            if (!isset($post_data['search_value'])) $post_data['search_value'] = null;



            if ( ($post_data['search_reset'] == '1') || $reset_flag)
            {
                Yii::$app->session->set('search_name',  null);
                Yii::$app->session->set('search_value',  null);
                $request_data['page'] = 1; //$pages->setPage(0);
            }
            else
            {
                if (!$post_data['search_name']) $post_data['search_name'] = Yii::$app->session->get('search_name'); else { $post_data['current_page'] = 1; $request_data['page'] = 1; /*$pages->setPage(0);*/ }
                if (!$post_data['search_value']) $post_data['search_value'] = Yii::$app->session->get('search_value'); else { $post_data['current_page'] = 1; $request_data['page'] = 1; /*$pages->setPage(0);*/ }
                if ($post_data['search_name'] && $post_data['search_value']) $query->where(['like', $post_data['search_name'], $post_data['search_value']]);
            }

            $countQuery = clone $query;
            $total =  intval($countQuery->count());
            $pages = new Pagination(['totalCount' => $total, 'defaultPageSize' => 10]);



            //var_dump($request_data['limit'], $pages->limit);
            //            $limit = (isset($request_data['limit'])) ? $request_data['limit'] : $pages->limit;
            $limit_flag = false;
            if (isset($request_data['limit']))
            {
                $limit = intval($request_data['limit']);
                //var_dump($limit, $pages->limit);
                //            	if ($limit != $pages->limit) { $request_data['page'] = 1; $pages->setPage(1); }
                if ($request_data['post_flag'] != '1') $limit_flag = $limit != $pages->limit;
        }
        else
        {
            $limit = $pages->limit;
        }

        //$limit = intval($limit);
        //$pages->forcePageParam = false;
        $pages->setPageSize($limit);
        $pages->setPage($request_data['page'] - 1);
        if ($limit_flag) { $request_data['page'] = 1; $pages->setPage(0); }
        //var_dump($query);
        $items = $query->offset($pages->offset)
        ->limit($limit)
        ->all();
        //var_dump($pages->offset, $limit, $items);
        /*
         if (isset($post_data['current_page'])) $current_page = $post_data['current_page'];
         else $current_page = (isset($request_data['page'])) ? $request_data['page'] : 1;
         */
        if (isset($request_data['page'])) $current_page = $request_data['page'];
        else $current_page = (isset($post_data['current_page'])) ? $post_data['current_page'] : 1;

        Yii::$app->session->set('page',  $request_data['page']);
        Yii::$app->session->set('limit',  $limit);
        Yii::$app->session->set('offset',  $pages->offset);

        $total_pages = intval($total / $limit) + 1;
        $pages->setPage($current_page-1);
        $data = [
            'columns' => $columns,
            'data' => $items,
            'rows' => $rows,
            'pages' => $pages,
            'current_page' => intval($current_page),
            'total_pages' => $total_pages,
            'table' => $table,
            'limit' => $limit,
            'total' => $total,
            'key_name' => $key_name,
            'search_name' => $post_data['search_name'],
            'search_value' => $post_data['search_value'],
            'default_order' => $default_order
        ];

        } catch (\Exception $e) {
            $view = 'index';
            Yii::$app->session->setFlash('danger', Yii::t('apl', 'table_doesn_exist'));
        }

        //var_dump($data);exit;
        return $this->render($view, $data);

    }

    public function actionIdno($idno)
    {
        Yii::$app->response->format = 'json';

        $idno = preg_replace("/[^0-9]/", '', trim($idno));

        $r = Yii::$app->db->createCommand("SELECT idno, caption, address, juridical, director FROM ut_base_idno WHERE (idno = '{$idno}')")->queryOne();

        if ($r) {
            $r['full_caption'] = trim($r['caption']);
            if ($r['juridical'] == 'Societate cu răspundere limitată') $r['juridical'] = 'Societatea cu răspundere limitată';
            $r['caption'] = trim(str_replace($r['juridical'], "", $r['caption']));
            $r['caption'] = trim(str_replace(mb_strtoupper($r['juridical']), "", $r['caption']));

            $d = explode(",", $r['director']);
            foreach ($d as $v) {
                if (strpos("***".$v, "[Administrator]")) $dir = trim(str_replace("[Administrator]", "", $v));
                else $dir = "";
                $n = null;
            }
            $r['director'] = $dir;
            $n = explode(" ", $dir);
            $r['last_name']  = isset($n[0]) && $n[0] != "" ? $n[0] : "";
            $r['first_name'] = isset($n[0]) && $n[1] != "" ? $n[1] : "";

//            $r['c1'] = $r['juridical'];
//            $r['c2'] = mb_strtoupper($r['juridical']);

        }

        $ret = ['data' => $r];
        return $ret;
    }

    public function actionMd5()
    {
        Yii::$app->response->format = 'json';
        $ret = ['md5' => substr(md5(time().'salt778899'), 0, 8)];
        return $ret;
    }

    public function actionAlias($alias)
    {
        Yii::$app->response->format = 'json';
        $r = Yii::$app->db->createCommand("SELECT alias FROM ut_client WHERE (alias = '{$alias}')")->queryOne();

        if (isset($r['alias']) && ($r['alias'] == $alias)) $ret = ['alias' => false];
        else $ret = ['alias' => true];
        return $ret;
    }

    public function actionBuyer($buyer)
    {
        Yii::$app->response->format = 'json';
        $buyer = preg_replace("/[^0-9]/", '', trim($buyer));
        $r = Yii::$app->db->createCommand("SELECT idno, tva, caption, first_name, last_name, contact_email, contact_phone FROM ut_customer
            WHERE (customerID = '{$buyer}')")->queryOne();
        $ret = ['data' => $r];
        return $ret;
    }
/**/
    public function actionBuyersearch($client_id = 0)
    {
        $r = Yii::$app->db->createCommand("
            SELECT customerID, idno, caption, first_name, last_name, contact_email FROM ut_customer
            WHERE (client_id = '{$client_id}')")->queryAll();
        //$ret = ['data' => $r];
        $ret = 'var buyers={';
        foreach ($r as $k => $v) {
            if ($v['caption'] == '') {
                $ret .= $v['customerID'].':"'.trim($v['first_name'].' '.$v['last_name']);
            }
            else {
                $ret .= $v['customerID'].':"'.$v['caption'];
            }
            $ret .= ' / '.$v['contact_email'];

            if ($v['idno'] != '') {
                $ret .= ' / '.$v['idno'];
            }
            $ret .= '",';
        }
        $ret .= '};';

        //Yii::$app->response->format = 'javascript';
//        $response = Yii::$app->response;
//        $response->format = Response::FORMAT_RAW;
//        $response->getHeaders()->set('Content-Type', 'text/javascript; charset=utf-8');
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->getHeaders()->set('Content-Type', 'text/javascript; charset=utf-8');
        return $ret;
    }


    public function actionBuyerajax($client_id = 0)
    {
        $search = Yii::$app->request->post('search', '');
        $r = Yii::$app->db->createCommand("
            SELECT customerID, idno, caption, first_name, last_name, contact_email, contact_phone, idno, tva, is_individual FROM ut_customer
            WHERE (client_id = '{$client_id}') AND
            ( (caption LIKE '%{$search}%') OR (first_name LIKE '%{$search}%') OR (last_name LIKE '%{$search}%') OR (contact_email LIKE '%{$search}%') ) ")->queryAll();

        $ra = [];
        foreach ($r as $k => $v) {
            $ret = '';
            if ($v['is_individual'] == '0') {
                if ($v['caption'] == '') {
                    $ret .= trim($v['first_name'].' '.$v['last_name']);
                }
                else {
                    $ret .= $v['caption'];
                }
// trim($v['first_name'].' '.$v['last_name']); // $ret;
            } else {
                $ret .= trim($v['first_name'].' '.$v['last_name']);
            }
            $rn = $ret;

            if ($v['contact_email'] != '') $ret .= ' / '.$v['contact_email'];

            $o = (object) null;
            $o->id = $v['customerID'];
            $o->name = $ret; // $v['caption'];
            $o->caption = $v['caption'];
            $o->email = $v['contact_email'];
            $o->phone = $v['contact_phone'];
            $o->idno = $v['idno'];
            $o->tva = $v['tva'];
            $o->flname = $rn;

            array_push($ra, $o);
        }
//        $ret = json_encode($ra, JSON_UNESCAPED_UNICODE);

        //Yii::$app->response->format = 'javascript';
        //        $response = Yii::$app->response;
        //        $response->format = Response::FORMAT_RAW;
        //        $response->getHeaders()->set('Content-Type', 'text/javascript; charset=utf-8');
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->getHeaders()->set('Content-Type', 'text/json; charset=utf-8');
        return $ra;
    }


/**/
    protected function findTable($request_data)
    {
        if (($model = SysTablelist::find()->where(['SysTableName' => $request_data['tab']])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    protected function _prepareData($data, $table)
    {
//echo "<pre>";        var_dump($data);
        foreach($data as &$item) {
            if (is_array($item)) {
                foreach($item as &$f) {
                    $f = str_replace("\\", "\\\\", $f);
                    $f = str_replace("'", "\'", $f);
                    $f = str_replace('"', '\"', $f);
                }
            }
        }
//var_dump($data);        //exit;
        foreach($data as &$item) {
            $item = (is_array($item))
            ? json_encode($item, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT)
            : (self::isJson($item)
                ? $item
                : addslashes($item)); // addslashes
        }
//var_dump($data); exit;

        foreach($_FILES as $key => $options) {
            $ext = strtolower(pathinfo($options['name'], PATHINFO_EXTENSION));
            $filename = md5(time().$table.$options['tmp_name']) . '.' . $ext;
            $folder = Yii::$app->params['business_image_url'] . $table . '/';
            self::checkFolders($folder);
            if (move_uploaded_file($options['tmp_name'], $folder . $filename)) {
                $data[$key] = $filename;
            }
        }
        return $data;
    }

    private static function checkFolders($folder)
    {
        if (!is_dir($folder)) {
            mkdir($folder);
        }
        if (!is_writable($folder)) {
            chmod($folder, 0777);
        }
        if (!is_dir($folder) || !is_writable($folder)) {
            throw new ErrorException('grant permissions to www-data for  folder' . $folder);
        }
    }

    private static function isJson($string) {
        return ((is_string($string) &&
            (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }

}
