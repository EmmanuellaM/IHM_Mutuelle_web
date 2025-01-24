
<?php $this->beginBlock('title') ?>
Profil
<?php $this->endBlock()?>
<?php $this->beginBlock('style')?>
<style>

</style>
<?php $this->endBlock()?>



<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php $form1 = \yii\widgets\ActiveForm::begin([
                'method' => 'post',
                'action' => '@member.enregistrer_modifier_profil',
                'options' => [
                    'enctype' => 'multipart/form-data',
                    'class' => 'white-block',
                ],
                'errorCssClass' => 'text-secondary'
            ])?>
            
            <div class="row">
                <div class="col-md-6">
                    <?= $form1->field($socialModel,'username')->input('text',['required'=>'required'])->label("Nom d'utilisateur") ?>
                </div>
                <div class="col-md-6">
                    <?= $form1->field($socialModel,'first_name')->input('text',['required' => 'required'])->label('Prénom') ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <?= $form1->field($socialModel,'name')->input('text',['required' => 'required'])->label('Nom') ?>
                </div>
                <div class="col-md-6">
                    <?= $form1->field($socialModel,'tel')->input('tel',['required'=>'required'])->label("Téléphone") ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <?= $form1->field($socialModel,'email')->input('email',['required'=> 'required'])->label('Email')?>
                </div>
                <div class="col-md-6">
                    <?= $form1->field($socialModel,'address')->input('address')->label('Adresse')?>
                </div>
                <div class="col-md-12">
                    <?= $form1->field($socialModel,'avatar')->fileInput();?>
                </div>
            </div>
            
            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
            
            <?php \yii\widgets\ActiveForm::end()?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group text-right">
                        <button class="btn-primary btn" data-toggle="modal" data-target="#changePassword" >Modifier mot de passe</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="changePasswordLabel" aria-hidden="true">
    <div class="col-3 mt-5 m-auto"  style="background-color: #fff;">
        <?php $form2 = \yii\widgets\ActiveForm::begin(['method' => 'post',
            // 'action' =>  '@administrator.update_password',
            'action' => '@member.modifiermotdepasse',
            'options' => ['enctype' => 'multipart/form-data','class' => 'modal-body'],
            'errorCssClass' => 'text-secondary'
        ])?>
        <?= $form2->field($passwordModel,'password')->input('password',['required'=> 'required'])->label('Ancien mot de passe') ?>
        <?= $form2->field($passwordModel,'new_password')->input('password',['required'=> 'required'])->label('Nouveau mot de passe') ?>
        <?= $form2->field($passwordModel,'confirmation_new_password')->input('password',['required'=>'required'])->label('Confirmation du nouveau mot de passe') ?>

        <div class="form-group text-right modal-footer">
            <a href="<?= Yii::getAlias("@member.modifier_profil") ?>" class="btn btn-danger btn-sm">Annuler</a>
            <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
        </div>
        <?php \yii\widgets\ActiveForm::end()?>
    </div>
</div>