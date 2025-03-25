<?php $this->beginBlock('title') ?>
Type d'aide
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

    .actions-group {
        text-align: center;
    }
</style>
<?php $this->endBlock()?>

<div class="container mt-5 mb-5">
    <div class="page-header">
        <h2>Créer un nouveau type d'aide</h2>
    </div>

    <div class="row justify-content-center">
        <?php $form = \yii\widgets\ActiveForm::begin([
            'method' => 'post',
            'action' => '@administrator.add_help_type',
            'errorCssClass' => 'text-secondary',
            'options' => ['class' => 'col-md-8 col-12 white-block']
        ])
        ?>
        
        <?= $form->field($model,'id')->hiddenInput(['value'=>0])->label(false) ?>
        
        <div class="form-group">
            <?= $form->field($model,'title')
                ->label('Titre de l\'aide', ['class' => 'form-control-label'])
                ->input('text', [
                    'required' => 'required',
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le titre de l\'aide'
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
                Enregistrer le type d'aide
            </button>
        </div>

        <?php \yii\widgets\ActiveForm::end()?>
    </div>
</div>