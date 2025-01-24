<?php $this->beginBlock('title') ?>
    Configuration
<?php $this->endBlock() ?>
<?php $this->beginBlock('style') ?>
    <style>
        #saving-amount-title {
            font-size: 5rem;
            color: white;
        }
        .img-bravo {
            width: 100px;
            height: 100px;
            border-radius: 100px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.51);
        }
        .media {
            border-bottom: 1px solid #ededed;
        }
        #social-crown {
            font-size: 5rem;
        }
    </style>
<?php $this->endBlock() ?>

<div class="row">
    <div class="col-12 mb-3">
        <h3 class="text-muted text-center">Configurations</h3>
    </div>
    <div class="col-12">
        <div class="row justify-content-center">
            <?php
          
            $form = \yii\widgets\ActiveForm::begin([
                'method' => 'post',
                'errorCssClass' => 'text-secondary',
                'action' => '@administrator.apply_settings',
                'options' => ['class' => 'col-12 col-md-8 white-block', 'id' => 'config-form']
            ])
            ?>

            <?= $form->field($model,'interest')->input("number",[
                'required' => 'required',
                'readonly' => true, 
                'id' => 'interest-field'
            ])->label('Intérêt par mois sur un emprunt en %') ?>

            <?= $form->field($model,'social_crown')->input("number",[
                'required' => 'required',
                'readonly' => true, 
                'id' => 'social-crown-field'
            ])->label('Montant de solidarité à payer par membre en FCFA') ?>

            <?= $form->field($model,'inscription')->input("number",[
                'required' => 'required',
                'readonly' => true, 
                'id' => 'inscription-field'
            ])->label('Montant de l\'inscription à payer par membre en FCFA') ?>

            
            <div class="form-group text-right">
                <button type="button" class="btn btn-primary" id="edit-save-button">Modifier</button>
                <button type="button" class="btn btn-secondary" id="cancel-button" style="display: none;">Annuler</button>
            </div>

            <?php
            \yii\widgets\ActiveForm::end();
            ?>
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

    // Sauvegarde des valeurs initiales pour annuler les modifications si nécessaire
    const initialValues = {
        interest: interestField.value,
        socialCrown: socialCrownField.value,
        inscription: inscriptionField.value
    };

    let isEditMode = false;

    function enableEditMode() {
        
        interestField.removeAttribute('readonly');
        socialCrownField.removeAttribute('readonly');
        inscriptionField.removeAttribute('readonly');

        editSaveButton.textContent = 'Enregistrer';

        cancelButton.style.display = 'inline-block';


        isEditMode = true;
    }

    // Fonction pour désactiver le mode édition et revenir en lecture seule
    function disableEditMode() {
        // Rendre les champs en lecture seule
        interestField.setAttribute('readonly', true);
        socialCrownField.setAttribute('readonly', true);
        inscriptionField.setAttribute('readonly', true);

        // Remettre les valeurs initiales si on annule
        interestField.value = initialValues.interest;
        socialCrownField.value = initialValues.socialCrown;
        inscriptionField.value = initialValues.inscription;

        // Changer le texte du bouton Enregistrer en Modifier
        editSaveButton.textContent = 'Modifier';

        // Masquer le bouton Annuler
        cancelButton.style.display = 'none';

        // Désactiver le mode édition
        isEditMode = false;
    }
    

    // Fonction pour soumettre le formulaire et enregistrer les modifications
    function saveChanges() {
        // Soumettre le formulaire
        document.getElementById('config-form').submit();
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
});
</script>
