<?php $this->beginBlock('title') ?>
Types de tontine
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

    .btn-danger {
        background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
        border: none;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(255, 107, 107, 0.3);
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

    .modal-content {
        border: none;
        border-radius: 1rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-body p {
        font-size: 1.1rem;
        color: #495057;
        margin-bottom: 1.5rem;
    }

    .modal .btn {
        min-width: 100px;
        margin: 0 0.5rem;
    }

    .delete-section {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #e9ecef;
        text-align: center;
    }

    .actions-group {
        text-align: center;
    }
</style>
<?php $this->endBlock()?>

<div class="container mt-5 mb-5">
    <div class="page-header">
        <h2>Modifier le type de tontine</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-12 white-block">
            <?php if (Yii::$app->session->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?= Yii::$app->session->getFlash('success') ?>
                </div>
            <?php endif; ?>
            
            <?php $form = \yii\widgets\ActiveForm::begin([
                'method' => 'post',
                'id' => 'form1',
                'options' => ['enctype' => 'multipart/form-data'],
                'action' => '@administrator.apply_tontine_type_update',
                'errorCssClass' => 'text-secondary',
            ])
            ?>

            <?= $form->field($model,'id')->hiddenInput()->label(false) ?>
            
            <div class="form-group">
                <?= $form->field($model,'title')
                    ->label('Titre de la tontine', ['class' => 'form-control-label'])
                    ->input('text', [
                        'required' => 'required',
                        'class' => 'form-control',
                        'placeholder' => 'Entrez le titre de la tontine'
                    ]) 
                ?>
            </div>

            <div class="form-group">
                <?= $form->field($model,'amount')
                    ->label('Montant', ['class' => 'form-control-label'])
                    ->input('number', [
                        'min' => 0,
                        'required' => 'required',
                        'class' => 'form-control',
                        'placeholder' => 'Entrez le montant'
                    ])
                ?>
            </div>

            <div class="form-group actions-group">
                <button type="submit" class="btn btn-primary">
                    Enregistrer les modifications
                </button>
            </div>

            <?php \yii\widgets\ActiveForm::end()?>

            <?php $form = \yii\widgets\ActiveForm::begin([
                'method' => 'post',
                'id' => 'form2',
                'action' => '@administrator.delete_tontine_type',
                'errorCssClass' => 'text-secondary',
            ])
            ?>
            <?= $form->field($model,'id')->hiddenInput()->label(false) ?>
            <?php \yii\widgets\ActiveForm::end()?>

            <div class="delete-section">
                <button class="btn btn-danger" data-toggle="modal" data-target="#modal">
                    Supprimer ce type de tontine
                </button>

                <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <p class="text-center">Êtes-vous sûr(e) de vouloir supprimer cette tontine ?</p>
                                <div class="form-group text-center mb-0">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Non</button>
                                    <button class="btn btn-primary" onclick="$('#form2').submit()">Oui</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>