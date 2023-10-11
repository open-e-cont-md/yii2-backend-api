<?php

namespace openecontmd\backend_api;

use yii\base\BootstrapInterface;
use yii\base\Theme;
use Yii;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        /*
         * Регистрация базового пути запроса
         * (вместо указания в файле config/main.php
         */

        $app->getRequest()->setBaseUrl('');
        $app->set('request', [
            'class' => 'klisl\languages\Request',
            'csrfParam' => '_csrf-backend',
            'cookieValidationKey' => '56xL4rFl91AMzQ97zmLXL5z60GzDc3BV'
        ]);
/*
        $app->set('i18n', [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceMessageTable' => 'sys_source_message',
                    'messageTable' => 'sys_message',
                    'forceTranslation' => true,
                ],
            ],
        ]);
*/
        $app->getUrlManager()->enablePrettyUrl = true;
        $app->getUrlManager()->showScriptName = false;
        $app->language = 'en';
        $app->sourceLanguage = 'en';

        /*
         * Регистрация модулей
         * (вместо указания в файле config/main.php
         */

        $app->setModule('languages', [
            'class' => 'klisl\languages\Module',
            'languages' => ['Eng' => 'en','Rom' => 'ro','Рус' => 'ru'],
            'default_language' => 'en',
            'show_default' => false
        ]);

        //$app->getModule('languages')->bootstrap($app);
        //echo "<pre>"; var_dump($app->getModule('languages')); exit;

        //$app->getModule('languages')->languages = ['en','ro','ru'];

/**
        $app->modules = [
            'languages' => [
//              'class' => 'common\modules\languages\Module',
                'class' => 'klisl\languages\Module',
                // Application languages
                'languages' => [
                    'Eng' => 'en',
                    'Rom' => 'ro',
                    'Рус' => 'ru'
                ],
                'default_language' => 'en', // Default language
                'show_default' => true, // true - Show default language in URL, false - No
            ],
        ];
/**/
//        $app->bootstrap = [ 'log', 'languages' ];


















        if ($app->id == 'app-frontend') {
            /*
             * Регистрация своих маршрутов
             * (вместо указания в файле config/main.php
             */
            $app->getUrlManager()->addRules([
                "test"            => "invoice/test",
                "test/<cius:\w+>" => "invoice/test",
            ], false);

            /*
             * Регистрация обработчика ответа
             * (вместо указания в файле config/main.php
             */
            $app->set('response', [
                'class' => 'yii\web\Response',
                'format' =>  \yii\web\Response::FORMAT_JSON,
                'on beforeSend' => function ($event) {
                    $response = $event->sender;
                    $response_data = $response->data;
                    if (is_array($response_data)) {
                        $response->data = [
                            'status' => $response->isSuccessful ? 'OK' : 'FAIL',
                        ];
                        if (Yii::$app->session->get('session_id')) {
                            $response->data['session_id'] = Yii::$app->session->get('session_id');
                        }
                        $response_data['name'] = 'Anonimous';
                        $response->data['data'] = $response_data;
                        $response->statusCode = 200;
                        $response->format = \yii\web\Response::FORMAT_JSON;
                    } else {
                        $response->data = $response_data;
                        $response->format = \yii\web\Response::FORMAT_HTML;
                    }
                }
            ]);
        }

        if ($app->id == 'app-backend') {

            $app->controllerNamespace = 'openecontmd\backend_api\controllers';

            /*
             * Определение пути к папке с шаблоном - backend
             * (вместо указания в файле config/main.php
             */
            $app->view->theme = new Theme([
                'pathMap' => [
                    '@app/views' => [ '@vendor/open-e-cont-md/yii2-backend-api/views' ]
                ]
            ]);

            $app->getUrlManager()->addRules([
//                '<module:[\w-]+>/<language:[\w+]+>/<submodule:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>' => '/v2/<submodule>/<controller>/<action>',
                '' => 'manager/dashboard',
                'dashboard' => 'manager/dashboard',

                '<controller:\w+>' => '<controller>/index',
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>'

            ], false);




        }
//        echo "<pre>"; print_r($app->params); echo "</pre>";

    }
}
