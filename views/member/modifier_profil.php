<?php $this->beginBlock('title') ?>
Modifier mon profil
<?php $this->endBlock()?>

<?php $this->beginBlock('style')?>
<style>
    .profile-container {
        padding: 2rem;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        min-height: 100vh;
    }

    .profile-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .form-section {
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #e9ecef;
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
        padding: 0.75rem 2rem;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #2e59d9;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
    }

    .btn-secondary {
        background-color: #e74a3b;
        border-color: #e74a3b;
        padding: 0.75rem 2rem;
        border-radius: 25px;
        font-weight: 600;
    }

    .btn-secondary:hover {
        background-color: #d0342c;
    }

    .password-modal {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        max-width: 400px;
        margin: 2rem auto;
    }

    .password-modal .form-group {
        margin-bottom: 1.5rem;
    }

    .password-modal .btn {
        margin: 0 0.5rem;
    }

    .avatar-upload {
        text-align: center;
        margin-bottom: 2rem;
    }

    .avatar-preview {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        margin: 1rem auto;
        border: 2px solid #4e73df;
        overflow: hidden;
    }

    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-input {
        margin-top: 1rem;
    }

    @media (max-width: 768px) {
        .profile-card {
            padding: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .btn-primary, .btn-secondary {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .password-modal {
            margin: 1rem;
        }
    }
</style>
<?php $this->endBlock()?>

<div class="profile-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="profile-card">
                    <h2 class="text-center mb-4">Modifier mon profil</h2>
                    
                    <?php $form1 = \yii\widgets\ActiveForm::begin([
                        'method' => 'post',
                        'action' => '@member.enregistrer_modifier_profil',
                        'options' => [
                            'enctype' => 'multipart/form-data',
                            'class' => 'needs-validation',
                            'novalidate' => true
                        ],
                        'errorCssClass' => 'is-invalid'
                    ])?>

                    <div class="avatar-upload">
                        <div class="avatar-preview">
                            <img src="<?= \app\managers\FileManager::loadAvatar($this->params['user'], "512") ?>" alt="">
                        </div>
                        <?= $form1->field($socialModel, 'avatar')->fileInput(['class' => 'form-control avatar-input'])->label('Changer la photo de profil') ?>
                    </div>

                    <div class="form-section">
                        <h4 class="mb-3">Informations personnelles</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form1->field($socialModel, 'username')->input('text', ['required' => 'required', 'class' => 'form-control'])->label('Nom d\'utilisateur') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form1->field($socialModel, 'first_name')->input('text', ['required' => 'required', 'class' => 'form-control'])->label('Prénom') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form1->field($socialModel, 'name')->input('text', ['required' => 'required', 'class' => 'form-control'])->label('Nom') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form1->field($socialModel, 'tel')->input('tel', ['required' => 'required', 'class' => 'form-control'])->label('Téléphone') ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form1->field($socialModel, 'email')->input('email', ['required' => 'required', 'class' => 'form-control'])->label('Email') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form1->field($socialModel, 'address')->input('text', ['class' => 'form-control'])->label('Adresse') ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>

                    <?php \yii\widgets\ActiveForm::end()?>

                    <div class="form-group text-center">
                        <button class="btn btn-secondary" data-toggle="modal" data-target="#changePassword">Modifier mot de passe</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="changePasswordLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content password-modal">
            <?php $form2 = \yii\widgets\ActiveForm::begin([
                'method' => 'post',
                'action' => '@member.modifiermotdepasse',
                'options' => [
                    'enctype' => 'multipart/form-data',
                    'class' => 'needs-validation',
                    'novalidate' => true
                ],
                'errorCssClass' => 'is-invalid'
            ])?>

            <div class="form-group">
                <?= $form2->field($passwordModel, 'password')->input('password', ['required' => 'required', 'class' => 'form-control'])->label('Ancien mot de passe') ?>
            </div>

            <div class="form-group">
                <?= $form2->field($passwordModel, 'new_password')->input('password', ['required' => 'required', 'class' => 'form-control'])->label('Nouveau mot de passe') ?>
            </div>

            <div class="form-group">
                <?= $form2->field($passwordModel, 'confirmation_new_password')->input('password', ['required' => 'required', 'class' => 'form-control'])->label('Confirmation du nouveau mot de passe') ?>
            </div>

            <div class="form-group text-right">
                <a href="<?= Yii::getAlias("@member.modifier_profil") ?>" class="btn btn-secondary btn-sm">Annuler</a>
                <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
            </div>

            <?php \yii\widgets\ActiveForm::end()?>
        </div>
    </div>
</div>