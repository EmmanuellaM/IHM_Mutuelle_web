<?php
use yii\helpers\Html;

// Vérification que le paiement a bien été effectué
if (!Yii::$app->session->get('payment_success')) {
    return Yii::$app->response->redirect(['member/pay']);
}

$amount = Yii::$app->session->get('payment_amount');
$method = Yii::$app->session->get('payment_method');
$transaction_id = Yii::$app->session->get('transaction_id');
$payment_phone = Yii::$app->session->get('payment_phone');

// Nettoyer les données de session après utilisation
Yii::$app->session->remove('payment_success');
Yii::$app->session->remove('payment_amount');
Yii::$app->session->remove('payment_method');
Yii::$app->session->remove('transaction_id');
Yii::$app->session->remove('payment_phone');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Paiement Réussi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><?= Html::a('Accueil', ['member/accueil']) ?></li>
                        <li class="breadcrumb-item active">Paiement Réussi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="success-icon mb-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                            </div>

                            <h2 class="card-title mb-4">Paiement Effectué avec Succès !</h2>

                            <div class="payment-details p-4 mb-4 bg-light rounded">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <h5 class="text-muted">Montant payé</h5>
                                        <p class="h3 text-success"><?= number_format($amount, 0, ',', ' ') ?> FCFA</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <h5 class="text-muted">Mode de paiement</h5>
                                        <p class="h4"><?= Html::encode($method) ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <h5 class="text-muted">ID de transaction</h5>
                                        <p class="h4"><?= Html::encode($transaction_id) ?></p>
                                    </div>
                                    <?php if ($payment_phone): ?>
                                    <div class="col-md-6 mb-3">
                                        <h5 class="text-muted">Numéro utilisé</h5>
                                        <p class="h4"><?= Html::encode($payment_phone) ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="text-center">
                                <p class="text-muted mb-4">
                                    Un reçu de paiement a été enregistré dans votre compte.<br>
                                    Vous pouvez continuer à utiliser nos services.
                                </p>

                                <div class="d-grid gap-2 d-md-block">
                                    <?= Html::a('Retour à l\'accueil', ['member/accueil'], [
                                        'class' => 'btn btn-primary btn-lg mx-2'
                                    ]) ?>
                                    <?= Html::a('Voir mes paiements', ['member/payments'], [
                                        'class' => 'btn btn-outline-primary btn-lg mx-2'
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-details {
    background-color: #f8f9fa;
    border-radius: 10px;
}
.success-icon {
    animation: scale-up 0.4s ease-in-out;
}
@keyframes scale-up {
    0% {
        transform: scale(0.5);
        opacity: 0;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}
</style>
