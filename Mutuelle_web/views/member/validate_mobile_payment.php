<?php
/* @var $this yii\web\View */
/* @var $member app\models\Member */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$adminPhone = '+237 690250672';
$ussdCode = $paymentMethod === 'Orange Money' ? '#150#' : '*126#';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Validation du paiement <?= Html::encode($paymentMethod) ?></h2>

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
                                <p class="mb-2"><strong>Montant :</strong></p>
                                <p class="h4 text-primary"><?= number_format($amount, 0, ',', ' ') ?> FCFA</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Mode de paiement :</strong></p>
                                <p class="h4"><?= Html::encode($paymentMethod) ?></p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p class="mb-2"><strong>Votre numéro :</strong></p>
                            <p class="h4"><?= Html::encode($phone) ?></p>
                        </div>
                        <div class="mt-3">
                            <p class="mb-2"><strong>ID du paiement :</strong></p>
                            <p class="h4"><?= Html::encode($payment_id) ?></p>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h4 class="alert-heading">Instructions de paiement</h4>
                        <ol class="mb-0">
                            <li>Composez le code <strong><?= $ussdCode ?></strong> sur votre téléphone</li>
                            <li>Sélectionnez "Transfert d'argent"</li>
                            <li>Entrez le numéro du bénéficiaire : <strong><?= $adminPhone ?></strong></li>
                            <li>Entrez le montant : <strong><?= number_format($amount, 0, ',', ' ') ?> FCFA</strong></li>
                            <li>Entrez votre code secret sur votre téléphone</li>
                            <li>Validez la transaction</li>
                            <li>Notez l'ID de la transaction qui vous sera envoyé par SMS</li>
                        </ol>
                    </div>

                    <?php $form = ActiveForm::begin([
                        'id' => 'payment-form',
                        'action' => ['member/confirm-payment'],
                        'method' => 'post',
                    ]); ?>

                    <?= Html::hiddenInput('payment_id', $payment_id) ?>
                    <?= Html::hiddenInput('payment_type', $paymentMethod) ?>
                    <?= Html::hiddenInput('phone', $phone) ?>
                    <?= Html::hiddenInput('amount', $amount) ?>

                    <div class="form-group mb-4">
                        <label class="form-label">ID de la transaction <?= $paymentMethod ?></label>
                        <input type="text" name="transaction_id" class="form-control form-control-lg text-center" 
                               required
                               autocomplete="off"
                               placeholder="Entrez l'ID de la transaction reçu par SMS">
                        <div class="form-text text-muted">
                            Cet ID se trouve dans le SMS de confirmation que vous avez reçu après le transfert
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <?= Html::submitButton('Confirmer le paiement', [
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
    font-size: 24px;
    letter-spacing: 2px;
}

.alert {
    margin-bottom: 20px;
}

.alert-dismissible .btn-close {
    padding: 1.25rem;
}

.alert-info ol {
    padding-left: 20px;
}

.alert-info ol li {
    margin-bottom: 10px;
}

.alert-info ol li:last-child {
    margin-bottom: 0;
}
</style>
