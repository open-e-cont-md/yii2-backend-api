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
use openecontmd\backend_api\models\User;
use openecontmd\backend_api\models\ApiInvoice;
use openecontmd\backend_api\models\Terms;

/**
 * Site controller
 */
class IncomingController extends BaseController
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
                        'actions' => ['index', 'dashboard', 'list', 'view', 'edit'],
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
//        Yii::$app->view->params['help'] = Document::getContentHelp('incoming_edit');
        if ($business == 'all') {
            $context = Customer::getContext();
        } else {
            $context = Customer::getContext($business);
        }

        $_user = User::findByEmail(\Yii::$app->user->identity->email);
        $context['user'] = $_user;
//echo "<pre>"; var_dump($context); echo "</pre>"; exit;
/*
        if (!\Yii::$app->user->isGuest) {
            $bl = explode(',', $context['user']->business_alias_list);
            if (!in_array($business, $bl)) return $this->redirect("/".Yii::$app->language."/dashboard");
        } else return $this->redirect("/".Yii::$app->language."/logoff");

        if (count($context['business']) == 1) $context['selected_business'] = 0;
*/
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

            $rows = ['outer_number','issue_date',/*'supplier_caption','supplier_business','supplier_email',*/'amount','currency','status'];


            $key_name = SysTabledescription::getKeyName($table);
            $selectors = [];

            $_dict = SysFormdescription::find()
            ->where(['TableName' => $request_data['tab']])
            ->andWhere(['isVisible' => '1'])
            ->andWhere(['like', 'SelectSrc', 'SELECT'])
            ->orderBy('FieldOrder')->indexBy('FieldName')->all();

//echo "<pre>"; var_dump($dict); exit;

            $dict = [];
            $dict['status'] = $_dict['status'];
            $dict['currency'] = $_dict['currency'];

            if ($dict) {
                foreach ($dict as $v) {
                    //$selectors[$v->FieldName] = $v->SelectSrc;
                    $res = Yii::$app->db->createCommand($v->SelectSrc)->queryAll(\PDO::FETCH_CLASS);
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
//echo "<pre>"; var_dump($selectors, $rows, $columns); exit;
        return $this->render($view, $data);
    }

    public function actionView($key = '', $business = 'all')
    {
        //Yii::$app->view->params['help'] = Document::getContentHelp('income_view');
        $context = Customer::getContext();
        //echo "<pre>"; var_dump($business, $context); exit;
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

        //        $status = json_decode(ApiInvoice::getStatusCaption($invoice['status'])->{Yii::$app->language});
        $status = ApiInvoice::getStatusCaption($invoice['status'])['caption'];
        $status = json_decode($status)->{Yii::$app->language};
        //        echo "<pre>"; var_dump(json_decode($status)->{Yii::$app->language}); echo "</pre>"; exit;
        //var_dump($invoice['json_data'], $items);exit;
        //$this->layout = 'simple';


//echo "<pre>"; var_dump($invoice, $factura); exit;
        return $this->render('invoice_view', [
            'context' => $context,
            'key' => $key,
            'items' => isset($items) ? $items : null,
            'result' => isset($invoice) ? $invoice : null,
            'factura' => $factura,
            'status' => $status,
            'business' => $business,
        ]);
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

}
