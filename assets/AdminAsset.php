<?php

namespace app\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/admin-styles.css',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
