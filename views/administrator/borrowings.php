<?php

use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->beginBlock('title') ?>
Epargnes
<?php $this->endBlock() ?>

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

    #btn-add {
        position: fixed !important;
        bottom: 25px;
        right: 25px;
        z-index: 1000;
        padding: 1rem 1.5rem;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        background: var(--primary-color);
        color: white;
        border: none;
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.3);
        transition: all 0.3s ease;
    }

    #btn-add:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 25px rgba(37, 99, 235, 0.4);
        background: var(--secondary-color);
    }

    .white-block {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 2.5rem;
        margin-bottom: 2rem;
        transition: transform 0.3s ease;
        text-align: center;
    }

    .white-block h3 {
        font-size: 1.25rem;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
        font-weight: 600;
        text-align: center;
    }

    .white-block h1 {
        font-size: 2.75rem;
        color: var(--primary-color);
        margin-bottom: 0.75rem;
        font-weight: 700;
        letter-spacing: -0.5px;
        text-align: center;
    }

    .modal-content {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .modal-body {
        padding: 2.5rem;
    }

    .form-control {
        border-radius: 10px;
        border: 2px solid #e5e7eb;
        padding: 0.875rem 1.25rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        color: var(--text-dark);
        background-color: white;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        outline: none;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1em;
        padding: 1rem 3rem 1rem 1.25rem;
        font-size: 1.1rem;
        line-height: 1.5;
        height: auto;
        min-height: 3.5rem;
    }

    select.form-control option {
        color: var(--text-dark);
        padding: 1rem;
        font-size: 1.1rem;
        line-height: 1.5;
    }

    .input-group {
        margin-bottom: 1.5rem;
    }

    .input-group select.form-control {
        flex: 1;
        width: auto;
    }

    .input-group-append .btn {
        padding: 1rem 2rem;
        font-size: 1.1rem;
    }

    /* Style spécifique pour le sélecteur de session */
    select[name="session_id"] {
        background-color: white;
        max-width: none;
        width: 100%;
    }

    /* Amélioration du contraste pour les options */
    select.form-control option:checked {
        background-color: var(--primary-color);
        color: white;
    }

    select.form-control option:hover {
        background-color: var(--background-light);
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

    .table tbody td {
        border: none;
        padding: 1.25rem 1rem;
        vertical-align: middle;
        color: var(--text-dark);
        font-size: 1rem;
    }

    .table tbody td:first-child {
        border-top-left-radius: var(--border-radius);
        border-bottom-left-radius: var(--border-radius);
    }

    .table tbody td:last-child {
        border-top-right-radius: var(--border-radius);
        border-bottom-right-radius: var(--border-radius);
    }

    .btn {
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
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

    .text-success {
        color: var(--success-color) !important;
    }

    .text-danger {
        color: var(--danger-color) !important;
    }

    .text-secondary {
        color: var(--text-muted) !important;
    }

    /* Amélioration des sélecteurs */
    .form-group label {
        color: var(--text-dark);
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }

    /* Style pour le texte dans les sélecteurs */
    select.form-control option {
        color: var(--text-dark);
        padding: 0.5rem;
        font-size: 1rem;
    }

    /* Amélioration de la lisibilité des montants */
    .amount {
        font-family: 'SF Mono', 'Roboto Mono', monospace;
        font-weight: 600;
        text-align: center;
    }

    /* Style pour les statuts */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-block;
    }

    .status-active {
        background-color: rgba(22, 163, 74, 0.1);
        color: var(--success-color);
    }

    .status-inactive {
        background-color: rgba(220, 38, 38, 0.1);
        color: var(--danger-color);
    }

    /* Style pour les statuts de session */
    .session-status {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        margin: 0.5rem 0;
    }

    .session-status.active {
        background-color: rgba(22, 163, 74, 0.1);
        color: var(--success-color);
    }

    .session-status.inactive {
        background-color: rgba(220, 38, 38, 0.1);
        color: var(--danger-color);
    }

    /* Centrage du texte dans les cellules du tableau */
    .table td.text-center {
        text-align: center;
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

            <!-- Dropdown to select other sessions -->
            <div class="col-12 mb-3">
                <p class="text-muted">Rechercher une Session</p>
                <form method="get" action="<?= Yii::getAlias('@administrator.borrowings') ?>">
                    <div class="input-group mb-3">
                        <select name="session_id" class="form-control">
                            <?php foreach ($allSessions as $session) : ?>
                                <option value="<?= Html::encode($session->id) ?>" <?= $selectedSession && $session->id == $selectedSession->id ? 'selected' : '' ?>>
                                    Session <?= Html::encode(ucfirst((new IntlDateFormatter('fr_FR', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'MMMM'))->format(new DateTime($session->date)))) ?> <?= $session->active ? '<span class="text-success">(active)</span>' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Recherche</button>
                        </div>
                    </div>
                </form>
            </div>

            <?php if ($selectedSession): ?>
                <?php $borrowingAmount = \app\models\Borrowing::find()->where(['session_id' => $selectedSession->id])->sum('amount'); ?>
                <div class="col-12 white-block">
                    <h3 class="session-status <?= $selectedSession->active ? 'active' : 'inactive' ?>">Session <?= $selectedSession->active ? '<span class="text-success">(active)</span>' : '<span class="text-danger">(inactive)</span>' ?></h3>
                    <h1><?= number_format($borrowingAmount ?: 0, 0, ',', ' ') ?> XAF</h1>
                    <h3>empruntés</h3>

                    <?php if (\app\managers\FinanceManager::numberOfSession() == 12): ?>
                        <p class="mt-4 text-secondary">
                            Aucun nouvel emprunt ne peut être fait car nous sommes à la dernière session de l'exercice.
                        </p>
                    <?php endif; ?>
                </div>

                <?php if (\app\managers\FinanceManager::numberOfSession() < 12): ?>
                    <button class="btn <?= $model->hasErrors() ? 'btn-danger' : 'btn-primary' ?>" id="btn-add" data-toggle="modal" data-target="#modalLRFormDemo">Ajouter Emprunt</button>
                    <div class="modal fade" id="modalLRFormDemo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <?php $members = \app\models\Member::find()->where(['active' => true])->all() ?>

                                <?php if (count($members)): ?>
                                    <?php
                                    $items = [];
                                    foreach ($members as $member) {
                                        $user = \app\models\User::findOne($member->user_id);
                                        $items[$member->id] = $user->name . " " . $user->first_name;
                                    }
                                    ?>

                                    <?php $form = \yii\widgets\ActiveForm::begin([
                                        'errorCssClass' => 'text-secondary',
                                        'method' => 'post',
                                        'action' => '@administrator.new_borrowing',
                                        'options' => ['class' => 'modal-body']
                                    ]) ?>
                                    <?= $form->field($model, 'member_id')->dropDownList($items)->label("Membre") ?>
                                    <?= $form->field($model, "amount")->label("Montant")->input("number", ['required' => 'required']) ?>
                                    <?= $form->field($model, 'session_id')->hiddenInput(['value' => $activeSession->id])->label(false) ?>
                                    <div class="form-group text-right">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">Ajouter</button>
                                    </div>
                                    <?php \yii\widgets\ActiveForm::end(); ?>
                                <?php else: ?>
                                    <div class="modal-body">
                                        <h3 class="text-muted text-center">Aucun membre inscrit</h3>
                                        <div class="text-center my-2">
                                            <a href="<?= Yii::getAlias("@administrator.new_member") ?>" class="btn btn-primary">Inscrire un membre</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-12 white-block mb-2">
                    <h5 class="mb-4 text-center">Session du <span class="text-secondary"><?= (new DateTime($selectedSession->date))->format("d-m-Y") ?> <?= $selectedSession->active ? '<span class="text-success">(active)</span>' : '' ?></span> : <span class="blue-text"><?= $borrowingAmount ? $borrowingAmount : 0 ?> XAF</span></h5>

                    <?php if (count($members)): ?>
                        <table class="table table-hover">
                            <thead class="blue-grey lighten-4">
                                <tr>
                                    <th>#</th>
                                    <th>Membre</th>
                                    <th>Dette Brute</th>
                                    <th>Net Perçu</th>
                                    <th>Total Remboursés</th>
                                    <th>Total Aérés</th>
                                    <th>Net à payer</th>
                                    <?php if($selectedSession->active) : ?>
                                    <th>Ajouter Emprunt</th>
                                    <?php endif; ?>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($members as $index => $member): ?>
                                    <?php
                                    $user = \app\models\User::findOne($member->user_id);
                                    $latestBorrowing = \app\models\Borrowing::find()->where(['member_id' => $member->id, 'session_id' => $selectedSession->id])->one();
                                    $administrator = $latestBorrowing ? \app\models\Administrator::findOne($latestBorrowing->administrator_id) : null;
                                    $administratorUser = $administrator ? \app\models\User::findOne($administrator->id) : null;
                                    $borrowingAmountUser = \app\models\Borrowing::find()
                                        ->where(['member_id' => $member->id, 'session_id' => $selectedSession->id])
                                        ->sum('amount');

                                    // Calcul du Net Perçu (Somme des receivedAmount des emprunts)
                                    $borrowingsUser = \app\models\Borrowing::find()
                                        ->where(['member_id' => $member->id, 'session_id' => $selectedSession->id])
                                        ->all();
                                    $receivedAmountUser = 0;
                                    foreach($borrowingsUser as $b) {
                                        $receivedAmountUser += $b->receivedAmount();
                                    }


                                    $TotalrefundedAmountUser = \app\models\Refund::find()
                                        ->where(['member_id' => $member->id, 'session_id' => $selectedSession->id])
                                        ->sum('amount');

                                    // ✅ MODIFICATION: Vérifier l'épargne TOTALE dans l'exercice, pas seulement dans cette session
                                    $savingAmountUser = \app\models\Saving::find()
                                        ->joinWith('session')
                                        ->where(['saving.member_id' => $member->id])
                                        ->andWhere(['session.exercise_id' => $selectedSession->exercise_id])
                                        ->sum('saving.amount');

                                    $totalRemainingAmount = 0;
                                    $borrowings = \app\models\Borrowing::find()->where(['member_id' => $member->id, 'session_id' => $selectedSession->id])->all();

                                    foreach ($borrowings as $borrowing) {
                                        $refundedAmountUser = \app\models\Refund::find()->where(['member_id' => $member->id, 'borrowing_id' => $borrowing->id])->sum('amount');
                                        
                                        // Calculer le montant total à rembourser (principe + intérêts)
                                        // Nouvelle logique: Intérêts précomptés, amount est la dette totale.
                                        $totalToPay = $borrowing->amount;
                                        
                                        // Si le montant remboursé est égal ou supérieur au total à payer, le reste est 0
                                        if ($refundedAmountUser >= $totalToPay) {
                                            $remainingAmount = 0;
                                        } else {
                                            $remainingAmount = $totalToPay - $refundedAmountUser;
                                        }
                                        
                                        $totalRemainingAmount += $remainingAmount;
                                    }
                                    ?>
                                    <tr>
                                        <th><?= $index + 1 ?></th>
                                        <td><?= Html::encode($user->name . " " . $user->first_name) ?></td>
                                        <td class="blue-text amount"><?= $borrowingAmountUser ?> XAF</td>
                                        <td class="blue-text amount"><?= $receivedAmountUser ?> XAF</td>
                                        <td class="blue-text amount"><?= $TotalrefundedAmountUser ? $TotalrefundedAmountUser : 0 ?> XAF</td>
                                        <td class="blue-text amount"><span style="color: <?= $totalRemainingAmount == 0 ? 'green' : 'red' ?>;"><?= $totalRemainingAmount ?> XAF</span></td>
                                        <td><?= $totalRemainingAmount ?> XAF</td>
                                        <?php if ($selectedSession->active): ?>
                                            <?php if ($savingAmountUser == 0): ?>
                                                <td class="red-text">Pour emprunter, veuillez épargner dans l'exercice</td>
                                            <?php else: ?>
                                                <td>
                                                    <?php $form = ActiveForm::begin([
                                                        'errorCssClass' => 'text-secondary',
                                                        'method' => 'post',
                                                        'action' => ['administrator/nouvelle-emprunt'],
                                                        'options' => ['class' => 'form-inline']
                                                    ]) ?>
                                                    <?= $form->field($model, 'member_id')->hiddenInput(['value' => $member->id])->label(false) ?>
                                                    <?= $form->field($model, 'amount')->label(false)->input("number", ['required' => 'required', 'placeholder' => 'Montant', 'class' => 'form-control mr-2']) ?>
                                                    <?= $form->field($model, 'session_id')->hiddenInput(['value' => $selectedSession->id])->label(false) ?>
                                                    <div class="form-group text-right">
                                                        <?= Html::submitButton('Emprunter', ['class' => 'btn btn-success btn-sm']) ?>
                                                    </div>
                                                    <?php ActiveForm::end(); ?>
                                                </td>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <td>
                                            <a href="<?= Yii::getAlias("@administrator.borrowings_details") . "?member_id=" . $member->id . "&session_id=" . $selectedSession->id ?>" class="btn btn-primary btn-sm">Details</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted">Aucun membre trouvé pour cette session.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-muted">Aucune session trouvée.</p>
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
