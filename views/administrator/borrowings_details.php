<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Borrowings Details';
?>

<?php $this->beginBlock('style') ?>
<style>
    :root {
        --primary-color: #2563eb;
        --secondary-color: #1e40af;
        --success-color: #16a34a;
        --danger-color: #dc2626;
        --text-dark: #1f2937;
        --text-muted: #6b7280;
        --background-light: #f8fafc;
        --border-radius: 12px;
        --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    }

    body {
        background-color: var(--background-light);
        color: var(--text-dark);
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .white-block {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 2.5rem;
        margin-bottom: 2rem;
        transition: transform 0.3s ease;
    }

    .white-block h3 {
        font-size: 1.75rem;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .white-block h4 {
        font-size: 1.25rem;
        color: var(--primary-color);
        margin-bottom: 2rem;
        font-weight: 500;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0 12px;
        margin-top: -12px;
        width: 100%;
    }

    .table thead th {
        border: none;
        background-color: var(--background-light);
        color: var(--text-muted);
        font-weight: 600;
        padding: 1.25rem 1rem;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table tbody tr {
        background: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        border-radius: var(--border-radius);
        transition: transform 0.2s ease;
    }

    .table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .table tbody td, .table tbody th {
        border: none;
        padding: 1.25rem 1rem;
        vertical-align: middle;
        color: var(--text-dark);
        font-size: 1rem;
    }

    .table tbody td:first-child, .table tbody th:first-child {
        border-top-left-radius: var(--border-radius);
        border-bottom-left-radius: var(--border-radius);
    }

    .table tbody td:last-child {
        border-top-right-radius: var(--border-radius);
        border-bottom-right-radius: var(--border-radius);
    }

    .btn {
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border: none;
    }

    .btn-primary:hover {
        background-color: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .btn-danger {
        background-color: var(--danger-color);
        border: none;
    }

    .btn-danger:hover {
        background-color: #b91c1c;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
    }

    .btn-success {
        background-color: var(--success-color);
        border: none;
        color: white;
    }

    .btn-success:hover {
        background-color: #15803d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);
    }

    /* Style pour les montants */
    .amount {
        font-family: 'SF Mono', 'Roboto Mono', monospace;
        font-weight: 600;
    }

    /* Style pour les badges */
    .badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .badge-success {
        background-color: rgba(22, 163, 74, 0.1);
        color: var(--success-color);
    }

    /* Style pour les modals */
    .modal-content {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        border-bottom: 1px solid #e5e7eb;
        padding: 1.5rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-title {
        color: var(--text-dark);
        font-weight: 600;
    }

    .close {
        font-size: 1.5rem;
        color: var(--text-muted);
        opacity: 1;
        transition: all 0.2s ease;
    }

    .close:hover {
        color: var(--text-dark);
        opacity: 1;
    }

    /* Ensure Bootstrap modals appear above all admin elements */
    .modal-backdrop,
    .modal-backdrop.show { z-index: 2000 !important; }

    .modal,
    .modal.show { z-index: 2001 !important; }

    .modal-dialog {
        z-index: 2002 !important;
        transform: none !important;
    }

    /* Style pour les formulaires */
    .form-control {
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        padding: 0.875rem 1.25rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        color: var(--text-dark);
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        outline: none;
    }

    .form-group label {
        color: var(--text-dark);
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
</style>
<?php $this->endBlock() ?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12 white-block">
            <h3><?= Html::encode($memberUser->name . " " . $memberUser->first_name) ?></h3>
            <h4>Emprunts Total de la Session: <span class="amount"><?= number_format($totalBorrowings ? $totalBorrowings : 0, 0, ',', ' ') ?> XAF</span></h4>
            <table class="table table-hover">
                <thead class="blue-grey lighten-4">
                    <tr>
                        <th>#</th>
                        <th>Montant</th>
                        <th>Intérêt</th>
                        <th>Net à payer</th>
                        <th>Reste</th>
                        <th>Administrateur</th>
                        <th>Date D'échéance</th>
                        <?php if($session->active) : ?>
                        <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($borrowings as $index => $borrowing) : ?>
                        <?php
                        $administrator = \app\models\Administrator::findOne($borrowing->administrator_id);
                        $administratorUser = \app\models\User::findOne($administrator->id);
                        $refundedAmountUser = \app\models\Refund::find()->where(['member_id' => $member->id, 'session_id' => $session->id, 'borrowing_id' => $borrowing->id])->sum('amount');
                        $Empruntpaye = $borrowing->amount + ($borrowing->amount * ($borrowing->interest / 100));
                        $Empruntpaye = $Empruntpaye - $refundedAmountUser;
                        // $Empruntpaye = $Empruntpaye - $borrowing->amount;
                        $totalBorrowings += $Empruntpaye;
                        $totalBorrowings = $totalBorrowings - $refundedAmountUser;
                        ?>
                        <tr>
                            <th scope="row"><?= $index + 1 ?></th>
                            <td><?= Html::encode($borrowing->amount) ?> XAF</td>
                            <td><?= $borrowing->interest ?> %</td>
                            <td><?= $borrowing->intendedAmount() ?> XAF</td>
                            <td><?= $Empruntpaye ?> XAF</td>
                            <td><?= Html::encode($administratorUser->name . " " . $administratorUser->first_name) ?></td>
                            <td><?= Html::encode($session->date_d_écheance_emprunt()) ?></td>
                            <?php if($session->active) : ?>
                                <?php if($Empruntpaye == 0) : ?>
                                    <td>
                                        <span style="color: green; font-size:larger" class="badge badge-success">Remboursé</span>
                                    </td>
                                <?php else: ?>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal<?= $borrowing->id ?>">Modifier</button>
                                            <button <?= ($session->active) ? 'data-target="#modalS' . $borrowing->id . '" data-toggle="modal"' : '' ?> class="btn btn-danger btn-sm">Supprimer</button>
                                        </td>
                                <?php endif; ?>
                            <?php endif; ?>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?= $borrowing->id ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?= $borrowing->id ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel<?= $borrowing->id ?>">Modifier Emprunt</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php $form = ActiveForm::begin([
                                            'action' => Url::to(['administrator/modifier-emprunt', 'id' => $borrowing->id]),
                                            'id' => 'edit-saving-form-' . $borrowing->id,
                                            'options' => ['data-pjax' => true]
                                        ]); ?>

                                        <?= $form->field($borrowing, 'member_id')->textInput(['value' => $memberUser->name . " " . $memberUser->first_name, 'readonly' => true]) ?>
                                        <?= $form->field($borrowing, 'amount')->textInput() ?>
                                        <?= Html::hiddenInput('Borrowing[id]', $borrowing->id) ?>

                                        <div class="form-group">
                                            <?= Html::submitButton('Enregistrer', ['class' => 'btn btn-success']) ?>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                                        </div>

                                        <?php ActiveForm::end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="modalS<?= $borrowing->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <p class="p-1 text-center">
                                            Êtes-vous sûr(e) de vouloir supprimer cette épargne?
                                        </p>
                                        <div class="text-center">
                                            <button data-dismiss="modal" class="btn btn-danger">Non</button>
                                            <a href="<?= Yii::getAlias("@administrator.delete_borrowing") . "?q=" . $borrowing->id ?>" class="btn btn-primary">Oui</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
