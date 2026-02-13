<?php

use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->beginBlock('title') ?>
Remboursements
<?php $this->endBlock() ?>

<?php $this->beginBlock('style') ?>
<style>
    :root {
        --primary-color: #2196F3;
        --secondary-color: #607D8B;
        --success-color: #4CAF50;
        --danger-color: #f44336;
        --warning-color: #FFC107;
        --text-muted: #6c757d;
    }

    /* Styles pour les sélecteurs */
    select.form-control {
        font-size: 1rem;
        color: #333;
        background-color: #fff;
        height: auto !important;
        padding: 0.8rem !important;
    }

    select.form-control option {
        padding: 1rem;
        font-size: 1rem;
        color: #333;
        background-color: #fff;
    }

    select.form-control option:hover,
    select.form-control option:focus,
    select.form-control option:active {
        background-color: var(--primary-color) !important;
        color: #fff !important;
    }

    /* Style pour le select2 si utilisé */
    .select2-container--default .select2-selection--single {
        height: auto;
        padding: 0.8rem;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: normal;
        color: #333;
    }

    .select2-container--default .select2-results__option {
        padding: 0.8rem;
        font-size: 1rem;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--primary-color);
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        padding: 0.5rem;
        border-radius: 4px;
    }

    .white-block {
        padding: 2rem;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        margin-bottom: 2rem;
    }

    .white-block:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .session-card {
        text-align: center;
        padding: 2.5rem;
    }

    .session-amount {
        font-size: 2.5rem;
        font-weight: 600;
        color: var(--primary-color);
        margin: 1rem 0;
    }

    .session-label {
        color: var(--text-muted);
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
        margin: 0.5rem;
    }

    .status-active {
        background-color: var(--success-color);
        color: white;
    }

    .status-inactive {
        background-color: var(--secondary-color);
        color: white;
    }

    .search-session {
        background-color: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }

    .search-session select {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 0.8rem;
        transition: all 0.3s ease;
    }

    .search-session select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
    }

    .search-session .btn-primary {
        border-radius: 8px;
        padding: 0.8rem 1.5rem;
        font-weight: 500;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 1rem 0;
    }

    .modern-table thead th {
        background-color: #f8f9fa;
        color: var(--secondary-color);
        font-weight: 600;
        padding: 1rem;
        border-bottom: 2px solid #e9ecef;
    }

    .modern-table tbody tr {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .modern-table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
    }

    .modern-table td, .modern-table th {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .amount {
        font-weight: 600;
        color: var(--primary-color);
    }

    .remaining {
        color: var(--warning-color);
        font-weight: 600;
    }

    .btn-floating {
        position: fixed !important;
        bottom: 25px;
        right: 25px;
        z-index: 1000;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        font-size: 1.5rem;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .btn-floating:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }

    .modal-content {
        border-radius: 12px;
        border: none;
    }

    .modal-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-control {
        border-radius: 8px;
        padding: 0.8rem;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
    }

    .btn {
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--text-muted);
    }
</style>
<?php $this->endBlock() ?>

