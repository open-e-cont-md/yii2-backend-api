<?php

namespace openecontmd\backend_api\controllers;

//use cabinet\models\User;
//use Yii;
use yii\web\Controller;
//use yii\web\ForbiddenHttpException;

class BaseController extends Controller
{
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
//            Yii::$app->language = Yii::$app->request->cookies->getValue('language');
/*
            if (null !==Yii::$app->request->cookies->getValue('language')) {
                Yii::$app->language = Yii::$app->request->cookies->getValue('language');
            }
            else
            {
                Yii::$app->language = 'ro';
                Yii::$app->response->cookies->add(new \yii\web\Cookie(['name' => 'language', 'value' => Yii::$app->language]));
            }
*/
//            $permissions = User::getPermission();
//            if(!in_array(Yii::$app->controller->id, $permissions) && Yii::$app->controller->id != 'site')
//                throw new ForbiddenHttpException('Доступ запрещен!');
            return true;
        }

        return false;
    }

}