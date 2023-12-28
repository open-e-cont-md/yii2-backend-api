<?php
namespace openecontmd\backend_api\controllers;

use Yii;
use openecontmd\backend_api\models\Client;
use openecontmd\backend_api\models\Customer;
use openecontmd\backend_api\models\SysTabledescription;
use openecontmd\backend_api\models\SysFormdescription;
use openecontmd\backend_api\models\SysTablelist;
use openecontmd\backend_api\models\Structure;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;
use openecontmd\backend_api\models\User;
use openecontmd\backend_api\models\ApiInvoice;
use openecontmd\backend_api\models\Terms;

use DateTime;
use Einvoicing\Invoice;
use Einvoicing\Presets;
use Einvoicing\Presets\Peppol;
use Einvoicing\Presets\CiusAtGov;
use Einvoicing\Presets\CiusRo;
use Einvoicing\Identifier;
use Einvoicing\Party;
use Einvoicing\InvoiceLine;
use Einvoicing\Writers\UblWriter;

use openecontmd\backend_api\models\SendMailSmtpClass;

/**
 * Site controller
 */
class InvoiceController extends BaseController
{
/*
    const CURRENCY_ISO4217_MDL = '498';
    const CURRENCY_ISO4217_EUR = '978';
    const CURRENCY_ISO4217_USD = '840';
    const CURRENCY_ISO4217_RUB = '643';
*/

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
                        'actions' => ['index', 'dashboard', 'list', 'view', 'sign', 'edit', 'send', 'delete', 'toarch', 'fromarch', 'tosign', 'toverify'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get', 'post'],
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

    public function actionDashboard()
    {
        $context = Customer::getContext();
//        Yii::$app->session->setFlash('success', 'Добро пожаловать!');
        return $this->render('dashboard', ['context' => $context]);
    }

    public function actionList($business = '')
    {
//        Yii::$app->view->params['help'] = Document::getContentHelp('invoice_edit');
        $context = Customer::getContext($business);
        $_user = User::findByEmail(\Yii::$app->user->identity->email);
        $context['user'] = $_user;
        $context['business_aliases'] = explode(',', $_user['business_alias_list']);
//        if (!in_array($business, $context['business_aliases'])) $business = '';
        if (!\Yii::$app->user->isGuest) {
            $bl = explode(',', $context['user']->business_alias_list);
            array_push($bl, 'all');
//            if (!in_array($business, $bl)) return $this->redirect("/".Yii::$app->language."/dashboard");
        } else return $this->redirect("/".Yii::$app->language."/logoff");

        if (count($context['business']) == 1) $context['selected_business'] = 0;
//echo "<pre>"; var_dump($business, $context); echo "</pre>"; exit;


        $request_data = ['tab' => 'ut_factura', 'item' => '65660f2fe278058f229cc52d8f18b53e'];
        $this->layout = 'main';
//echo "<pre>"; var_dump($context); exit;
        $view = 'list';
        $table = $this->findTable($request_data);
//var_dump($table); exit;
        if ($table) {
            $columns = SysTabledescription::find()
            ->where(['TableName' => $request_data['tab'], 'FieldVisible' => 1])
            ->orderBy('OrderFields')->indexBy('FieldName')->all();
            $rows = array_keys($columns);
            $key_name = SysTabledescription::getKeyName($table);
            $selectors = [];
            $dict = SysFormdescription::find()
            ->where(['TableName' => $request_data['tab']])
            ->andWhere(['isVisible' => '1'])
            ->andWhere(['like', 'SelectSrc', 'SELECT'])
            ->orderBy('FieldOrder')->indexBy('FieldName')->all();
            $lang = Yii::$app->language;
            if ($dict) {
                foreach ($dict as $v) {
                    //$selectors[$v->FieldName] = $v->SelectSrc;
                switch ($v->FieldName) {
                    case 'business_alias': $sql = "SELECT business_token AS value, caption FROM ut_business ORDER BY caption"; break;
                    default: $sql = $v->SelectSrc; break;
                }

                $res = Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_CLASS);
                    foreach ($res as $val) {
                        if (!json_decode($val->caption))
                            $selectors[$v->FieldName][$val->value] = $val->caption;
                        else
                            $selectors[$v->FieldName][$val->value] = json_decode($val->caption)->{Yii::$app->language};
                    }
                }
            }
        }
        else
        {
            $columns = $rows = $selectors = $table = $key_name = null;
        }

        $item = Structure::find()->where(['StructureID' => $request_data['item']])->one();
//echo "<pre>"; var_dump(Yii::$app->language, $selectors); exit;
        $data = [
            'item' => $item,
            'columns' => $columns,
            'rows' => $rows,
            'selectors' => $selectors,
            'table' => $table,
            'parent' => $request_data['item'],
            'key_name' => $key_name,
            'context' => $context,
            'business' => $business
        ];
//echo "<pre>"; var_dump($data); exit;
        return $this->render($view, $data);
    }


    public function actionView($key = '')
    {
//        Yii::$app->view->params['help'] = Document::getContentHelp('invoice_view');
//        $context = Customer::getContext($business);
          $context = [];
//echo "<pre>"; var_dump($key); exit;
        $_user = User::findByEmail(\Yii::$app->user->identity->email);
        $context['user'] = $_user;
        $context['business_aliases'] = explode(',', $_user['business_alias_list']);
//        if (!in_array($business, $context['business_aliases'])) $business = '';
/*
        if (!\Yii::$app->user->isGuest) {
            $bl = explode(',', $context['user']->business_alias_list);
            if (!in_array($business, $bl)) return $this->redirect("/".Yii::$app->language."/dashboard");
        } else return $this->redirect("/".Yii::$app->language."/logoff");
*/
        $invoice = Yii::$app->db->createCommand("SELECT * FROM ut_factura WHERE (inner_hash = '".addslashes($key)."')")->queryOne();
//var_dump($invoice['json_data']);exit;
        $factura = @json_decode($invoice['json_data'])->Document;
        $items = @json_decode($invoice['json_data'])->Document->SupplierInfo->Merchandises;

//        $status = json_decode(Invoice::getStatusCaption($invoice['status'])->{Yii::$app->language});
        $status = ApiInvoice::getStatusCaption($invoice['status'])['caption'];
        $status = json_decode($status)->{Yii::$app->language};
//        echo "<pre>"; var_dump(json_decode($status)->{Yii::$app->language}); echo "</pre>"; exit;
//var_dump($invoice['json_data'], $items);exit;
        //$this->layout = 'simple';

//var_dump($business, $context, $invoice);exit;

        return $this->render('invoice_view', [
            'context' => $context,
            'key' => $key,
            'items' => isset($items) ? $items : null,
            'result' => isset($invoice) ? $invoice : null,
            'factura' => $factura,
            'status' => $status,
            'business' => null
        ]);
    }

    public function actionSign($business = '', $key = '')
    {
//        Yii::$app->view->params['help'] = Document::getContentHelp('invoice_view');
        $context = Customer::getContext($business);
//echo "<pre>"; var_dump($business, $context); exit;
        $_user = User::findByEmail(\Yii::$app->user->identity->email);
        $context['user'] = $_user;
        $context['business_aliases'] = explode(',', $_user['business_alias_list']);
        //        if (!in_array($business, $context['business_aliases'])) $business = '';
        if (!\Yii::$app->user->isGuest) {
            $bl = explode(',', $context['user']->business_alias_list);
            if (!in_array($business, $bl)) return $this->redirect("/".Yii::$app->language."/dashboard");
        } else return $this->redirect("/".Yii::$app->language."/logoff");

        $invoice = Yii::$app->db->createCommand("SELECT * FROM ut_factura WHERE (inner_hash = '".addslashes($key)."')")->queryOne();
//var_dump($invoice['json_data']);exit;
        $factura = @json_decode($invoice['json_data'])->Document;
        $items = @json_decode($invoice['json_data'])->Document->SupplierInfo->Merchandises;

        //        $status = json_decode(ApiInvoice::getStatusCaption($invoice['status'])->{Yii::$app->language});
        $status = ApiInvoice::getStatusCaption($invoice['status'])['caption'];
        $status = json_decode($status)->{Yii::$app->language};
        //        echo "<pre>"; var_dump(json_decode($status)->{Yii::$app->language}); echo "</pre>"; exit;
        //var_dump($invoice['json_data'], $items);exit;
        //$this->layout = 'simple';

//var_dump($business, $context, $invoice);exit;

        return $this->render('invoice_sign', [
            'context' => $context,
            'key' => $key,
            'items' => isset($items) ? $items : null,
            'result' => isset($invoice) ? $invoice : null,
            'factura' => $factura,
            'status' => $status,
            'business' => $business
        ]);
    }

