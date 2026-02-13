<?php $this->beginBlock('title') ?>
    Configuration de la Mutuelle
<?php $this->endBlock() ?>
<?php $this->beginBlock('style') ?>
    <style>
        :root {
            --primary-color: #1a73e8;
            --secondary-color: #34a853;
            --accent-color: #fbbc05;
            --dark-color: #333333;
            --light-color: #f8f9fa;
            --border-radius: 8px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .config-header {
            background: linear-gradient(135deg, var(--primary-color), #0d47a1);
            color: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: var(--box-shadow);
        }
        
        .config-header h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .config-header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .config-logo {
            width: 80px;
            height: 80px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
            box-shadow: var(--box-shadow);
        }
        
        .config-logo i {
            font-size: 2.5rem;
            color: var(--primary-color);
        }
        
        .white-block {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--box-shadow);
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            padding: 0.75rem;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.25);
        }
        
        .form-control[readonly] {
            background-color: #f8f9fa;
        }
        
        .label-with-icon {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .label-with-icon i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }
        
        .btn {
            padding: 0.5rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #0d47a1;
            border-color: #0d47a1;
        }
        
        .btn-secondary {
            background-color: #f1f3f4;
            border-color: #f1f3f4;
            color: var(--dark-color);
        }
        
        .btn-secondary:hover {
            background-color: #e0e0e0;
            border-color: #e0e0e0;
        }
        
        .config-footer {
            text-align: center;
            color: #666;
            margin-top: 2rem;
            font-size: 0.9rem;
        }
        
        .field-highlight {
            transition: all 0.3s;
        }
        
        .field-highlight.active {
            background-color: rgba(251, 188, 5, 0.1);
            border-left: 3px solid var(--accent-color);
            padding-left: 1rem;
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>
<?php $this->endBlock() ?>

<div class="container">
    <div class="row justify-content-center animate-fade-in">
        <div class="col-12 col-lg-10">
            <!-- Header -->
            <div class="config-header">
                <div class="config-logo">
                    <i class="fas fa-university"></i>
                </div>
                <h2>Configuration de la Mutuelle</h2>
                <p>Mutuelle Web des Enseignants - École Polytechnique de Yaoundé</p>
            </div>
            
            <!-- Configuration Form -->
            <div class="white-block">
                <?php
                $form = \yii\widgets\ActiveForm::begin([
                    'method' => 'post',
                    'errorCssClass' => 'text-danger',
                    'action' => '@administrator.apply_settings',
                    'options' => ['class' => 'config-form', 'id' => 'config-form']
                ])
                ?>

                <div class="field-highlight">
                    <?= $form->field($model, 'interest')->input("number", [
                        'required' => 'required',
                        'readonly' => true, 
                        'id' => 'interest-field',
                        'class' => 'form-control',
                        'min' => '0',
                        'step' => '0.1'
                    ])->label('<div class="label-with-icon"><i class="fas fa-percentage"></i> Intérêt par mois sur un emprunt (%)</div>') ?>
                </div>

                <div class="field-highlight">
                    <?= $form->field($model, 'social_crown')->input("number", [
                        'required' => 'required',
                        'readonly' => true, 
                        'id' => 'social-crown-field',
                        'class' => 'form-control',
                        'min' => '0'
                    ])->label('<div class="label-with-icon"><i class="fas fa-hands-helping"></i> Montant de solidarité à payer par membre (FCFA)</div>') ?>
                </div>

                <div class="field-highlight">
                    <?= $form->field($model, 'inscription')->input("number", [
                        'required' => 'required',
                        'readonly' => true, 
                        'id' => 'inscription-field',
                        'class' => 'form-control',
                        'min' => '0'
                    ])->label('<div class="label-with-icon"><i class="fas fa-file-signature"></i> Montant de l\'inscription à payer par membre (FCFA)</div>') ?>
                </div>
                
                <div class="form-group text-right mt-4">
                    <button type="button" class="btn btn-secondary me-2" id="cancel-button" style="display: none;">
                        <i class="fas fa-times me-1"></i> Annuler
                    </button>
                    <button type="button" class="btn btn-primary" id="edit-save-button">
                        <i class="fas fa-edit me-1"></i> Modifier
                    </button>
                </div>

                <?php
                \yii\widgets\ActiveForm::end();
                ?>
            </div>
            
            <!-- Footer -->
            <div class="config-footer">
                <p>© <?= date('Y') ?> Mutuelle Web des Enseignants - École Polytechnique de Yaoundé</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editSaveButton = document.getElementById('edit-save-button');
    const cancelButton = document.getElementById('cancel-button');
    
    const interestField = document.getElementById('interest-field');
    const socialCrownField = document.getElementById('social-crown-field');
    const inscriptionField = document.getElementById('inscription-field');
    
    const fieldHighlights = document.querySelectorAll('.field-highlight');
    
    // Sauvegarde des valeurs initiales pour annuler les modifications si nécessaire
    const initialValues = {
        interest: interestField.value,
        socialCrown: socialCrownField.value,
        inscription: inscriptionField.value
    };

    let isEditMode = false;

    // Fonction pour activer le mode édition
    function enableEditMode() {
        // Rendre les champs éditables
        interestField.removeAttribute('readonly');
        socialCrownField.removeAttribute('readonly');
        inscriptionField.removeAttribute('readonly');
        
        // Mettre en surbrillance les champs éditables
        fieldHighlights.forEach(field => {
            field.classList.add('active');
        });

        // Changer le texte et l'icône du bouton
        editSaveButton.innerHTML = '<i class="fas fa-save me-1"></i> Enregistrer';
        editSaveButton.classList.remove('btn-primary');
        editSaveButton.classList.add('btn-success');

        // Afficher le bouton Annuler
        cancelButton.style.display = 'inline-block';

        // Activer le mode édition
        isEditMode = true;
    }

    // Fonction pour désactiver le mode édition et revenir en lecture seule
    function disableEditMode() {
        // Rendre les champs en lecture seule
        interestField.setAttribute('readonly', true);
        socialCrownField.setAttribute('readonly', true);
        inscriptionField.setAttribute('readonly', true);
        
        // Supprimer la surbrillance des champs
        fieldHighlights.forEach(field => {
            field.classList.remove('active');
        });

        // Remettre les valeurs initiales si on annule
        interestField.value = initialValues.interest;
        socialCrownField.value = initialValues.socialCrown;
        inscriptionField.value = initialValues.inscription;

        // Changer le texte et l'icône du bouton
        editSaveButton.innerHTML = '<i class="fas fa-edit me-1"></i> Modifier';
        editSaveButton.classList.remove('btn-success');
        editSaveButton.classList.add('btn-primary');

        // Masquer le bouton Annuler
        cancelButton.style.display = 'none';

        // Désactiver le mode édition
        isEditMode = false;
    }
    
    // Fonction pour soumettre le formulaire et enregistrer les modifications
    function saveChanges() {
        // Validation basique
        if (parseFloat(interestField.value) < 0 || 
            parseFloat(socialCrownField.value) < 0 || 
            parseFloat(inscriptionField.value) < 0) {
            alert("Les valeurs ne peuvent pas être négatives.");
            return;
        }
        
        // Effet visuel pour indiquer l'enregistrement
        editSaveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Enregistrement...';
        editSaveButton.disabled = true;
        
        // Soumettre le formulaire après un court délai pour l'effet visuel
        setTimeout(() => {
            document.getElementById('config-form').submit();
        }, 500);
    }

    // Gestion du clic sur le bouton Modifier/Enregistrer
    editSaveButton.addEventListener('click', function() {
        if (isEditMode) {
            // Si on est en mode édition, enregistrer les modifications
            saveChanges();
        } else {
            // Sinon, passer en mode édition
            enableEditMode();
        }
    });

    // Gestion du clic sur le bouton Annuler
    cancelButton.addEventListener('click', function() {
        // Annuler et revenir en lecture seule sans enregistrer
        disableEditMode();
    });
    
    // Mettre en surbrillance les champs au survol (uniquement en mode édition)
    fieldHighlights.forEach(field => {
        field.addEventListener('mouseenter', function() {
            if (isEditMode) {
                this.style.boxShadow = '0 0 0 3px rgba(251, 188, 5, 0.25)';
            }
        });
        
        field.addEventListener('mouseleave', function() {
            this.style.boxShadow = 'none';
        });
    });
});
</script>