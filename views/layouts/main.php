<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<!-- Loading Overlay -->
<div id="loading-overlay">
    <div class="loading-content">
        <span class="loading-text">Mutuelle Web</span>
        <span class="loading-dots">
            <span class="dot">.</span>
            <span class="dot">.</span>
            <span class="dot">.</span>
        </span>
    </div>
</div>

<div class="wrap">

    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
       'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            !Yii::$app->user->isGuest ? ['label' => 'Chat', 'url' => ['/chat/index']] : '',
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<script>
$(document).ready(function() {
    // Show overlay on form submission
    $('form').on('submit', function() {
        if (!$(this).hasClass('no-loader')) {
            $('#loading-overlay').css('display', 'flex').hide().fadeIn(300);
        }
    });

    // Show overlay on link clicks (excluding relative hashes, modals, and target blank)
    $('a').on('click', function() {
        var href = $(this).attr('href');
        var target = $(this).attr('target');
        
        if (href && href !== '#' && !href.startsWith('javascript:') && !href.startsWith('#') && 
            !$(this).data('toggle') && !$(this).data('dismiss') && target !== '_blank') {
            $('#loading-overlay').css('display', 'flex').hide().fadeIn(300);
        }
    });

    // Hide overlay if the page is shown from cache (back button)
    window.onpageshow = function(event) {
        if (event.persisted) {
            $('#loading-overlay').fadeOut(200);
        }
    };
});
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
