<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/mdb.min.css',
        'css/main.css',
        'css/admin-styles.css',
        ['css/print.css', 'media' => 'print'],
    ];

    public $js = [
        'js/mdb/mdb.min.js',
        'js/app.js',
        'js/main.js',
        'js/modal-fix.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}