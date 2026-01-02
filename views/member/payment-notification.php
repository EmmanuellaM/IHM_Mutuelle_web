<?php
use yii\bootstrap4\Alert;
use yii\bootstrap4\Button;

$badgeClass = 'badge-danger';
if ($status === \app\helpers\MemberStatusHelper::STATUS_INSCRIT) {
    $badgeClass = 'badge-warning';
}
?>

<div class="payment-notification alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center">
        <span class="badge <?= $badgeClass ?> mr-2">!</span>
        <div class="flex-grow-1">
            <p class="mb-0"><?= $message ?></p>
        </div>
        <div class="d-flex align-items-center">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <a href="#" class="btn btn-sm btn-primary ml-2" data-toggle="modal" data-target="#paymentModal">
                <?= $buttonText ?>
            </a>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel"><?php 
                    if ($status === \app\helpers\MemberStatusHelper::STATUS_INACTIF) {
                        echo 'Paiement d\'inscription';
                    } else {
                        echo 'Paiement du fond social';
                    }
                ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php 
                    if ($status === \app\helpers\MemberStatusHelper::STATUS_INACTIF) {
                        echo 'Pour être en règle, vous devez effectuer le paiement de votre inscription.';
                    } else {
                        echo 'Pour être totalement en règle, vous devez effectuer le paiement de votre fond social.';
                    }
                ?></p>
                <div class="mt-3">
                    <button type="button" class="btn btn-primary" onclick="window.location.href='<?= Yii::getAlias('@member.pay') ?>'">
                        Procéder au paiement
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