    public function actionEdit($key = '')
    {
        $business = '';
//        Yii::$app->view->params['help'] = Document::getContentHelp('invoice_edit');
        $pi = Yii::$app->request->pathInfo;
        $flag_copy = substr($pi, 0, 22) == 'invoice/outgoing/copy/';
//echo "<pre>"; var_dump($business, $key); echo "</pre>"; exit;
        $context = Customer::getContextByToken($business);
//echo "<pre>"; var_dump($context); echo "</pre>"; exit;
        $_user = User::findByEmail(\Yii::$app->user->identity->email);
        $context['user'] = $_user;
        $context['business_aliases'] = explode(',', $_user['business_alias_list']);
//        if (!in_array($business, $context['business_aliases'])) $business = '';

//echo "<pre>"; var_dump(\Yii::$app->user->isGuest); echo "</pre>"; exit;
/*
        if (!\Yii::$app->user->isGuest) {
            $bl = explode(',', $context['user']->business_alias_list);
            if (!in_array($business, $bl)) return $this->redirect("/".Yii::$app->language."/dashboard");
        } else return $this->redirect("/".Yii::$app->language."/logoff");
*/
        $sb = isset($context['selected_business']) ? $context['selected_business'] : null;
        $b = (isset($sb) && isset($context['business'][$sb])) ? $context['business'][$sb] : null;

//echo "<pre>"; var_dump($b); echo "</pre>"; exit;

        $b['profile'] = self::inheritProfile(json_decode($b['profile_json']), $context);
        //$b = Client::findBusinessesByToken($business);
//echo "<pre>"; var_dump($business, $b); echo "</pre>"; exit;
//echo "<pre>"; var_dump($business, $context, $b); echo "</pre>"; exit;
        $mode = (Yii::$app->request->post('mode')) ? Yii::$app->request->post('mode') : '';
//echo "<plaintext>"; var_dump($mode); exit;

        if (!empty(Yii::$app->request->post()))
        {
//echo "<plaintext>"; var_dump(Yii::$app->request->post());exit;
            $p = self::_prepareData(Yii::$app->request->post(), 'ut_factura');
//echo "<plaintext>"; var_dump($p, $b);exit;
            $p['remark'] = str_replace(["\n", "\r", "\n\r", "\r\n"], "", nl2br(trim(str_replace(["<br />", "<br>"], "\n", $p['remark'])), false));
            $p['topic'] = str_replace(["\n", "\r", "\n\r", "\r\n"], "", nl2br(trim(str_replace(["<br />", "<br>"], "\n", $p['topic'])), false));  //var_dump($p['remark']);
            $p['amount'] = str_replace(" ", "", $p['amount']);

//            $c = Customer::findCustomer($p['buyer_id'], $key);
            $c = Client::findCustomerByID($p['buyer_id']);
//echo "<pre>"; var_dump($c); echo "</pre>"; exit;


            $tva_calc = in_array($p['invoice']['tva_calc'], ['over', 'inner']) ? $p['invoice']['tva_calc'] : 'none';
            if ($p['invoice']['no_tva'] == '0') {
                if ($tva_calc == 'over') {
                    $tva_rate = $p['invoice']['tva_rate'];
                    $tva_amount = floatval(str_replace(' ', '', $p['tva_amount']));
                    $wtva_amount = floatval(str_replace(' ', '', $p['wtva_amount']));
                    $amount = floatval(str_replace(' ', '', $p['amount']));
                }
                else if ($tva_calc == 'inner') {
                    $tva_rate = $p['invoice']['tva_rate'];
                    $tva_amount = floatval(str_replace(' ', '', $p['tva_amount']));
                    $wtva_amount = floatval(str_replace(' ', '', $p['wtva_amount']));
                    $amount = floatval(str_replace(' ', '', $p['amount']));
                }
                else {
                    $tva_calc = 'none';
                    $tva_rate = 0;
                    $tva_amount = 0;
                    $wtva_amount = floatval($p['amount']);
                    $amount = floatval($p['amount']);
                }
            }
            else
            {
                $tva_calc = 'none';
                $tva_rate = 0;
                $tva_amount = 0;
                $wtva_amount = floatval($p['amount']);
                $amount = floatval($p['amount']);
            }

            switch ($p['mode']) {
                default:
                case 'update':
//echo "<plaintext>"; var_dump($p, $b);exit;
                    Yii::$app->db->createCommand("START TRANSACTION")->execute();
                    if ($p['outer_number'].'' == '')
                    {
                        Yii::$app->db->createCommand("ROLLBACK")->execute();
                        Yii::$app->session->setFlash('danger', 'Вы не можете создать инвойс без номера!');
                        break;
                    }
                    if ($p['buyer_id'].'' == '0')
                    {
                        Yii::$app->db->createCommand("ROLLBACK")->execute();
                        Yii::$app->session->setFlash('danger', 'Вы должны выбрать плательщика!');
                        break;
                    }
                    if (trim($p['buyer_email']).'' == '')
                    {
                        Yii::$app->db->createCommand("ROLLBACK")->execute();
                        Yii::$app->session->setFlash('danger', 'Вы должны указать e-mail получателя!');
                        break;
                    }

                    $jd = self::makeJSON($p, $b, $c, $context);
                    $xd = self::makeXML($p, $b, $c, $context);

                    $p['remark'] = $p['remark']; //addcslashes($p['remark'], "'\\");
                    $p['topic'] = $p['topic']; //addcslashes($p['topic'], "'\\");
//echo "<pre>"; var_dump($jd); echo "</pre>"; exit;

                        $query1 = "UPDATE ut_factura SET
status = '".$p['status']."',
outer_number = '".$p['outer_number']."',
buyer_caption = '".$p['buyer_caption']."',
remark = '".$p['remark']."',
topic = '".$p['topic']."',
buyer_name = '".$p['buyer_name']."',
issue_date = '".date("Y-m-d", strtotime($p['issue_date']))."',
due_on = '".date("Y-m-d", strtotime($p['due_on']))."',
buyer_id = '".$p['buyer_id']."',
buyer_idno = '".$p['buyer_idno']."',
buyer_tva = '".$p['buyer_tva']."',
buyer_phone = '".$p['buyer_phone']."',
buyer_email = '".$p['buyer_email']."',
currency = '".$p['currency']."',
amount = '{$amount}',
xml_source = '$xd',
json_data = '$jd',
tva_calc = '{$tva_calc}',
tva_rate = '{$tva_rate}',
wtva_amount = '{$wtva_amount}',
tva_amount = '{$tva_amount}',
invoice_pattern = '{$p['invoice']['invoice_pattern']}',
pattern_language = '{$p['invoice']['pattern_language']}'
WHERE (inner_hash = '".$p['inner_hash']."')";
//echo "<plaintext>"; var_dump($p, $query1); echo "</pre>"; exit;

/*
 *
delivery_date = '".date("Y-m-d", strtotime($p['delivery_date']))."',
paid_date = '".(isset($p['paid_date']) ? ((strtotime($p['paid_date']) > 0) ? date("Y-m-d", strtotime($p['paid_date'])) : '0000-00-00 00:00:00') : '0000-00-00 00:00:00')."',
*/

                        $query_flag = (Yii::$app->db->createCommand($query1)->execute() > 0) ? true : false;

                    //                    $query_flag = true;
                    if ($query_flag)
                    {
                        Yii::$app->db->createCommand("COMMIT")->execute();
                        Yii::$app->session->setFlash('success', Terms::translate('changes_saved', 'alert'));
                    }
                    else
                    {
                        Yii::$app->db->createCommand("ROLLBACK")->execute();
                        Yii::$app->session->setFlash('danger', Terms::translate('nothing_saved', 'alert'));
                    }
                break;

                case 'stay_create':
                case 'create':
                    Yii::$app->db->createCommand("START TRANSACTION")->execute();
                    if ($p['outer_number'].'' == '')
                    {
                        Yii::$app->db->createCommand("ROLLBACK")->execute();
                        Yii::$app->session->setFlash('danger', 'Вы не можете создать инвойс без номера!');
                        break;
                    }
                    if ($p['buyer_id'].'' == '0')
                    {
                        Yii::$app->db->createCommand("ROLLBACK")->execute();
                        Yii::$app->session->setFlash('danger', 'Вы должны выбрать плательщика!');
                        break;
                    }
                    if (trim($p['buyer_email']).'' == '')
                    {
                        Yii::$app->db->createCommand("ROLLBACK")->execute();
                        Yii::$app->session->setFlash('danger', 'Вы должны указать e-mail получателя!');
                        break;
                    }

                    $jd = self::makeJSON($p, $b, $c, $context);
                    $xd = self::makeXML($p, $b, $c, $context);

                    $p['remark'] = $p['remark']; //addcslashes($p['remark'], "'\\");
                    $p['topic'] = $p['topic']; //addcslashes($p['topic'], "'\\");
//echo "<pre>"; var_dump($b, $jd); echo "</pre>"; exit;
//echo "<pre>"; var_dump($p, $idx, $jd, json_encode($jd, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)); echo "</pre>"; exit;
//echo "<pre>"; var_dump($p); echo "</pre>"; exit;

                    $query = "INSERT INTO ut_factura (ParentID, client_id, business_alias, moment, status, inner_hash, outer_number,
                        buyer_caption, remark, topic,
                        issue_date, due_on, /* delivery_date, paid_date,*/ buyer_id, buyer_idno, currency, amount,
                        buyer_tva, buyer_phone, buyer_email, buyer_name, xml_source, json_data, tva_calc, tva_rate, wtva_amount, tva_amount,
                        invoice_pattern, pattern_language, source_create
                        ) VALUES (
                        '65660f2fe278058f229cc52d8f18b53e', '".$b['client_id']."', '".$p['business']."', NOW(), '".(isset($p['status']) ? $p['status'] : 'draft')."', '".$p['inner_hash']."',
                        '".$p['outer_number']."', '".$p['buyer_caption']."', '".$p['remark']."', '".$p['topic']."',
                        '".date("Y-m-d", strtotime($p['issue_date']))."', '".date("Y-m-d", strtotime($p['due_on']))."',
                        '".$p['buyer_id']."', '".$p['buyer_idno']."', '".$p['currency']."', '".str_replace(' ', '', $p['amount'])."',
                        '".$p['buyer_tva']."', '".$p['buyer_phone']."', '".$p['buyer_email']."', '".$p['buyer_name']."', '$xd', '$jd',
                        '{$tva_calc}', '{$tva_rate}', '{$wtva_amount}', '{$tva_amount}',
                        '{$p['invoice']['invoice_pattern']}', '{$p['invoice']['pattern_language']}', 'self')";
                        /*
                        '".date("Y-m-d", strtotime($p['delivery_date']))."', '0000-00-00 00:00:00',
*/
                    //$query = addcslashes($query, "'");
//echo "<plaintext>"; var_dump($p, $query); echo "</pre>"; exit;

                    $query_flag = (Yii::$app->db->createCommand($query)->execute()) ? true : false;
//exit;
                    if ($query_flag)
                    {
                        Yii::$app->db->createCommand("COMMIT")->execute();
                        Yii::$app->session->setFlash('success', Terms::translate('changes_saved', 'alert'));

                        return $this->redirect("/".Yii::$app->language."/invoice/outgoing/{$business}");

/*
                        $invoice = Invoice::findInvoice($p['inner_hash']);
                        $items = @json_decode($invoice[0]->json_data)->Document->SupplierInfo->Merchandises;
                        if (isset($items->Row)) $items = $items->Row;
//                        echo "<pre>"; var_dump(1, $invoice[0]->json_data, @json_decode($invoice[0]->json_data)->Document->SupplierInfo, $items); echo "</pre>"; exit;
                        $statuses = Invoice::getStatuses($invoice[0]->status);
//echo "<pre>"; var_dump($business, $invoice[0]); echo "</pre>"; exit;
                        return $this->render('invoice_edit', [
                            'key' => $p['inner_hash'],
                            'context' => $context,
                            'invoice' => $invoice,
                            'statuses' => $statuses,
                            'items' => isset($items) ? $items : [],
                            'business' => $business
                            //                'customer_list' => $customer_list
                        ]);
*/
                        break;
                    }
                    else
                    {
                        Yii::$app->db->createCommand("ROLLBACK")->execute();
                        Yii::$app->session->setFlash('danger', Terms::translate('nothing_saved', 'alert'));
                    }
                    //            var_dump($query1, $query_flag); exit;
                    break;

            }

        }

