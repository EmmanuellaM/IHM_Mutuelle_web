<?php
?>
</style>
<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="<?= Yii::getAlias('@web/css/admin-styles.css') ?>">
<style>
</style>
<?php $this->endBlock() ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="section-title">Nouvelle agape</h1>
        </div>
    </div>
</div>

<div class="col-10">
    <div class="col-12">
        <div class="row justify-content-center">
                    <?php
                    $formAgape = \yii\widgets\ActiveForm::begin([
                        'method' => 'post',
                        'errorCssClass' => 'text-secondary',
                        'action' => '@administrator.nouvelle_agape',
                        'options' => ['class' => 'col-12 col-md-8 white-block']
                    ])
                    ?>

                    <?= $formAgape->field($model,'amount')->input("number",['required'=> 'required'])->label('entrez le montant de l\'agape') ?>


                    <div class="form-group text-right">
                        <button class="btn btn-primary" type="submit">Enregistrer</button>
                    </div>
                    <?php
                    \yii\widgets\ActiveForm::end();
                    ?>


                </div>
            </div>
        </div>
