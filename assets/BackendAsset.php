<?php
namespace openecontmd\backend_api\assets;

use yii\web\AssetBundle;

class BackendAsset extends AssetBundle
{
    public $sourcePath = '@vendor/open-e-cont-md/yii2-backend-api';

    public $css = [
        'css/font-awesome.min.css'
    ];

    public $js = [
        'js/jquery.cookie.min.js',
//        'datatables.net-buttons/js/dataTables.buttons.min.js',
//        'js/bootstrap-switch.min.js'
    ];
/*
    public $depends = [
        'hail812\adminlte3\assets\BaseAsset',
        'hail812\adminlte3\assets\PluginAsset'
    ];
*/
}