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
            //'csrfParam' => '_csrf-backend',
            'csrfParam' => '_csrf',
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
                'table' => 'manager/table',
                'ajaxtable' => 'manager/ajaxtable',

                'client' => 'client/list',
                'client/edit/<key:\w+>' => 'client/edit',
                'outgoing' => 'invoice/list',
                'outgoing/view/<key:\w+>' => 'invoice/view',
                'outgoing/new' => 'invoice/edit',
                'outgoing/edit/<key:\w+>' => 'invoice/edit',
                'outgoing/send/<key:\w+>' => 'invoice/send',

                'vendor' => 'client/vendor',
                'incoming' => 'incoming/list',
                'incoming/view/<key:\w+>' => 'incoming/view',

                'customer' => 'manager/customer',
                'customer/auto/<client_id:[\d]+>' => 'customer/buyersearch',
                'customer/ajax/<client_id:[\d]+>' => 'customer/buyerajax',

                'message' => 'manager/message',
                'repository' => 'manager/repository',

                'manager' => 'manager/list',
                'manager/edit/<email:[\w\@\.\-]+>/<key:\w+>' => 'manager/edit',
                'setting' => 'manager/setting',
                'rights' => 'manager/rights',
                'icons' => 'manager/icons',

                '<controller:\w+>' => '<controller>/index',
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>'

            ], false);




        }
//        echo "<pre>"; print_r($app->params); echo "</pre>";

    }
}
