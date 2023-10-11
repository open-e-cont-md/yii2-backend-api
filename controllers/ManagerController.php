<?php
//namespace cabinet\controllers;
namespace openecontmd\backend_api\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;


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
                        'actions' => ['index', 'dashboard', 'application', 'invite', 'edit'],
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

}
