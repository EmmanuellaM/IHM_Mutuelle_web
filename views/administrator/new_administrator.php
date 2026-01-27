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
                            <label class="control-label">Numéro de téléphone <span class="text-danger">*</span></label>
                            
                            <!-- Container avec position relative pour superposer les champs -->
                            <div id="phone-container" style="position: relative; min-height: 38px; transition: min-height 0.3s ease;">
                                <!-- Champ opérateur (visible au départ) -->
                                <div id="operator-container">
                                    <?= $form->field($model, 'operator')
                                        ->dropDownList(
                                            [
                                                'MTN' => 'MTN Cameroun',
                                                'Orange' => 'Orange Cameroun',
                                                'Camtel' => 'Camtel',
                                            ],
                                            [
                                                'class' => 'form-control form-select',
                                                'prompt' => 'Sélectionnez un opérateur',
                                                'id' => 'operator-select'
                                            ]
                                        )
                                        ->label(false) ?>
                                </div>
                                
                                <!-- Champ de numéro (caché au départ, superposé) -->
                                <div id="phone-field-container" style="position: absolute; top: 0; left: 0; right: 0; display: none;">
                                    <?= $form->field($model, 'tel')
                                        ->textInput([
                                            'class' => 'form-control',
                                            'placeholder' => 'Choisissez le préfixe puis complétez',
                                            'id' => 'phone-input',
                                            'list' => 'prefix-list',
                                            'maxlength' => 9
                                        ])
                                        ->label(false) ?>
                                    
                                    <!-- Datalist pour les préfixes -->
                                    <datalist id="prefix-list"></datalist>
                                    
                                    <small id="phone-hint" class="text-muted d-block mt-1" style="font-style: italic;"></small>
                                    
                                    <!-- Bouton pour changer d'opérateur -->
                                    <button type="button" id="change-operator-btn" class="btn btn-sm btn-link p-0 mt-1">
                                        <i class="fas fa-edit"></i> Changer d'opérateur
                                    </button>
                                </div>
                            </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const operatorSelect = document.getElementById('operator-select');
    const phoneFieldContainer = document.getElementById('phone-field-container');
    const phoneInput = document.getElementById('phone-input');
    const prefixList = document.getElementById('prefix-list');
    const phoneHint = document.getElementById('phone-hint');
    const changeOperatorBtn = document.getElementById('change-operator-btn');
    const phoneContainer = document.getElementById('phone-container');
    
    // Préfixes disponibles par opérateur (2 chiffres)
    const prefixes = {
        'MTN': ['67', '65'],
        'Orange': ['69', '65'],
        'Camtel': ['23', '24']
    };
    
    // Quand l'opérateur change
    operatorSelect.addEventListener('change', function() {
        const operator = this.value;
        
        if (operator && prefixes[operator]) {
            // Masquer le container de l'opérateur
            document.getElementById('operator-container').style.display = 'none';
            
            // Afficher le champ de numéro
            phoneFieldContainer.style.display = 'block';
            
            // Agrandir le container pour le texte d'aide
            phoneContainer.style.minHeight = '100px';
            
            // Remplir le datalist avec les préfixes
            prefixList.innerHTML = '';
            prefixes[operator].forEach(prefix => {
                const option = document.createElement('option');
                option.value = prefix;
                prefixList.appendChild(option);
            });
            
            // Focus sur le champ
            phoneInput.focus();
            
            phoneHint.textContent = `${operator} - Tapez ou choisissez un préfixe (${prefixes[operator].join(', ')}) puis complétez les 7 chiffres`;
            phoneHint.style.color = '#6c757d';
        }
    });
    
    // Bouton pour changer d'opérateur
    changeOperatorBtn.addEventListener('click', function() {
        // Réinitialiser
        operatorSelect.value = '';
        document.getElementById('operator-container').style.display = 'block';
        phoneFieldContainer.style.display = 'none';
        phoneInput.value = '';
        prefixList.innerHTML = '';
        
        // Réduire le container
        phoneContainer.style.minHeight = '38px';
    });
    
    // Validation en temps réel
    phoneInput.addEventListener('input', function() {
        // Autoriser uniquement les chiffres
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Limiter à 9 chiffres
        if (this.value.length > 9) {
            this.value = this.value.substring(0, 9);
        }
        
        // Afficher le statut
        if (this.value.length === 9) {
            // Numéro complet - masquer le hint et le bouton
            phoneHint.textContent = '';
            changeOperatorBtn.style.display = 'none';
            
            // Réduire le container
            phoneContainer.style.minHeight = '38px';
        } else if (this.value.length >= 3) {
            phoneHint.textContent = `${9 - this.value.length} chiffre(s) restant(s)`;
            phoneHint.style.color = '#f59e0b';
            phoneHint.style.fontWeight = 'normal';
            changeOperatorBtn.style.display = 'inline-block';
            
            // Agrandir le container pour le texte
            phoneContainer.style.minHeight = '100px';
        } else {
            changeOperatorBtn.style.display = 'inline-block';
            phoneContainer.style.minHeight = '100px';
        }
    });
    
    // Validation avant soumission
    const form = phoneInput.closest('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const operator = operatorSelect.value;
            const phone = phoneInput.value;
            
            if (!operator) {
                e.preventDefault();
                alert('Veuillez sélectionner un opérateur');
                return false;
            }
            
            if (phone.length !== 9) {
                e.preventDefault();
                alert('Le numéro de téléphone doit contenir exactement 9 chiffres');
                return false;
            }
        });
    }
});
</script>