        if ($key != '') {

            $invoice = ApiInvoice::findInvoiceNumber($b['client_id'], $b['business_token'], $b['prefix']);
//echo "<plaintext>"; var_dump($invoice, $b); echo "</pre>"; exit;
            $seq_num = $invoice['seq_num'];
            $invoice = ApiInvoice::findInvoice($key);

//echo "<plaintext>"; var_dump($invoice, $b); echo "</pre>"; exit;

            $invoice[0]->seq_num = $seq_num;
            $customer = ApiInvoice::getCustomerById($context['client']->client_id, $invoice[0]->buyer_id);
//echo "<pre>"; var_dump($business, $invoice); echo "</pre>"; exit;
            $items = @json_decode($invoice[0]->json_data)->Document->SupplierInfo->Merchandises;
//            echo "<pre>"; var_dump($invoice[0]->json_data, json_decode($invoice[0]->json_data)); echo "</pre>"; exit;
            if (isset($items->Row)) $items = $items->Row;
            if ($flag_copy) $invoice[0]->status = 'draft';
            $statuses = ApiInvoice::getStatuses($invoice[0]->status);
            //$status = json_decode(Invoice::getStatusCaption($invoice[0]->status))->{Yii::$app->language};
            $status = ApiInvoice::getStatusCaption($invoice[0]->status);
            $status = json_decode($status['caption'])->{Yii::$app->language};
//echo "<pre>"; var_dump($status); echo "</pre>"; exit;
            if ( ($mode == 'stay') || ($mode == 'stay_create') )
            {
                if ($p['buyer_id'].'' == '0')
                    return $this->render('invoice_edit', [
                        'key' => $key,
                        'context' => $context,
                        'invoice' => $invoice,
                        'statuses' => $statuses,
                        'status' => $status,
                        'items' => isset($items) ? $items : [],
                        'business' => $business,
                        'customer' => $customer
                        //                'customer_list' => $customer_list
                    ]);
                else
                    return $this->redirect("/".Yii::$app->language."/invoice/outgoing/{$business}");
            }
            else
                return $this->render('invoice_edit', [
                    'key' => $key,
                    'context' => $context,
                    'invoice' => $invoice,
                    'statuses' => $statuses,
                    'status' => $status,
                    'items' => isset($items) ? $items : [],
                    'business' => $business,
                    'customer' => $customer
                    //                'customer_list' => $customer_list
                ]);
        }
        else
        {
            $invoice = ApiInvoice::findInvoiceNumber($b['client_id'], $b['business_token'], $b['prefix']);
            //if ( (!$invoice['seq_num']) $invoice['seq_num'] = $b['prefix'].$invoice['seq_num'];
            $statuses = ApiInvoice::getStatuses('draft');
//            $statuses = Invoice::getStatuses($invoice[0]->status);
//echo "<pre>"; var_dump($b); echo "</pre>"; exit;
            $business_prefix = ApiInvoice::getBusinessPrefix($context['client']->clientID, $b['businessID'], 'invoices_manual');
//echo "<pre>"; var_dump($context['client']->clientID, $b['businessID'], $business_prefix); echo "</pre>"; exit;
//            var_dump($business_prefix); exit;

            if (!isset($business_prefix) || !$business_prefix) $business_prefix = ['invoice_prefix' => 'api'];

            return $this->render('invoice_start', [
                'key' => $key,
                'context' => $context,
                'invoice' => $invoice,
                'statuses' => $statuses,
                'items' => isset($items) ? $items : [],
                'business' => $business,
                'business_prefix' => $business_prefix['invoice_prefix']
//                'customer_list' => $customer_list
            ]);
        }
    }

    /***
     * delete item
     * @return string
     */
    public function actionDelete($business, $key)
    {
        try{
            $query = (new \yii\db\Query())
            ->createCommand()
            ->delete('ut_factura', ['inner_hash' => $key])
            ->execute();
            $status = true;

        } catch (\Exception $e) {
            $status = false;
        }

        return $this->redirect("/".Yii::$app->language."/invoice/outgoing/{$business}");
    }

    protected function findTable($request_data)
    {
        if (($model = SysTablelist::find()->where(['SysTableName' => $request_data['tab']])->one()) !== null) {
            return $model;
        } else {
            return null;
            //throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public function inheritProfile($b, $c)
    {
//        echo "<pre>"; var_dump(isset($b->global_registration)); echo "</pre>"; //exit;

        if (!isset($b)) $b = (object) null;

        if (!isset($b->global_registration)) $b->global_registration = '1';
        if ($b->global_registration == '1') {
            $b->idno = $c['client']->profile->idno;
            $b->tva = $c['client']->profile->tva;
            $b->no_tva = $c['client']->profile->no_tva;
            $b->tva_rate = $c['client']->profile->tva_rate;
            $b->tva_calc = $c['client']->profile->tva_calc;
        }

        if (!isset($b->global_bank)) $b->global_bank = '1';
        if ($b->global_bank == '1') {
            $b->bank_name = $c['client']->profile->bank_name;
            $b->bank_address = $c['client']->profile->bank_address;
            $b->mdl_account = $c['client']->profile->mdl_account;
            $b->bank_code = $c['client']->profile->bank_code;
        }

        if (!isset($b->global_juridical)) $b->global_juridical = '1';
        if ($b->global_juridical == '1') {
            $b->juridical_country_code = $c['client']->profile->juridical_country_code;
            $b->juridical_city = $c['client']->profile->juridical_city;
            $b->juridical_address = $c['client']->profile->juridical_address;
            $b->juridical_postal_index = $c['client']->profile->juridical_postal_index;
        }

        if (!isset($b->global_contact)) $b->global_contact = '1';
        if ($b->global_contact == '1') {
            $b->country_code = $c['client']->profile->country_code;
            $b->city = $c['client']->profile->city;
            $b->address = $c['client']->profile->address;
            $b->postal_index = $c['client']->profile->postal_index;
        }

//        echo "<pre>"; var_dump($b); echo "</pre>"; exit;

        return $b;
    }

//  ==============  JSON  ===========================
    protected function makeJSON($p, $b, $c, $context)
    {
        $idx = [];
        foreach ($p as $k => $v) if (strpos('*'.$k, 'name_') > 0) array_push($idx, substr($k, 5));

        $jd = (object) [];
        $jd->Document = (object) [];
        $jd->Document->SupplierInfo = (object) [];
        $jd->Document->SupplierInfo->DeliveryDate = ''; //date("c", strtotime($p['delivery_date']));
        $jd->Document->SupplierInfo->Supplier = (object) [];
        $jd->Document->SupplierInfo->Supplier->IDNO = $b['idno'];
        $jd->Document->SupplierInfo->Supplier->TVA = $b['tva'];
        $jd->Document->SupplierInfo->Supplier->Title = $context['client']->caption.', '.json_decode($b['caption'])->{$b['preferred_language']};
        $jd->Document->SupplierInfo->Supplier->Name = $b['contact_firstname'].' '.$b['contact_lastname'];
        $jd->Document->SupplierInfo->Supplier->Email = $b['contact_email'];
        $jd->Document->SupplierInfo->Supplier->Phone = $b['contact_phone'];
        $jd->Document->SupplierInfo->Supplier->Address = $b['profile']->juridical_city.', '.$b['profile']->juridical_address.', '.$b['profile']->juridical_postal_index;
        $jd->Document->SupplierInfo->Supplier->TaxpayerType = 1;
        $jd->Document->SupplierInfo->Supplier->BankAccount = (object) [];
        $jd->Document->SupplierInfo->Supplier->BankAccount->BranchTitle = $b['profile']->bank_name; //.', '.$b['bank_address'];
        $jd->Document->SupplierInfo->Supplier->BankAccount->BranchCode = $b['profile']->bank_code;
        $jd->Document->SupplierInfo->Supplier->BankAccount->Account = $b['profile']->mdl_account;

        $jd->Document->SupplierInfo->Buyer = (object) [];
        $jd->Document->SupplierInfo->Buyer->IDNO = $c->idno;
        $jd->Document->SupplierInfo->Buyer->TVA = $c->tva;
        $jd->Document->SupplierInfo->Buyer->Title = $c->caption;
        $jd->Document->SupplierInfo->Buyer->Name = $c->first_name.' '.$c->last_name;
        $jd->Document->SupplierInfo->Buyer->Email = $p['buyer_email']; //$c->contact_email;
        $jd->Document->SupplierInfo->Buyer->Phone = $c->contact_phone;
        $jd->Document->SupplierInfo->Buyer->Address = /*$c->city.', '.$c->postal_index.', '.*/ $c->address;
        $jd->Document->SupplierInfo->Buyer->TaxpayerType = 1;
        $jd->Document->SupplierInfo->Buyer->BankAccount = (object) [];
        $jd->Document->SupplierInfo->Buyer->BankAccount->BranchTitle = '-';
        $jd->Document->SupplierInfo->Buyer->BankAccount->BranchCode = '-';
        $jd->Document->SupplierInfo->Buyer->BankAccount->Account = '-';
        $jd->Document->SupplierInfo->Buyer->Currency = $p['currency'];

        $jd->Document->SupplierInfo->Transporter = (object) [];
        $jd->Document->SupplierInfo->Transporter->IDNO = '-';
        $jd->Document->SupplierInfo->Transporter->Title = '-';
        $jd->Document->SupplierInfo->Transporter->Address = '-';
        $jd->Document->SupplierInfo->Transporter->TaxpayerType = 1;
        $jd->Document->SupplierInfo->Transporter->BankAccount = (object) [];
        $jd->Document->SupplierInfo->Transporter->BankAccount->BranchTitle = '-';
        $jd->Document->SupplierInfo->Transporter->BankAccount->BranchCode = '-';
        $jd->Document->SupplierInfo->Transporter->BankAccount->Account = '-';

        $jd->Document->SupplierInfo->AttachedDocuments = '';
        $jd->Document->SupplierInfo->Notes = '';
        $jd->Document->SupplierInfo->DelegateSeria = '';
        $jd->Document->SupplierInfo->DelegateNumber = '';
        $jd->Document->SupplierInfo->DelegateName = '';
        $jd->Document->SupplierInfo->DelegateDate = '';

        $jd->Document->SupplierInfo->VehicleLogbook = (object) [];
        $jd->Document->SupplierInfo->VehicleLogbook->IssueDate = '';
        $jd->Document->SupplierInfo->VehicleLogbook->Seria = '';
        $jd->Document->SupplierInfo->VehicleLogbook->Number = '';
        $jd->Document->SupplierInfo->LoadingPoint = '';
        $jd->Document->SupplierInfo->UnloadingPoint = '';
        $jd->Document->SupplierInfo->Redirections = '';

        $n = 0;
        $jd->Document->SupplierInfo->Merchandises = [];
        foreach ($idx as $v)
        {
            $n++;
            if ( !isset($p['name_'.$v]) || !isset($p['total_price_'.$v]) ) { continue; }
            $jd->Document->SupplierInfo->Merchandises[$n] = [];
            $jd->Document->SupplierInfo->Merchandises[$n]["Code"] = $n;
            $jd->Document->SupplierInfo->Merchandises[$n]["Name"] = $p['name_'.$v];
            $jd->Document->SupplierInfo->Merchandises[$n]["UnitPrice"] = str_replace(' ', '', $p['unit_price_'.$v]);
            $jd->Document->SupplierInfo->Merchandises[$n]["Quantity"] = intval($p['qnt_'.$v]);
            $jd->Document->SupplierInfo->Merchandises[$n]["UnitOfMeasure"] = $p['unit_'.$v];
            $jd->Document->SupplierInfo->Merchandises[$n]["TotalPrice"] = str_replace(' ', '', $p['total_price_'.$v]);
            $jd->Document->SupplierInfo->Merchandises[$n]["UnitPriceWithoutTVA"] = 0;
            $jd->Document->SupplierInfo->Merchandises[$n]["TotalPriceWithoutTVA"] = 0;
            $jd->Document->SupplierInfo->Merchandises[$n]["TVA"] = 0;
            $jd->Document->SupplierInfo->Merchandises[$n]["TotalTVA"] = 0;
            $jd->Document->SupplierInfo->Merchandises[$n]["OtherInfo"] = '';
            $jd->Document->SupplierInfo->Merchandises[$n]["PackageType"] = '';
            $jd->Document->SupplierInfo->Merchandises[$n]["NumberOfPlaces"] = 0;
            $jd->Document->SupplierInfo->Merchandises[$n]["GrossWeight"] = 0;
        }
        $jd->Document->SupplierInfo->Merchandises = (object)$jd->Document->SupplierInfo->Merchandises;
        $jd->Document->SupplierInfo->CreationMotiv = 1;
        $jd->Document->AdditionalInformation = (object) [];
        $jd->Document->AdditionalInformation->field = ''; //$p['topic'];
        $jd->Document->AdditionalInformation->remark = str_replace(["<br />", "<br>"], " ", $p['remark']);
        $jd->Document->AdditionalInformation->topic = str_replace(["<br />", "<br>"], " ", $p['topic']);
        $jd->Document->AdditionalInformation->total = $p['amount'];
        $jd->Document->AdditionalInformation->issue_date = date("c", strtotime($p['issue_date']));

        //echo "<pre>"; var_dump($jd); echo "</pre>"; exit;

        $jd =  addcslashes(json_encode($jd, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT), "'\\");
        return $jd;
    }

//  ==============  XML  ===========================

//  ==============  XML  ===========================
    protected function makeXML($p, $b, $c, $context)
    {
        $idx = [];
        foreach ($p as $k => $v) if (strpos('*'.$k, 'name_') > 0) array_push($idx, substr($k, 5));

        $w = new \XMLWriter();
        $w->openMemory();
        $w->setIndent(true);
        $w->startDocument('1.0','utf8');

        $w->startElement("Documents");
        $w->startElement("Document");

        $w->startElement("SupplierInfo");

        $w->startElement("DeliveryDate");
        //$w->text(date("c", strtotime($p['delivery_date'])));
        $w->text('');
        $w->endElement();   //  DeliveryDate

        $w->startElement("Supplier");
        $w->writeAttribute('IDNO', $b['idno']);
        $w->writeAttribute('Title', $context['client']->caption.', '.json_decode($b['caption'])->{$b['preferred_language']});
        $w->writeAttribute('Address', $b['juridical_address']);
        $w->writeAttribute('TaxpayerType', 1);
        $w->startElement("BankAccount");
        $w->writeAttribute('BranchTitle', $b['bank_name'].', '.$b['bank_address']);
        $w->writeAttribute('BranchCode', $b['bank_code']);
        $w->writeAttribute('Account', $b['mdl_account']);
        $w->endElement();   //  BankAccount
        $w->endElement();   //  Supplier

        $w->startElement("Buyer");
        $w->writeAttribute('IDNO', $c->idno);
        $w->writeAttribute('Title', $c->caption);
        $w->writeAttribute('Address', $c->address);
        $w->writeAttribute('TaxpayerType', 1);
        $w->startElement("BankAccount");
        $w->writeAttribute('BranchTitle', '-');
        $w->writeAttribute('BranchCode', '-');
        $w->writeAttribute('Account', '-');
        $w->endElement();   //  BankAccount
        $w->endElement();   //  Buyer

        $w->startElement("Transporter");
        $w->writeAttribute('IDNO', '-');
        $w->writeAttribute('Title', '-');
        $w->writeAttribute('Address', '-');
        $w->writeAttribute('TaxpayerType', 1);
        $w->startElement("BankAccount");
        $w->writeAttribute('BranchTitle', '-');
        $w->writeAttribute('BranchCode', '-');
        $w->writeAttribute('Account', '-');
        $w->endElement();   //  BankAccount
        $w->endElement();   //  Transporter

        $w->startElement("AttachedDocuments");
        $w->endElement();
        $w->startElement("Notes");
        $w->endElement();
        $w->startElement("DelegateSeria");
        $w->endElement();
        $w->startElement("DelegateNumber");
        $w->endElement();
        $w->startElement("DelegateName");
        $w->endElement();
        $w->startElement("DelegateDate");
        $w->endElement();
        $w->startElement("VehicleLogbook");
        $w->startElement("IssueDate");
        $w->endElement();
        $w->startElement("Seria");
        $w->endElement();
        $w->startElement("Number");
        $w->endElement();
        $w->endElement();   //  VehicleLogbook
        $w->startElement("LoadingPoint");
        $w->endElement();
        $w->startElement("UnloadingPoint");
        $w->endElement();
        $w->startElement("Redirections");
        $w->endElement();

        $w->startElement("Merchandises");

        $n = 0;
        foreach ($idx as $v)
        {
            $n++;
            if ( !isset($p['name_'.$v]) || !isset($p['total_price_'.$v]) ) { continue; }
            $w->startElement("Row");
            $w->writeAttribute('Code', $n);
            $w->writeAttribute('Name', $p['name_'.$v]);
            $w->writeAttribute('UnitPrice', str_replace(' ', '', $p['unit_price_'.$v]));
            $w->writeAttribute('Quantity', intval($p['qnt_'.$v]));
            $w->writeAttribute('UnitOfMeasure', $p['unit_'.$v]);
            $w->writeAttribute('TotalPrice', str_replace(' ', '', $p['total_price_'.$v]));
            $w->writeAttribute('UnitPriceWithoutTVA', 0);
            $w->writeAttribute('TotalPriceWithoutTVA', 0);
            $w->writeAttribute('TVA', 0);
            $w->writeAttribute('TotalTVA', 0);
            $w->writeAttribute('OtherInfo', '');
            $w->writeAttribute('PackageType', '');
            $w->writeAttribute('NumberOfPlaces', 0);
            $w->writeAttribute('GrossWeight', 0);
            $w->endElement();
        }
        $w->endElement();   //  Merchandises
        $w->startElement("CreationMotiv");
        $w->text(1);
        $w->endElement();   //  CreationMotiv

        $w->endElement();   //  SupplierInfo

        $w->startElement("AdditionalInformation");
        $w->startElement("field");
        //$w->text($p['topic']);
        $w->text('');
        $w->endElement();   //  field
        $w->startElement("remark");
        $w->text(str_replace(["<br />", "<br>"], " ", $p['remark']));
        $w->endElement();   //  remark
        $w->startElement("total");
        $w->text($p['amount']);
        $w->endElement();   //  total
        $w->endElement();   //  AdditionalInformation

        $w->endElement();   //  Document
        $w->endElement();   //  Documents
        //                    echo "<plaintext>".$w->outputMemory(true); exit;
        $xd = $w->outputMemory(true);

        $xd =  addcslashes($xd, "'\\");
        return $xd;
    }

    public function actionToarch($business, $key = null)
    {
        $lang = Yii::$app->language == 'ro' ? '' : '/'.Yii::$app->language;
        if (isset($key) && $key != '') {
            $r = Yii::$app->db->createCommand("UPDATE ut_factura SET `is_archived` = 1 WHERE (business_alias = '{$business}') AND (`inner_hash` = '{$key}')")->execute();
        }
        return $this->redirect([$lang.'/invoice/outgoing/'.$business]);
    }
    public function actionFromarch($business, $key = null)
    {
        $lang = Yii::$app->language == 'ro' ? '' : '/'.Yii::$app->language;
        if (isset($key) && $key != '') {
            $r = Yii::$app->db->createCommand("UPDATE ut_factura SET `is_archived` = 0 WHERE (business_alias = '{$business}') AND (`inner_hash` = '{$key}')")->execute();
        }
        return $this->redirect([$lang.'/invoice/outgoing/'.$business]);
    }

    public function actionTosign($business, $key = null)
    {
        $lang = Yii::$app->language == 'ro' ? '' : '/'.Yii::$app->language;
        $pdf_url = '';
        if (isset($key) && $key != '') {
            //$r = Yii::$app->db->createCommand("UPDATE ut_factura SET `is_archived` = 1 WHERE (business_alias = '{$business}') AND (`inner_hash` = '{$key}')")->execute();
            $invoice = Yii::$app->db->createCommand("SELECT * FROM ut_factura WHERE (inner_hash = '".addslashes($key)."')")->queryOne();
            $pdf_url = json_decode($invoice['doc_url'])->pdf_url;
            $pdf_url = base64_encode($pdf_url);
            $ret_url = base64_encode($lang.'/toverify/'.$business.'/'.$key);
//echo "<plaintext>"; var_dump($pdf_url); exit;
        }
        //return $this->redirect('https://mpass.diginet.md/demo1/p_return.php?file='.$pdf_url);
        Yii::$app->getResponse()->redirect('https://mpass.diginet.md/demo1/p_sign_request.php?file='.$pdf_url.'&url='.$ret_url)->send();
        return;
    }
    public function actionToverify($business, $key = null)
    {
        $lang = Yii::$app->language == 'ro' ? '' : '/'.Yii::$app->language;
        if (isset($key) && $key != '') {
            $r = Yii::$app->db->createCommand("UPDATE ut_factura SET `is_archived` = 1 WHERE (business_alias = '{$business}') AND (`inner_hash` = '{$key}')")->execute();
        }
        return $this->redirect(['/invoice/outgoing/sign/'.$business.'/'.$key]);
    }


    protected function _prepareData($data, $table)
    {
        //echo "<pre>";        var_dump($data);
/*
        foreach($data as &$item) {
            if (is_array($item)) {
                foreach($item as &$f) {
                    $f = str_replace("\\", "\\\\", $f);
                    $f = str_replace("'", "\'", $f);
                    $f = str_replace('"', '\"', $f);
                }
            }
        }
*/
        //var_dump($data);        //exit;
/*
        foreach($data as &$item) {
            $item = (is_array($item))
            ? json_encode($item, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT)
            : (self::isJson($item)
                ? $item
                : addslashes($item)); // addslashes
        }
*/
        foreach($data as &$item) {
            if (!is_array($item)) {
                $item = addslashes($item);
            }
        }
        //var_dump($data); exit;
        return $data;
    }

    private static function isJson($string) {
        return ((is_string($string) &&
            (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }

/*
    public function actionSend($business = '', $key = '')
    {
        $context = Customer::getContext($business);
        $_user = User::findByEmail(\Yii::$app->user->identity->email);
        $context['user'] = $_user;
        $context['business_aliases'] = explode(',', $_user['business_alias_list']);
        if (!in_array($business, $context['business_aliases'])) $business = '';

        $invoice = Yii::$app->db->createCommand("SELECT * FROM ut_factura WHERE (inner_hash = '".addslashes($key)."')")->queryOne();
        //var_dump($invoice['json_data']);exit;
        $factura = @json_decode($invoice['json_data'])->Document;
        $items = @json_decode($invoice['json_data'])->Document->SupplierInfo->Merchandises;

        //        $status = json_decode(Invoice::getStatusCaption($invoice['status'])->{Yii::$app->language});
        $status = Invoice::getStatusCaption($invoice['status'])['caption'];
        $status = json_decode($status)->{Yii::$app->language};
        //        echo "<pre>"; var_dump(json_decode($status)->{Yii::$app->language}); echo "</pre>"; exit;
        //var_dump($invoice['json_data'], $items);exit;
        //$this->layout = 'simple';
        return $this->render('invoice_send', [
            'context' => $context,
            'key' => $key,
            'items' => isset($items) ? $items : null,
            'result' => isset($invoice) ? $invoice : null,
            'factura' => $factura,
            'status' => $status,
            'business' => $business
        ]);
    }
*/

    public function actionSend($key = null)
    {

        $ret = [];
        $lang = Yii::$app->language == 'ro' ? '' : '/'.Yii::$app->language;
        if (isset($key) && $key != '') {
            $ret = ['result' => 'Server error!'];
        }
        $context = [];
        $_user = User::findByEmail(\Yii::$app->user->identity->email);
        $context['user'] = $_user;
        $context['business_aliases'] = explode(',', $_user['business_alias_list']);
        $invoice = Yii::$app->db->createCommand("SELECT * FROM ut_factura WHERE (inner_hash = '".addslashes($key)."')")->queryOne();
//var_dump($invoice['json_data']);exit;
        $factura = @json_decode($invoice['json_data'])->Document;
        $items = @json_decode($invoice['json_data'])->Document->SupplierInfo->Merchandises;
//var_dump($items);exit;
        //        $status = json_decode(Invoice::getStatusCaption($invoice['status'])->{Yii::$app->language});
        $status = ApiInvoice::getStatusCaption($invoice['status'])['caption'];
        $status = json_decode($status)->{Yii::$app->language};
        $cius = 'peppol';
//var_dump($invoice, $factura, $items);exit;

        //  Invoice Instance
        switch ($cius) {
            default:
            case 'peppol':
                $inv = new Invoice(Presets\Peppol::class);
                $cius_str = Peppol::getSpecification();
                break;
            case 'austria':
                $inv = new Invoice(Presets\CiusAtGov::class);
                $cius_str = CiusAtGov::getSpecification();
                break;
            case 'romania':
                $inv = new Invoice(Presets\CiusRo::class);
                $cius_str = CiusRo::getSpecification();
                break;
        }
        $inv->setCurrency('MDL');
        $inv->setVatCurrency('MDL');

        $inv->setNumber($invoice["outer_number"])
        ->setIssueDate(new DateTime(substr($invoice["issue_date"], 0, 10)))
        ->setDueDate(new DateTime(substr($invoice["due_on"], 0, 10)));

        //  Seller Instance
        $seller_identifier = new Identifier($factura->SupplierInfo->Supplier->IDNO, '0000'); // $factura->SupplierInfo->Supplier->IDNO
        $seller = new Party();
        $seller->setContactName($factura->SupplierInfo->Supplier->Name);
        $seller->setContactEmail($factura->SupplierInfo->Supplier->Email);
        $seller->setElectronicAddress( $seller_identifier )
        ->setCompanyId(new Identifier($factura->SupplierInfo->Supplier->IDNO, '0000'))
        ->setName($factura->SupplierInfo->Supplier->Title)
        ->setTradingName($factura->SupplierInfo->Supplier->Title)
        ->setVatNumber($factura->SupplierInfo->Supplier->TVA)
        ->setAddress([$factura->SupplierInfo->Supplier->Address])
        ->setCity('Chisinau')
        ->setCountry('MD');
        $inv->setSeller($seller);

        //  Buyer Instance
        $buyer_identifier = new Identifier($factura->SupplierInfo->Buyer->IDNO, '0000');
        $buyer = new Party();
        $buyer->setElectronicAddress( $buyer_identifier )
        ->setName($factura->SupplierInfo->Buyer->Name)
        ->setCountry('MD');
        $inv->setBuyer($buyer);

        foreach ($items as $k => $v) {
            $line = new InvoiceLine();
            $line->setName($v->Name)
            ->setPrice($v->UnitPrice)
            ->setVatRate($v->TVA)
            ->setUnit('PCS')
            ->setQuantity($v->Quantity);
            $inv->addLine($line);
        }
/*
        // 4 items priced at €40/unit + 16% VAT
        $firstLine = new InvoiceLine();
        $firstLine->setName('Product Name')
        ->setPrice(40)
        ->setVatRate(16)
        ->setQuantity(4);
        $inv->addLine($firstLine);

        // 27 items price at €10 per 5 units + 4% VAT
        $secondLine = new InvoiceLine();
        $secondLine->setName('Line #2')
        ->setDescription('The description for the second line')
        ->setPrice(10, 5)
        ->setQuantity(27)
        ->setVatRate(4);
        $inv->addLine($secondLine);
*/
        // Close and output XML document
        $ubl_writer = new UblWriter();
        $ubl_document = $ubl_writer->export($inv);

        $time = "(".date("d.m.Y_H.i", time()).")";
        $cdn_path = __DIR__.'/../../../../backend/web/documents/';
        $fname = $invoice["outer_number"].'_'.$time.'.xml';

        file_put_contents($cdn_path.$fname, $ubl_document);
        chmod($cdn_path.$fname, 0664);

//        var_dump($fname);exit;


//var_dump($inv);exit;


        //$ri = reset($ri);
        //echo "*****<plaintext>"; //var_dump($ri[0]); exit;

        $subject = 'OPEN.E-CONT.MD: Invoice '.$invoice["outer_number"].' (XML format)';
        $body  = 'XML Invoice: '.$invoice["outer_number"]."<br/>\n";
        $body .= 'Profile: PEPPOL'."<br/><br/>\n";
        $body .= 'See attached XML file: <b>'.$fname."</b>\n";
        $mail_to = 'oleg.dynga@gmail.com';

        $mail_smtp = 'ssl://mail.invoicing.md'; // $ri['key_1'];
        $mail_port = '465'; // $ri['token_code'];
        $mail_username = 'noreply@invoicing.md'; // $ri['key_2'];
        $mail_password = 'hB+D@!G-b}fX'; // $ri['key_3'];
        $mail_from = 'noreply@invoicing.md'; // $ri['token_refresh'];
        $mail_name = 'OPEN.E-CONT.MD'; // $ri['token_access'];
        $mailSMTP = new SendMailSmtpClass($mail_username, $mail_password, $mail_smtp, $mail_port, "utf-8");
        $mailSMTP->addFile($cdn_path.$fname);
        //          return $mailSMTP;

        $result =  $mailSMTP->send($mail_to, $subject, $body, [$mail_name, $mail_from]);



        return $result;
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->getHeaders()->set('Content-Type', 'text/json; charset=utf-8');
        return $ubl_document;
    }


}


/*

=== $invoice ===

array(41) {
  ["facturaID"]=>
  string(3) "650"
  ["ParentID"]=>
  string(32) "65660f2fe278058f229cc52d8f18b53e"
  ["client_alias"]=>
  string(7) "diginet"
  ["business_alias"]=>
  string(32) "2ea9685ae0420f057f98b0dbc2d55ad0"
  ["moment"]=>
  string(19) "2022-11-22 19:04:43"
  ["due_on"]=>
  string(19) "2022-11-23 00:00:00"
  ["issue_date"]=>
  string(19) "2022-11-22 17:04:42"
  ["delivery_date"]=>
  string(19) "2022-11-23 00:00:00"
  ["paid_date"]=>
  NULL
  ["outer_number"]=>
  string(10) "Delta-4366"
  ["inner_hash"]=>
  string(32) "50cd548b30ae3454fd63fb934d079dec"
  ["supplier_idno"]=>
  string(13) "1019600052449"
  ["buyer_idno"]=>
  string(14) "12345678901234"
  ["buyer_name"]=>
  string(14) "Alter Ego I.I."
  ["buyer_id"]=>
  string(1) "0"
  ["remark"]=>
  string(0) ""
  ["amount"]=>
  string(5) "82.00"
  ["currency"]=>
  string(3) "MDL"
  ["status"]=>
  string(5) "draft"
  ["xml_source"]=>
  string(1786) "<?xml version="1.0" encoding="utf8"?>
<Documents>
 <Document>
  <SupplierInfo>
   <DeliveryDate>2022-11-22 17:04:42</DeliveryDate>
   <Supplier IDNO="1019600052449" Title="Delta S.A." Address="Chisinau, str. Columna, 84" TaxpayerType="1">
    <BankAccount BranchTitle="Victoriabank, Chisinau, Centru" BranchCode="VB654543" Account="MD54654654400003458776"/>
   </Supplier>
   <Buyer IDNO="12345678901234" Title="Alter Ego I.I." Address="" TaxpayerType="1">
    <BankAccount BranchTitle="-" BranchCode="-" Account="-"/>
   </Buyer>
   <Transporter IDNO="-" Title="-" Address="-" TaxpayerType="1">
    <BankAccount BranchTitle="-" BranchCode="-" Account="-"/>
   </Transporter>
   <AttachedDocuments/>
   <Notes/>
   <DelegateSeria/>
   <DelegateNumber/>
   <DelegateName/>
   <DelegateDate/>
   <VehicleLogbook>
    <IssueDate/>
    <Seria/>
    <Number/>
   </VehicleLogbook>
   <LoadingPoint/>
   <UnloadingPoint/>
   <Redirections/>
   <Merchandises>
    <Row Code="1" Name="Set de 5 panze de ferastrau, Alluse, T144D" UnitPrice="30.00" Quantity="2" UnitOfMeasure="pcs" TotalPrice="60.00" UnitPriceWithoutTVA="0" TotalPriceWithoutTVA="0" TVA="0" TotalTVA="0" OtherInfo="" PackageType="" NumberOfPlaces="0" GrossWeight="0"/>
    <Row Code="2" Name="Fierastrau pentru metal cu maner plastic, TSK26" UnitPrice="22.00" Quantity="1" UnitOfMeasure="pcs" TotalPrice="22.00" UnitPriceWithoutTVA="0" TotalPriceWithoutTVA="0" TVA="0" TotalTVA="0" OtherInfo="" PackageType="" NumberOfPlaces="0" GrossWeight="0"/>
   </Merchandises>
   <CreationMotiv>1</CreationMotiv>
  </SupplierInfo>
  <AdditionalInformation>
   <field>Remark...</field>
   <remark>Remark...</remark>
   <total>82.00</total>
   <issue_date>2022-11-22 17:04:42</issue_date>
  </AdditionalInformation>
 </Document>
</Documents>
"
  ["json_data"]=>
  string(3661) "{
    "Document": {
        "SupplierInfo": {
            "DeliveryDate": "2022-11-22 17:04:42",
            "Supplier": {
                "IDNO": "1019600052449",
                "TVA": "0208851",
                "Title": "Delta S.A.",
                "Name": "Vladimir Macarov",
                "Email": "delta.sa@gmail.com",
                "Phone": "‎+373 79 760-762",
                "Address": "Chisinau, str. Columna, 84",
                "TaxpayerType": 1,
                "BankAccount": {
                    "BranchTitle": "Victoriabank, Chisinau, Centru",
                    "BranchCode": "VB654543",
                    "Account": "MD54654654400003458776"
                }
            },
            "Buyer": {
                "IDNO": "12345678901234",
                "TVA": "654321",
                "Title": "Alter Ego I.I.",
                "Name": "Alter Ego I.I.",
                "Email": "oleg.dynga@gmail.com",
                "Phone": "+37369102424",
                "Address": "Stefan cel Mare 180, Этаж 3",
                "TaxpayerType": 1,
                "BankAccount": {
                    "BranchTitle": "-",
                    "BranchCode": "-",
                    "Account": "-"
                },
                "Currency": "MDL"
            },
            "Transporter": {
                "IDNO": "-",
                "Title": "-",
                "Address": "-",
                "TaxpayerType": 1,
                "BankAccount": {
                    "BranchTitle": "-",
                    "BranchCode": "-",
                    "Account": "-"
                }
            },
            "AttachedDocuments": "",
            "Notes": "",
            "DelegateSeria": "",
            "DelegateNumber": "",
            "DelegateName": "",
            "DelegateDate": "",
            "VehicleLogbook": {
                "IssueDate": "",
                "Seria": "",
                "Number": ""
            },
            "LoadingPoint": "",
            "UnloadingPoint": "",
            "Redirections": "",
            "Merchandises": {
                "1": {
                    "Code": 1,
                    "SKU": "T144D",
                    "Name": "Set de 5 panze de ferastrau, Alluse, T144D",
                    "UnitPrice": "30.00",
                    "Quantity": 2,
                    "UnitOfMeasure": "pcs",
                    "TotalPrice": "60.00",
                    "UnitPriceWithoutTVA": 0,
                    "TotalPriceWithoutTVA": 0,
                    "TVA": 0,
                    "TotalTVA": 0,
                    "OtherInfo": "",
                    "PackageType": "",
                    "NumberOfPlaces": 0,
                    "GrossWeight": 0
                },
                "2": {
                    "Code": 2,
                    "SKU": "TSK26",
                    "Name": "Fierastrau pentru metal cu maner plastic, TSK26",
                    "UnitPrice": "22.00",
                    "Quantity": 1,
                    "UnitOfMeasure": "pcs",
                    "TotalPrice": "22.00",
                    "UnitPriceWithoutTVA": 0,
                    "TotalPriceWithoutTVA": 0,
                    "TVA": 0,
                    "TotalTVA": 0,
                    "OtherInfo": "",
                    "PackageType": "",
                    "NumberOfPlaces": 0,
                    "GrossWeight": 0
                }
            },
            "CreationMotiv": 1
        },
        "AdditionalInformation": {
            "field": "Remark...",
            "remark": "Remark...",
            "total": "82.00",
            "issue_date": "2022-11-22 17:04:42"
        }
    }
}"
  ["topic"]=>
  string(9) "Remark..."
  ["order_id"]=>
  NULL
  ["is_deal"]=>
  string(1) "0"
  ["outer_id"]=>
  string(10) "Delta-4366"
  ["outer_invoice_id"]=>
  string(10) "Delta-4366"
  ["json_extention"]=>
  string(2) "{}"
  ["buyer_phone"]=>
  NULL
  ["buyer_email"]=>
  NULL
  ["buyer_tva"]=>
  NULL
  ["doc_url"]=>
  NULL
  ["buyer_caption"]=>
  NULL
  ["tva_calc"]=>
  string(0) ""
  ["tva_rate"]=>
  NULL
  ["tva_amount"]=>
  string(4) "0.00"
  ["wtva_amount"]=>
  string(4) "0.00"
  ["invoice_pattern"]=>
  NULL
  ["pattern_language"]=>
  NULL
  ["source_create"]=>
  string(4) "self"
  ["is_archived"]=>
  string(1) "0"
  ["client_id"]=>
  string(9) "221043438"
}


=== $factura ===

object(stdClass)#196 (2) {
  ["SupplierInfo"]=>
  object(stdClass)#157 (16) {
    ["DeliveryDate"]=>
    string(19) "2022-11-22 17:04:42"
    ["Supplier"]=>
    object(stdClass)#186 (9) {
      ["IDNO"]=>
      string(13) "1019600052449"
      ["TVA"]=>
      string(7) "0208851"
      ["Title"]=>
      string(10) "Delta S.A."
      ["Name"]=>
      string(16) "Vladimir Macarov"
      ["Email"]=>
      string(18) "delta.sa@gmail.com"
      ["Phone"]=>
      string(18) "‎+373 79 760-762"
      ["Address"]=>
      string(26) "Chisinau, str. Columna, 84"
      ["TaxpayerType"]=>
      int(1)
      ["BankAccount"]=>
      object(stdClass)#187 (3) {
        ["BranchTitle"]=>
        string(30) "Victoriabank, Chisinau, Centru"
        ["BranchCode"]=>
        string(8) "VB654543"
        ["Account"]=>
        string(22) "MD54654654400003458776"
      }
    }
    ["Buyer"]=>
    object(stdClass)#188 (10) {
      ["IDNO"]=>
      string(14) "12345678901234"
      ["TVA"]=>
      string(6) "654321"
      ["Title"]=>
      string(14) "Alter Ego I.I."
      ["Name"]=>
      string(14) "Alter Ego I.I."
      ["Email"]=>
      string(20) "oleg.dynga@gmail.com"
      ["Phone"]=>
      string(12) "+37369102424"
      ["Address"]=>
      string(31) "Stefan cel Mare 180, Этаж 3"
      ["TaxpayerType"]=>
      int(1)
      ["BankAccount"]=>
      object(stdClass)#189 (3) {
        ["BranchTitle"]=>
        string(1) "-"
        ["BranchCode"]=>
        string(1) "-"
        ["Account"]=>
        string(1) "-"
      }
      ["Currency"]=>
      string(3) "MDL"
    }
    ["Transporter"]=>
    object(stdClass)#190 (5) {
      ["IDNO"]=>
      string(1) "-"
      ["Title"]=>
      string(1) "-"
      ["Address"]=>
      string(1) "-"
      ["TaxpayerType"]=>
      int(1)
      ["BankAccount"]=>
      object(stdClass)#191 (3) {
        ["BranchTitle"]=>
        string(1) "-"
        ["BranchCode"]=>
        string(1) "-"
        ["Account"]=>
        string(1) "-"
      }
    }
    ["AttachedDocuments"]=>
    string(0) ""
    ["Notes"]=>
    string(0) ""
    ["DelegateSeria"]=>
    string(0) ""
    ["DelegateNumber"]=>
    string(0) ""
    ["DelegateName"]=>
    string(0) ""
    ["DelegateDate"]=>
    string(0) ""
    ["VehicleLogbook"]=>
    object(stdClass)#192 (3) {
      ["IssueDate"]=>
      string(0) ""
      ["Seria"]=>
      string(0) ""
      ["Number"]=>
      string(0) ""
    }
    ["LoadingPoint"]=>
    string(0) ""
    ["UnloadingPoint"]=>
    string(0) ""
    ["Redirections"]=>
    string(0) ""
    ["Merchandises"]=>
    object(stdClass)#194 (2) {
      ["1"]=>
      object(stdClass)#193 (15) {
        ["Code"]=>
        int(1)
        ["SKU"]=>
        string(5) "T144D"
        ["Name"]=>
        string(42) "Set de 5 panze de ferastrau, Alluse, T144D"
        ["UnitPrice"]=>
        string(5) "30.00"
        ["Quantity"]=>
        int(2)
        ["UnitOfMeasure"]=>
        string(3) "pcs"
        ["TotalPrice"]=>
        string(5) "60.00"
        ["UnitPriceWithoutTVA"]=>
        int(0)
        ["TotalPriceWithoutTVA"]=>
        int(0)
        ["TVA"]=>
        int(0)
        ["TotalTVA"]=>
        int(0)
        ["OtherInfo"]=>
        string(0) ""
        ["PackageType"]=>
        string(0) ""
        ["NumberOfPlaces"]=>
        int(0)
        ["GrossWeight"]=>
        int(0)
      }
      ["2"]=>
      object(stdClass)#195 (15) {
        ["Code"]=>
        int(2)
        ["SKU"]=>
        string(5) "TSK26"
        ["Name"]=>
        string(47) "Fierastrau pentru metal cu maner plastic, TSK26"
        ["UnitPrice"]=>
        string(5) "22.00"
        ["Quantity"]=>
        int(1)
        ["UnitOfMeasure"]=>
        string(3) "pcs"
        ["TotalPrice"]=>
        string(5) "22.00"
        ["UnitPriceWithoutTVA"]=>
        int(0)
        ["TotalPriceWithoutTVA"]=>
        int(0)
        ["TVA"]=>
        int(0)
        ["TotalTVA"]=>
        int(0)
        ["OtherInfo"]=>
        string(0) ""
        ["PackageType"]=>
        string(0) ""
        ["NumberOfPlaces"]=>
        int(0)
        ["GrossWeight"]=>
        int(0)
      }
    }
    ["CreationMotiv"]=>
    int(1)
  }
  ["AdditionalInformation"]=>
  object(stdClass)#197 (4) {
    ["field"]=>
    string(9) "Remark..."
    ["remark"]=>
    string(9) "Remark..."
    ["total"]=>
    string(5) "82.00"
    ["issue_date"]=>
    string(19) "2022-11-22 17:04:42"
  }
}


=== $items ===

object(stdClass)#207 (2) {
  ["1"]=>
  object(stdClass)#206 (15) {
    ["Code"]=>
    int(1)
    ["SKU"]=>
    string(5) "T144D"
    ["Name"]=>
    string(42) "Set de 5 panze de ferastrau, Alluse, T144D"
    ["UnitPrice"]=>
    string(5) "30.00"
    ["Quantity"]=>
    int(2)
    ["UnitOfMeasure"]=>
    string(3) "pcs"
    ["TotalPrice"]=>
    string(5) "60.00"
    ["UnitPriceWithoutTVA"]=>
    int(0)
    ["TotalPriceWithoutTVA"]=>
    int(0)
    ["TVA"]=>
    int(0)
    ["TotalTVA"]=>
    int(0)
    ["OtherInfo"]=>
    string(0) ""
    ["PackageType"]=>
    string(0) ""
    ["NumberOfPlaces"]=>
    int(0)
    ["GrossWeight"]=>
    int(0)
  }
  ["2"]=>
  object(stdClass)#208 (15) {
    ["Code"]=>
    int(2)
    ["SKU"]=>
    string(5) "TSK26"
    ["Name"]=>
    string(47) "Fierastrau pentru metal cu maner plastic, TSK26"
    ["UnitPrice"]=>
    string(5) "22.00"
    ["Quantity"]=>
    int(1)
    ["UnitOfMeasure"]=>
    string(3) "pcs"
    ["TotalPrice"]=>
    string(5) "22.00"
    ["UnitPriceWithoutTVA"]=>
    int(0)
    ["TotalPriceWithoutTVA"]=>
    int(0)
    ["TVA"]=>
    int(0)
    ["TotalTVA"]=>
    int(0)
    ["OtherInfo"]=>
    string(0) ""
    ["PackageType"]=>
    string(0) ""
    ["NumberOfPlaces"]=>
    int(0)
    ["GrossWeight"]=>
    int(0)
  }
}



*/