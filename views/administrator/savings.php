<?php

use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->beginBlock('title') ?>
Épargnes
<?php $this->endBlock() ?>
<?php $this->beginBlock('style') ?>
<style>
    .page-header {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
        text-align: center;
    }

    .session-selector {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .session-selector label {
        color: #2193b0;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .session-selector select {
        border: 1px solid #e0e0e0;
        border-radius: 0.25rem;
        padding: 0.5rem;
        transition: all 0.3s ease;
    }

    .session-selector select:focus {
        border-color: #2193b0;
        box-shadow: 0 0 0 0.2rem rgba(33, 147, 176, 0.25);
    }

    .session-summary {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
    }

    .session-summary .status {
        display: inline-block;
        padding: 0.25rem 1rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }

    .session-summary .status.active {
        background: rgba(52, 168, 83, 0.1);
        color: #34a853;
    }

    .session-summary .status.inactive {
        background: rgba(234, 67, 53, 0.1);
        color: #ea4335;
    }

    .session-summary .amount {
        font-size: 2.5rem;
        font-weight: 600;
        color: #2193b0;
        margin: 1rem 0;
    }

    .session-summary .label {
        color: #6c757d;
        font-size: 1.1rem;
    }

    .savings-table {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .savings-table .table {
        margin-bottom: 0;
    }

    .savings-table thead th {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        color: #495057;
        font-weight: 600;
        border: none;
        padding: 1rem;
    }

    .savings-table tbody tr {
        transition: all 0.3s ease;
    }

    .savings-table tbody tr:hover {
        background-color: rgba(33, 147, 176, 0.05);
    }

    .savings-table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .amount-cell {
        font-weight: 600;
        color: #2193b0;
    }

    .action-cell {
        white-space: nowrap;
    }

    .btn-add-saving {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        transition: all 0.3s ease;
    }

    .btn-add-saving:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(33, 147, 176, 0.2);
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }

    .empty-state h3 {
        margin-bottom: 1rem;
    }

    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }

    .saving-form {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .saving-form input {
        max-width: 150px;
    }

    .saving-form .btn {
        padding: 0.375rem 0.75rem;
    }
</style>
<?php $this->endBlock() ?>

<div class="container mt-5 mb-5">
    <div class="row animate-fade-in">
        <?php if (count($sessions)): ?>
            <?php 
            $activeSession = \app\models\Session::findOne(['active' => true]);
            $allSessions = \app\models\Session::find()->all();
            $selectedSession = isset($_GET['session_id']) ? \app\models\Session::findOne($_GET['session_id']) : $activeSession;
            ?>

            <!-- En-tête de la page -->
            <div class="col-12">
                <div class="page-header">
                    <h2>Gestion des Épargnes</h2>
                </div>
            </div>

            <!-- Sélecteur de session -->
            <div class="col-12">
                <div class="session-selector">
                    <label>Sélectionner une Session</label>
                    <form method="get" action="<?= Yii::getAlias('@administrator.savings') ?>">
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
                <?php $savingAmount = \app\models\Saving::find()->where(['session_id' => $selectedSession->id])->sum('amount'); ?>
                
                <!-- Résumé de la session -->
                <div class="col-12">
                    <div class="session-summary">
                        <div class="status <?= $selectedSession->active ? 'active' : 'inactive' ?>">
                            <?= $selectedSession->active ? 'Session Active' : 'Session Inactive' ?>
                        </div>
                        <div class="amount"><?= number_format($savingAmount ?: 0, 0, ',', ' ') ?> XAF</div>
                        <div class="label">Total des épargnes</div>
                    </div>
                </div>

                <!-- Table des épargnes -->
                <div class="col-12">
                    <div class="savings-table">
                        <?php $members = \app\models\Member::find()->where(['active' => true])->all() ?>
                        <?php if (count($members)): ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Membre</th>
                                            <th>Montant Total</th>
                                            <th>Dernier Administrateur</th>
                                            <?php if($selectedSession->active) : ?>
                                            <th>Nouvelle Épargne</th>
                                            <?php endif; ?>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($members as $index => $member): ?>
                                            <?php
                                            $user = \app\models\User::findOne($member->user_id);
                                            $latestSaving = \app\models\Saving::find()
                                                ->where(['member_id' => $member->id, 'session_id' => $selectedSession->id])
                                                ->orderBy(['created_at' => SORT_DESC])
                                                ->one();
                                            $administrator = $latestSaving ? \app\models\Administrator::findOne($latestSaving->administrator_id) : null;
                                            $administratorUser = $administrator ? \app\models\User::findOne($administrator->id) : null;
                                            $savingAmountUser = \app\models\Saving::find()->where(['member_id' => $member->id])->sum('amount');
                                            ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td class="text-capitalize"><?= Html::encode($user->name . " " . $user->first_name) ?></td>
                                                <td class="amount-cell"><?= number_format($savingAmountUser, 0, ',', ' ') ?> XAF</td>
                                                <td class="text-capitalize"><?= $administratorUser ? $administratorUser->name . " " . $administratorUser->first_name : 'N/A' ?></td>

                                                <?php if($selectedSession->active) : ?>
                                                <td>
                                                    <?php $form = ActiveForm::begin([
                                                        'errorCssClass' => 'text-secondary',
                                                        'method' => 'post',
                                                        'action' => ['administrator/nouvelle-epargne'],
                                                        'options' => ['class' => 'saving-form']
                                                    ]) ?>
                                                    <?= $form->field($model, 'member_id')->hiddenInput(['value' => $member->id])->label(false) ?>
                                                    <?= $form->field($model, 'amount')->label(false)->input("number", [
                                                        'required' => 'required',
                                                        'placeholder' => 'Montant',
                                                        'class' => 'form-control'
                                                    ]) ?>
                                                    <?= $form->field($model, 'session_id')->hiddenInput(['value' => $selectedSession->id])->label(false) ?>
                                                    <?= Html::submitButton('Ajouter', ['class' => 'btn btn-add-saving']) ?>
                                                    <?php ActiveForm::end(); ?>
                                                </td>
                                                <?php endif; ?>

                                                <td class="action-cell">
                                                    <a href="<?= Yii::getAlias("@administrator.savings_details") . "?member_id=" . $member->id . "&session_id=" . $selectedSession->id ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-history"></i> Details
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <h3>Aucun membre inscrit</h3>
                                <a href="<?= Yii::getAlias("@administrator.new_member") ?>" class="btn btn-primary">
                                    Inscrire un membre
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="empty-state">
                    <h3>Aucune session disponible</h3>
                    <p>Veuillez créer une nouvelle session pour commencer à gérer les épargnes.</p>
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
