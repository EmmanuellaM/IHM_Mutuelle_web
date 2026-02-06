<?php
use app\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
$this->title = "Mutuelle - ENSPY";
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title>404</title>
        <?php $this->head() ?>
        <style>
            body {
                background: linear-gradient(rgba(0, 0, 0, 0.71),rgba(0, 0, 0, 0.71)), url("/img/404.png");
                height: 100vh;
            }
        </style>
    </head>
    <body class="container-fluid">
    <?php $this->beginBody() ?>
    <div class="row h-100 align-items-center">
        <div class="col-12 text-center text-white">
            <h1 style="font-size: 4rem"><b>404</b></h1>
            <h2 style="font-size: 3.8rem">Page introuvable</h2>
        </div>
    </div>

    <?php $this->endBody() ?>
    </body>

    </html>
<?php $this->endPage(); ?>