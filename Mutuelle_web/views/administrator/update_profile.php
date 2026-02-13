<?php $this->beginBlock('title') ?>
Modifier mon profil
<?php $this->endBlock()?>
<?php $this->beginBlock('style')?>
<style>
    .profile-form-container {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
        padding: 2rem;
        transition: all 0.3s ease;
    }

    .form-title {
        color: #2196F3;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e0e0e0;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 0.8rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #2196F3;
        box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.15);
    }

    .form-label {
        color: #555;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(45deg, #2196F3, #00BCD4);
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 25px;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(33,150,243,0.3);
        background: linear-gradient(45deg, #1976D2, #0097A7);
    }

    .btn-danger {
        background: linear-gradient(45deg, #f44336, #ff5722);
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 25px;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(244,67,54,0.3);
        background: linear-gradient(45deg, #d32f2f, #f4511e);
    }

    .file-input-wrapper {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .file-input-wrapper input[type="file"] {
        padding: 0.8rem;
        background: #f8f9fa;
        border-radius: 8px;
        border: 2px dashed #2196F3;
    }

    .modal-content {
        border-radius: 15px;
        box-shadow: 0 0 30px rgba(0,0,0,0.1);
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border-top: none;
        padding: 1rem 2rem 2rem;
    }

    .password-change-btn {
        margin-top: 1rem;
    }
</style>
<?php $this->endBlock()?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="profile-form-container">
                <h2 class="form-title text-center">Modifier mes informations</h2>
                <?php $form1 = \yii\widgets\ActiveForm::begin([
                    'method' => 'post',
                    'action' => '@administrator.update_social_information',
                    'options' => [
                        'enctype' => 'multipart/form-data',
                        'class' => 'needs-validation',
                    ],
                    'errorCssClass' => 'text-danger'
                ])?>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form1->field($socialModel,'username')
                            ->input('text',['required'=>'required', 'class' => 'form-control'])
                            ->label("Nom d'utilisateur", ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form1->field($socialModel,'first_name')
                            ->input('text',['required' => 'required', 'class' => 'form-control'])
                            ->label('Prénom', ['class' => 'form-label']) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form1->field($socialModel,'name')
                            ->input('text',['required' => 'required', 'class' => 'form-control'])
                            ->label('Nom', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form1->field($socialModel,'tel')
                            ->input('tel',['required'=>'required', 'class' => 'form-control'])
                            ->label("Téléphone", ['class' => 'form-label']) ?>
                    </div>
                </div>

                <?= $form1->field($socialModel,'email')
                    ->input('email',['required'=> 'required', 'class' => 'form-control'])
                    ->label('Email', ['class' => 'form-label'])?>

                <?= $form1->field($socialModel,'address')
                    ->input('text', ['class' => 'form-control'])
                    ->label('Adresse', ['class' => 'form-label'])?>

                <div class="file-input-wrapper">
                    <?= $form1->field($socialModel,'avatar')
                        ->fileInput(['class' => 'form-control'])
                        ->label('Photo de profil', ['class' => 'form-label']);?>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>

                <?php \yii\widgets\ActiveForm::end()?>

                <div class="text-center password-change-btn">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#changePassword">
                        <i class="fas fa-key me-2"></i>Modifier le mot de passe
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="changePasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="form-title text-center mb-4">Modification du mot de passe</h4>
                <?php $form2 = \yii\widgets\ActiveForm::begin([
                    'method' => 'post',
                    'action' =>  '@administrator.update_password',
                    'options' => ['enctype' => 'multipart/form-data'],
                    'errorCssClass' => 'text-danger'
                ])?>

                <?= $form2->field($passwordModel,'password')
                    ->input('password',['required'=> 'required', 'class' => 'form-control'])
                    ->label('Ancien mot de passe', ['class' => 'form-label']) ?>

                <?= $form2->field($passwordModel,'new_password')
                    ->input('password',['required'=> 'required', 'class' => 'form-control'])
                    ->label('Nouveau mot de passe', ['class' => 'form-label']) ?>

                <?= $form2->field($passwordModel,'confirmation_new_password')
                    ->input('password',['required'=>'required', 'class' => 'form-control'])
                    ->label('Confirmation du nouveau mot de passe', ['class' => 'form-label']) ?>

                <div class="text-center mt-4">
                    <button type="button" class="btn btn-danger me-2" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
                
                <?php \yii\widgets\ActiveForm::end()?>
            </div>
        </div>
    </div>
</div>