<?php $this->beginBlock('title') ?>
Nouvel administrateur
<?php $this->endBlock()?>
<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="<?= Yii::getAlias('@web/css/admin-styles.css') ?>">
<?php $this->endBlock()?>

<div class="page-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-12">
                <div class="form-block">
                    <h2 class="section-title">Nouvel administrateur</h2>
                    
                    <?php $form = \yii\widgets\ActiveForm::begin([
                        'method' => 'post',
                        'action' => '@administrator.add_administrator',
                        'scrollToError' => true,
                        'errorCssClass' => 'text-danger',
                        'options' => ['enctype' => 'multipart/form-data'],
                    ]); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'username')
                                ->textInput(['class' => 'form-control', 'placeholder' => 'Entrez le nom d\'utilisateur'])
                                ->label('Nom d\'utilisateur') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'first_name')
                                ->textInput(['class' => 'form-control', 'placeholder' => 'Entrez le prénom'])
                                ->label('Prénom') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'name')
                                ->textInput(['class' => 'form-control', 'placeholder' => 'Entrez le nom'])
                                ->label('Nom') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'tel')
                                ->textInput(['class' => 'form-control', 'placeholder' => 'Entrez le numéro de téléphone'])
                                ->label('Téléphone') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'email')
                                ->textInput(['class' => 'form-control', 'type' => 'email', 'placeholder' => 'Entrez l\'adresse email'])
                                ->label('Email') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'address')
                                ->textInput(['class' => 'form-control', 'placeholder' => 'Entrez l\'adresse'])
                                ->label('Adresse') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'avatar')
                                ->fileInput(['class' => 'form-control'])
                                ->label('Photo de profil') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'password')
                                ->passwordInput(['class' => 'form-control', 'placeholder' => 'Entrez le mot de passe'])
                                ->label('Mot de passe') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'confirm_password')
                                ->passwordInput(['class' => 'form-control', 'placeholder' => 'Confirmez le mot de passe'])
                                ->label('Confirmer le mot de passe') ?>
                        </div>
                    </div>

                    <div class="form-group text-end mt-4">
                        <a href="<?= Yii::getAlias('@administrator.administrators') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </div>

                    <?php \yii\widgets\ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>