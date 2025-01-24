<?php $this->beginBlock('title') ?>
Nouvelle contribution
<?php $this->endBlock() ?>
<?php $this->beginBlock('style') ?>
<style>
</style>
<?php $this->endBlock() ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
    <?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= Yii::$app->session->getFlash('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php elseif (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= Yii::$app->session->getFlash('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>
        <div class="col-12 mb-3">
            <h3  class="text-center text-muted">Nouvelle contribution  <?=$model->member_id ?></h3>
        </div>
        <?php
        
        $form = \yii\widgets\ActiveForm::begin([
            'errorCssClass' => 'text-secondary',
            'options' => ['class' => 'col-md-8 col-12 white-block'],
            'action' => '@administrator.add_contribution',
            'method' => 'post'
        ]);

        $members =$model->member_id? \app\models\Contribution::find()->where(['help_id' =>$model->help_id,'member_id' => $model->member_id])->select('member_id')->column() : \app\models\Contribution::find()->where(['help_id' =>$model->help_id,'state' => false])->select('member_id')->column();
        $help = \app\models\Help::findOne(['id' => $model->help_id]);
        $montant = $help->unit_amount ;
        $echeance =$help->limit_date;
        $members = \app\models\Member::findAll(['id' => $members]);

        $items = [];

        foreach ($members as $member) {
            $user = $member->user();
            $items[$member->id] = $user->name." ".$user->first_name;
        }
        ?>

        <?= $form->field($model,'help_id')->hiddenInput()->label(false) ?>
        <?= $form->field($model,'member_id')->dropDownList($items,['required'=> 'required'])->label("Contributeur")?>
        <?= $form->field($model,"date")->input("date",['required'=> 'required','value'=>date('Y-m-d'),'min' => date('Y-m-d'),'max' => date('Y-m-d', strtotime('+1 year'))])->label("Date de contribution");?>
        <?= $form->field($model,'amount')->input("number" , ['required'=> 'required','value'=>$montant,'min' => 0])->label('montant') ?>

        <div class="form-group text-right">
            <button class="btn btn-primary" type="submit">Enregistrer</button>
        </div>
        <?php
        \yii\widgets\ActiveForm::end()
        ?>
    </div>
</div>
