<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

// Gestion des messages d'erreur et de succès
$error_message = Yii::$app->session->getFlash('payment_error');
$success_message = Yii::$app->session->getFlash('success');
?>

<div class="container py-5">
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= Yii::$app->session->getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?= Yii::$app->session->getFlash('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger">
            <?= Html::encode($error_message) ?>
        </div>
    <?php endif; ?>

    <?php if ($success_message): ?>
        <div class="alert alert-success">
            <?= Html::encode($success_message) ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Choisissez votre mode de paiement</h3>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'payment-form',
                        'action' => ['member/process-payment'],
                        'method' => 'post',
                    ]); ?>

                    <div class="mb-3">
                        <label class="form-label">Mode de paiement</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="payment_type" id="orange_money" value="orange_money" autocomplete="off">
                            <label class="btn btn-outline-primary" for="orange_money">
                                <img src="<?= Yii::getAlias('@web') ?>/img/orange.jpeg" alt="Orange Money" style="width: 30px; height: 30px; margin-right: 10px;">
                                Orange Money
                            </label>

                            <input type="radio" class="btn-check" name="payment_type" id="mtn_money" value="mtn_money" autocomplete="off">
                            <label class="btn btn-outline-primary" for="mtn_money">
                                <img src="<?= Yii::getAlias('@web') ?>/img/MTN.jpeg" alt="MTN Mobile Money" style="width: 30px; height: 30px; margin-right: 10px;">
                                MTN Mobile Money
                            </label>

                            <input type="radio" class="btn-check" name="payment_type" id="card" value="card" autocomplete="off">
                            <label class="btn btn-outline-primary" for="card">
                                <img src="<?= Yii::getAlias('@web') ?>/img/card.jpeg" alt="Carte bancaire" style="width: 30px; height: 30px; margin-right: 10px;">
                                Carte bancaire</label>
                        </div>
                    </div>

                    <!-- Formulaire Mobile Money -->
                    <div id="mobile_form" style="display: none;">
                        <div class="mb-3">
                            <label for="country_code" class="form-label">Pays</label>
                            <select class="form-select" name="country_code" id="country_code">
                                <option value="">Sélectionnez votre pays</option>
                                <option value="237">Cameroun (+237)</option>
                                <option value="225">Côte d'Ivoire (+225)</option>
                                <option value="241">Gabon (+241)</option>
                                <option value="224">Guinée (+224)</option>
                                <option value="223">Mali (+223)</option>
                                <option value="227">Niger (+227)</option>
                                <option value="221">Sénégal (+221)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Numéro de téléphone</label>
                            <div class="input-group">
                                <span class="input-group-text" id="phone_prefix">+---</span>
                                <input type="tel" class="form-control" name="phone" id="phone">
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire Carte Bancaire -->
                    <div id="card_form" style="display: none;">
                        <div class="mb-3">
                            <label for="card_number" class="form-label">Numéro de carte</label>
                            <input type="text" class="form-control" name="card_number" id="card_number" placeholder="XXXX XXXX XXXX XXXX">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expiry" class="form-label">Date d'expiration</label>
                                    <input type="text" class="form-control" name="expiry" id="expiry" placeholder="MM/YY">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <input type="text" class="form-control" name="cvv" id="cvv" placeholder="XXX">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="card_holder" class="form-label">Nom sur la carte</label>
                            <input type="text" class="form-control" name="card_holder" id="card_holder">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Montant (FCFA)</label>
                        <input type="number" class="form-control" name="amount" id="amount" required>
                    </div>

                    <div class="text-center">
                        <?= Html::submitButton('Payer', ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
    // Gestion de l'affichage des formulaires selon le mode de paiement
    $('input[name="payment_type"]').change(function() {
        var paymentType = $(this).val();
        
        // Cacher tous les formulaires
        $('#mobile_form, #card_form').hide();
        
        // Afficher le formulaire approprié
        if (paymentType === 'orange_money' || paymentType === 'mtn_money') {
            $('#mobile_form').show();
        } else if (paymentType === 'card') {
            $('#card_form').show();
        }
    });

    // Mise à jour du préfixe téléphonique
    $('#country_code').change(function() {
        var prefix = $(this).val();
        $('#phone_prefix').text(prefix ? '+' + prefix : '+---');
    });

    // Formatage du numéro de carte
    $('#card_number').on('input', function() {
        var value = $(this).val().replace(/\D/g, '');
        if (value.length > 16) value = value.slice(0, 16);
        $(this).val(value.replace(/(\d{4})/g, '$1 ').trim());
    });

    // Formatage de la date d'expiration
    $('#expiry').on('input', function() {
        var value = $(this).val().replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0,2) + '/' + value.slice(2,4);
        }
        $(this).val(value);
    });

    // Formatage du CVV
    $('#cvv').on('input', function() {
        var value = $(this).val().replace(/\D/g, '');
        if (value.length > 3) value = value.slice(0, 3);
        $(this).val(value);
    });

    // Validation du numéro de téléphone pour Orange Money et MTN Money
    $('#phone').on('input', function() {
        var countryCode = $('#country_code').val();
        var lengths = {
            '237': 9,  // Cameroun
            '225': 10, // Côte d'Ivoire
            '241': 8,  // Gabon
            '224': 9,  // Guinée
            '223': 8,  // Mali
            '227': 8,  // Niger
            '221': 9,  // Sénégal
            '228': 8,  // Togo
            '229': 8,  // Bénin
            '226': 8,  // Burkina Faso
            '235': 8,  // Tchad
            '242': 9   // Congo
        };
        var value = $(this).val().replace(/\D/g, '');
        if (countryCode in lengths) {
            if (value.length > lengths[countryCode]) {
                value = value.slice(0, lengths[countryCode]);
            }
            if (value.length > 0 && value[0] !== '6') {
                value = '6' + value.slice(1);
            }
        }
        $(this).val(value);
    });
JS;
$this->registerJs($js);
?>
