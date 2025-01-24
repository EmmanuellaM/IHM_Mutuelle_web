<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Member;
use app\models\TontineType;

/* @var $this yii\web\View */
/* @var $model app\models\YourModel */
/* @var $form yii\widgets\ActiveForm */

// Assuming $member_id and $tontine_type_id are passed to the view
$member_id = Yii::$app->request->get('member_id');
$tontine_type_id = Yii::$app->request->get('tontine_type_id');

$member = Member::findOne($member_id);
$tontineType = TontineType::findOne($tontine_type_id);

if (!$member || !$tontineType) {
    throw new \yii\web\NotFoundHttpException("The requested member or tontine type does not exist.");
}

$user = $member->user;

?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">

        <!-- Flash messages -->
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>

        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-danger">
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php endif; ?>
        <!-- End flash messages -->

        <?php if (count(Member::find()->where(['active' => true])->all()) > 1): ?>
            <div class="col-12 mb-2">
                <h3 class="text-center text-muted">Inscription Tontine</h3>
            </div>
            <?php
            $form = ActiveForm::begin([
                'method' => 'post',
                'errorCssClass' => 'text-secondary',
                'action' => Yii::getAlias('@member.add_tontine'),  
                'options' => ['class' => 'col-md-8 col-12 white-block']
            ]);
            ?>
            
            <?= $form->field($model, "tontine_type_id")->hiddenInput(['value' => $tontineType->id])->label(false) ?>
            <div class="form-group">
                <label>Type de la tontine</label>
                <input type="text" class="form-control" value="<?= $tontineType->title . " - " . $tontineType->amount . ' XAF' ?>" readonly>
            </div>

            <?= $form->field($model, "member_id")->hiddenInput(['value' => $member->id])->label(false) ?>
            <div class="form-group">
                <label>Nom du membre concerné par la cotisation Mensuelle</label>
                <input type="text" class="form-control" value="<?= $user->name . " " . $user->first_name ?>" readonly>
            </div>

            <?= $form->field($model, "limit_date")->input("date", ['required' => 'required'])->label("Date limite de contribution") ?>

            <?= $form->field($model, "comments")->textarea(['required' => 'required'])->label("Commentaires à propos de la Tontine") ?>

            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
            <?php ActiveForm::end(); ?>
        <?php else: ?>
            <div class="col-12">
                <h3 class="text-center text-muted">Impossible de créer une aide avec moins de 2 membres actifs.</h3>
                <div class="text-center mt-2">
                    <a href="<?= Yii::getAlias("@administrator.new_member") ?>" class="btn btn-primary">Nouveau membre</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
