<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Paiement par carte bancaire</h2>

                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= Yii::$app->session->getFlash('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="payment-info mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Montant à payer :</strong></p>
                                <p class="h4 text-primary"><?= number_format($amount, 0, ',', ' ') ?> FCFA</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>ID du paiement :</strong></p>
                                <p class="h4"><?= Html::encode($payment_id) ?></p>
                            </div>
                        </div>
                    </div>

                    <?php $form = ActiveForm::begin([
                        'id' => 'card-payment-form',
                        'action' => ['member/process-card-payment'],
                        'method' => 'post',
                    ]); ?>

                    <?= Html::hiddenInput('payment_id', $payment_id) ?>
                    <?= Html::hiddenInput('amount', $amount) ?>

                    <div class="form-group mb-4">
                        <label class="form-label">Numéro de carte</label>
                        <input type="text" name="card_number" class="form-control form-control-lg" 
                               required
                               pattern="[0-9]{16}"
                               maxlength="19"
                               placeholder="1234 5678 9012 3456">
                        <div class="form-text text-muted">
                            Entrez les 16 chiffres de votre carte bancaire
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="form-label">Date d'expiration</label>
                                <input type="text" name="expiry" class="form-control form-control-lg" 
                                       required
                                       pattern="(0[1-9]|1[0-2])\/([0-9]{2})"
                                       maxlength="5"
                                       placeholder="MM/YY">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="form-label">CVV</label>
                                <input type="text" name="cvv" class="form-control form-control-lg" 
                                       required
                                       pattern="[0-9]{3}"
                                       maxlength="3"
                                       placeholder="123">
                                <div class="form-text text-muted">
                                    3 chiffres au dos de la carte
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Nom sur la carte</label>
                        <input type="text" name="card_holder" class="form-control form-control-lg" 
                               required
                               placeholder="JEAN DUPONT">
                    </div>

                    <div class="d-grid gap-2">
                        <?= Html::submitButton('Payer maintenant', [
                            'class' => 'btn btn-success btn-lg mb-3',
                            'id' => 'submit-payment'
                        ]) ?>
                        <?= Html::a('Annuler', ['member/pay'], ['class' => 'btn btn-outline-secondary btn-lg']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-info {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}

.form-control-lg {
    font-size: 18px;
}

.alert {
    margin-bottom: 20px;
}

.alert-dismissible .btn-close {
    padding: 1.25rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Formatage du numéro de carte
    const cardInput = document.querySelector('input[name="card_number"]');
    cardInput.addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 16) value = value.slice(0, 16);
        this.value = value.replace(/(\d{4})/g, '$1 ').trim();
    });

    // Formatage de la date d'expiration
    const expiryInput = document.querySelector('input[name="expiry"]');
    expiryInput.addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 4) value = value.slice(0, 4);
        if (value.length > 2) {
            value = value.slice(0, 2) + '/' + value.slice(2);
        }
        this.value = value;
    });

    // Formatage du CVV
    const cvvInput = document.querySelector('input[name="cvv"]');
    cvvInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '').slice(0, 3);
    });
});
</script>