<div class="container mt-5 mb-5">
    <div class="row">
        <?php if (count($sessions)): ?>
            <?php
            $activeSession = \app\models\Session::findOne(['active' => true]);
            $allSessions = \app\models\Session::find()->all();
            $selectedSession = isset($_GET['session_id']) ? \app\models\Session::findOne($_GET['session_id']) : $activeSession;
            ?>

            <div class="col-12">
                <div class="search-session">
                    <h5 class="mb-3">Sélectionner une session</h5>
                    <form method="get" action="<?= Yii::getAlias('@administrator.refunds') ?>">
                        <div class="input-group">
                            <select name="session_id" class="form-control">
                                <?php foreach ($allSessions as $session) : ?>
                                    <option value="<?= Html::encode($session->id) ?>" <?= $selectedSession && $session->id == $selectedSession->id ? 'selected' : '' ?>>
                                        Session <?= Html::encode(ucfirst((new IntlDateFormatter('fr_FR', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'MMMM'))->format(new DateTime($session->date)))) ?> 
                                        <?= $session->active ? '(active)' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Rechercher</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php if ($selectedSession): ?>
                <?php $refundAmount = \app\models\Refund::find()->where(['session_id' => $selectedSession->id])->sum('amount'); ?>
                <div class="col-12">
                    <div class="white-block session-card">
                        <div class="status-badge <?= $selectedSession->active ? 'status-active' : 'status-inactive' ?>">
                            <?= $selectedSession->active ? 'Session active' : 'Session inactive' ?>
                        </div>
                        <div class="session-amount">
                            <?= number_format($refundAmount ?: 0, 0, ',', ' ') ?> XAF
                        </div>
                        <div class="session-label">Total des remboursements</div>
                        <div class="text-muted">
                            Session du <?= (new DateTime($selectedSession->date))->format("d/m/Y") ?>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary btn-floating" data-toggle="modal" data-target="#modalLRFormDemo">
                    <i class="fas fa-plus"></i>
                </button>

                <div class="modal fade" id="modalLRFormDemo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <?php
                            $members = \app\models\Member::find()->where(['active' => true])->all();
                            $items = [];
                            foreach ($members as $member) {
                                $user = \app\models\User::findOne($member->user_id);
                                $items[$member->id] = $user->name . " " . $user->first_name;
                            }
                            ?>

                            <?php $form = \yii\widgets\ActiveForm::begin([
                                'errorCssClass' => 'text-danger',
                                'method' => 'post',
                                'action' => '@administrator.new_refund',
                                'options' => ['class' => 'modal-body']
                            ]) ?>
                            <?= $form->field($model, 'member_id')->dropDownList($items, [
                                'class' => 'form-control',
                                'prompt' => 'Sélectionnez un membre'
                            ])->label("Membre") ?>
                            <?= $form->field($model, "amount")->label("Montant")->input("number", ['required' => 'required']) ?>
                            <?= $form->field($model, 'session_id')->hiddenInput(['value' => $activeSession->id])->label(false) ?>
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary ml-2">Ajouter</button>
                            </div>
                            <?php \yii\widgets\ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="white-block">
                        <?php
                        $refunds = \app\models\Refund::findAll(['session_id' => $selectedSession->id]);
                        ?>

                        <?php if (count($refunds)): ?>
                            <div class="table-responsive">
                                <table class="modern-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Membre</th>
                                            <th>Montant</th>
                                            <th>Reste à payer</th>
                                            <th>Administrateur</th>
                                            <!-- <th>Action</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($refunds as $index => $refund): ?>
                                            <?php
                                            $borrowing = \app\models\Borrowing::findOne($refund->borrowing_id);
                                            $member = \app\models\Member::findOne($borrowing->member_id);
                                            $memberUser = \app\models\User::findOne($member->user_id);
                                            $administrator = \app\models\Administrator::findOne($refund->administrator_id);
                                            $administratorUser = \app\models\User::findOne($administrator->id);
                                            $remainingAmount = max($borrowing->intendedAmount() - $borrowing->refundedAmount(), 0);
                                            ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td class="text-capitalize"><?= Html::encode($memberUser->name . " " . $memberUser->first_name) ?></td>
                                                <td class="amount"><?= number_format($refund->amount, 0, ',', ' ') ?> XAF</td>
                                                <td class="remaining"><?= number_format($remainingAmount, 0, ',', ' ') ?> XAF</td>
                                                <td class="text-capitalize"><?= Html::encode($administratorUser->name . " " . $administratorUser->first_name) ?></td>
                                                <td>
                                                    <?php if ($selectedSession->active): ?>
                                                        <!-- <button class="btn btn-outline-primary btn-sm mr-2">
                                                            <i class="fas fa-edit"></i>
                                                        </button> -->
                                                        <!-- <button class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#modalS<?= $refund->id ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button> -->
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                            <?php if ($selectedSession->active): ?>
                                                <div class="modal fade" id="modalS<?= $refund->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-body text-center">
                                                                <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                                                                <h5 class="mb-4">Êtes-vous sûr(e) de vouloir supprimer ce remboursement ?</h5>
                                                                <button class="btn btn-outline-secondary" data-dismiss="modal">Non</button>
                                                                <a href="<?= Yii::getAlias("@administrator.delete_refund") . "?q=" . $refund->id ?>" class="btn btn-danger ml-2">Oui, supprimer</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-file-invoice-dollar fa-3x mb-3"></i>
                                <h4>Aucun remboursement à cette session</h4>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="white-block empty-state">
                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                    <h4>Aucune session enregistrée</h4>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $this->beginBlock('script') ?>
<script>
    $(document).ready(function() {
        $('#modalLRFormDemo').on('shown.bs.modal', function () {
            $('#modalLRFormDemo input:first').focus();
        });
    });
</script>
<?php $this->endBlock() ?>
