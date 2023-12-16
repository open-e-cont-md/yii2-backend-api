<?php
//namespace cabinet\controllers;
namespace openecontmd\backend_api\controllers;

use Yii;
//use openecontmd\backend_api\models\Client;
use openecontmd\backend_api\models\Customer;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use openecontmd\backend_api\models\SysTabledescription;
use openecontmd\backend_api\models\SysFormdescription;
use openecontmd\backend_api\models\SysTablelist;
use openecontmd\backend_api\models\Structure;
//use openecontmd\backend_api\models\Terms;
use openecontmd\backend_api\models\User;

/**
 * Site controller
 */
class ManagerController extends BaseController
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
                        'actions' => [
                            'index',
                            'dashboard',
                            'incoming',
                            'message',
                            'repository',
                            'table',
                            'ajaxtable',
                            'client',
                            'vendor',
                            'customer',
                            'rights',
                            'setting',
                            'icons',
                            'list',
                            'edit'
                        ],
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
        //Yii::$app->view->params['help'] = Document::getContentHelp('dashboard');
//echo "<plaintext>"; var_dump(\Yii::$app->user->identity->email);exit;

        //$_user = User::findByEmail(\Yii::$app->user->identity->email);
//echo "<plaintext>"; var_dump(111, $_user);exit;
        $context = null; //Customer::getContext();
//echo "<plaintext>"; var_dump(111, $context);exit;
        $_business = null; //Customer::findBusinessesByClientID($_user->client_id);
        $this->layout = 'main';

        $context['selected_business'] = 0;
            $context['business'] = $_business;
        //        Yii::$app->session->setFlash('success', 'Добро пожаловать!');
        return $this->render('dashboard', ['context' => $context]);
    }

    public function actionTable()
    {
        $context = null;
        $_business = null;
        $this->layout = 'main';
        $context['selected_business'] = 0;
        $context['business'] = $_business;
        return $this->render('table', ['context' => $context]);
    }

    public function actionAjaxtable()
    {
        $context = null;
        $_business = null;
        $this->layout = 'main';
        $context['selected_business'] = 0;
        $context['business'] = $_business;
        return $this->render('ajaxtable', ['context' => $context]);
    }

    public function actionClient()
    {
        $context = null;
        $_business = null;
        $this->layout = 'main';
        $context['selected_business'] = 0;
        $context['business'] = $_business;
        return $this->render('client', ['context' => $context]);
    }

    public function actionVendor()
    {
        $context = null;
        $_business = null;
        $this->layout = 'main';
        $context['selected_business'] = 0;
        $context['business'] = $_business;
        return $this->render('vendor', ['context' => $context]);
    }

    public function actionMessage()
    {
        $context = null;
        $_business = null;
        $this->layout = 'main';
        $context['selected_business'] = 0;
        $context['business'] = $_business;
        return $this->render('message', ['context' => $context]);
    }

    public function actionRepository()
    {
        $context = null;
        $_business = null;
        $this->layout = 'main';
        $context['selected_business'] = 0;
        $context['business'] = $_business;
        return $this->render('repository', ['context' => $context]);
    }

    public function actionCustomer()
    {
        $context = null;
        $_business = null;
        $this->layout = 'main';
        $context['selected_business'] = 0;
        $context['business'] = $_business;
        return $this->render('customer', ['context' => $context, 'model' => []]);
    }

    public function actionRights()
    {
        $context = null;
        $_business = null;
        $this->layout = 'main';
        $context['selected_business'] = 0;
        $context['business'] = $_business;
        return $this->render('rights', ['context' => $context, 'model' => []]);
    }
    public function actionSetting()
    {
        $context = null;
        $_business = null;
        $this->layout = 'main';
        $context['selected_business'] = 0;
        $context['business'] = $_business;
        return $this->render('settings', ['context' => $context, 'model' => []]);
    }
    public function actionIcons()
    {
        $context = null;
        $_business = null;
        $this->layout = 'main';
        $context['selected_business'] = 0;
        $context['business'] = $_business;
        return $this->render('icons', ['context' => $context, 'model' => []]);
    }

    public function actionEdit($email = null, $key = null)
    {
        $context = null;
        $_business = null;
        $view = 'manager_edit';
        $context = Customer::getContext();
        $_user = User::findByEmail($email);
        $context['user'] = $_user;
        $context['selected_business'] = 0;
        $context['business'] = $_business;
        $data = [
            'context' => $context,
            'email' => $email,
            'key' => $key
        ];
        return $this->render($view, $data);
    }

    public function actionList($customer_id = null)
    {
        //Yii::$app->view->params['help'] = Document::getContentHelp('customer');
        $this->layout = 'main';

        if (isset($customer_id)) {
            //echo "<pre>"; var_dump($customer_id); echo "</pre>"; exit;
        }

        $context = Customer::getContext();
        $_user = User::findByEmail(\Yii::$app->user->identity->email);
        $context['user'] = $_user;

        $request_data = ['tab' => 'ut_user', 'item' => '6c9598dba0bb7f8da03f8a8f12acf592'];
        $view = 'list';
        $table = $this->findTable($request_data);
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
            if ($dict) {
                foreach ($dict as $v) {
                    //$selectors[$v->FieldName] = $v->SelectSrc;
                    $res = Yii::$app->db->createCommand($v->SelectSrc)->queryAll(\PDO::FETCH_CLASS);
                    foreach ($res as $val) {
                        $selectors[$v->FieldName][$val->value] = $val->caption;
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
            //            'business' => $business
        ];
        //echo "<pre>"; var_dump($data); exit;
        return $this->render($view, $data);
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
