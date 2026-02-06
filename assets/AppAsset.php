<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap/bootstrap.min.css',
        'css/all.min.css',
        'css/mdb.min.css',
        'css/main.css',
        ['css/print.css', 'media' => 'print'],
    ];

    public $js = [
        'js/bootstrap/popper.min.js',
        'js/bootstrap/bootstrap.min.js',
        'js/font_awesome/all.min.js',
        'js/mdb/mdb.min.js',
        'js/app.js',
        'js/main.js',
        'js/modal-fix.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}