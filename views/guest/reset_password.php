<?php $this->beginBlock('title') ?>
Reinitialiser mot de passe

<?php use yii\bootstrap\Html; ?>
<?php use yii\bootstrap\ActiveForm; ?>
<?php $this->endBlock()?>
<?php $this->beginBlock('style') ?>
    <style>

    </style>

<?php $this->endBlock()?>

<div class="site-reset-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Veuillez entrer une information de vous soit le nom d'utilisateur ou votre addresse Email : </p>

    <div class="row">
        <div class="col-lg-5">
        <?php $form = \yii\widgets\ActiveForm::begin([
            'method' => 'post',
            'action' => '@guest.resend_password',
            'scrollToError' => true,
            'errorCssClass' =>'text-secondary',
            'options' => ['enctype' => 'multipart/form-data','class' => 'col-md-8 col-12 form-block'],
        ]); ?>

                <?= $form->field($model,'username')->label('Nom d\'utilisateur ou Email ') ?>

                <div class="form-group">
                    <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?> 
        </div>
    </div>
</div>
