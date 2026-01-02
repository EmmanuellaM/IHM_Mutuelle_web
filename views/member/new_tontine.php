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

$this->beginBlock('title') ?>
Nouvelle Tontine
<?php $this->endBlock()?>

<?php $this->beginBlock('style')?>
<style>
    .page-header {
        margin-bottom: 2rem;
        padding: 1.5rem 0;
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        border-radius: 0.5rem;
        text-align: center;
    }
    
    .white-block {
        padding: 2rem;
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #2193b0;
        box-shadow: 0 0 0 0.2rem rgba(33, 147, 176, 0.25);
    }

    .form-control[readonly] {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }

    .form-control-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
        min-width: 150px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(33, 147, 176, 0.3);
    }

    .alert {
        border: none;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: linear-gradient(135deg, #28a745, #5dd879);
        color: white;
    }

    .alert-danger {
        background: linear-gradient(135deg, #dc3545, #ff6b6b);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }

    .empty-state h3 {
        margin-bottom: 1.5rem;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .actions-group {
        text-align: center;
        margin-top: 2rem;
    }
</style>
<?php $this->endBlock()?>

<div class="container mt-5 mb-5">
    <div class="page-header">
        <h2>Inscription à une tontine</h2>
    </div>

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
        <?php
        $form = ActiveForm::begin([
            'method' => 'post',
            'errorCssClass' => 'text-secondary',
            'action' => Yii::getAlias('@member.add_tontine'),  
            'options' => ['class' => 'col-md-8 col-12 mx-auto white-block']
        ]);
        ?>
        
        <?= $form->field($model, "tontine_type_id")->hiddenInput(['value' => $tontineType->id])->label(false) ?>
        <div class="form-group">
            <label class="form-control-label">Type de la tontine</label>
            <input type="text" class="form-control" value="<?= $tontineType->title . " - " . $tontineType->amount . ' XAF' ?>" readonly>
        </div>

        <?= $form->field($model, "member_id")->hiddenInput(['value' => $member->id])->label(false) ?>
        <div class="form-group">
            <label class="form-control-label">Membre concerné par la cotisation mensuelle</label>
            <input type="text" class="form-control" value="<?= $user->name . " " . $user->first_name ?>" readonly>
        </div>

        <div class="form-group">
            <?= $form->field($model, "limit_date")
                ->input("date", [
                    'required' => 'required',
                    'class' => 'form-control',
                ])
                ->label("Date limite de contribution", ['class' => 'form-control-label']) 
            ?>
        </div>

        <div class="form-group">
            <?= $form->field($model, "comments")
                ->textarea([
                    'required' => 'required',
                    'class' => 'form-control',
                    'placeholder' => 'Ajoutez vos commentaires ici...'
                ])
                ->label("Commentaires à propos de la tontine", ['class' => 'form-control-label']) 
            ?>
        </div>

        <div class="actions-group">
            <button type="submit" class="btn btn-primary">
                Enregistrer l'inscription
            </button>
        </div>

        <?php ActiveForm::end(); ?>
    <?php else: ?>
        <div class="white-block empty-state">
            <h3>Impossible de créer une tontine avec moins de 2 membres en règle</h3>
            <a href="<?= Yii::getAlias("@administrator.new_member") ?>" class="btn btn-primary">
                Ajouter un nouveau membre
            </a>
        </div>
    <?php endif; ?>
</div>
