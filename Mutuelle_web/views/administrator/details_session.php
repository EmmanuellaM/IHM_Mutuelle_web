<?php
$this->beginBlock('title') ?>
    Détails de la Session
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

        .session-status {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 1rem;
            margin-top: 0.5rem;
        }

        .transaction-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .transaction-amount {
            font-size: 3rem;
            font-weight: 600;
            color: #2193b0;
            margin: 1rem 0;
        }

        .transaction-label {
            color: #6c757d;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .section-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .section-title {
            font-size: 1.5rem;
            color: #2193b0;
            margin: 0;
        }

        .section-amount {
            font-size: 1.2rem;
            color: #34a853;
            font-weight: 600;
        }

        .custom-table {
            width: 100%;
            margin-bottom: 0;
        }

        .custom-table thead th {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            color: #495057;
            font-weight: 600;
            border: none;
            padding: 1rem;
        }

        .custom-table tbody tr {
            transition: all 0.3s ease;
        }

        .custom-table tbody tr:hover {
            background-color: rgba(33, 147, 176, 0.05);
        }

        .custom-table td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid #f0f0f0;
        }

        .amount-cell {
            font-weight: 600;
            color: #2193b0;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }

        .empty-state h3 {
            font-size: 1.2rem;
            margin-bottom: 0;
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>
<?php $this->endBlock() ?>

<div class="container mt-5 mb-5">
    <?php 
    $savingAmount = \app\models\Saving::find()->where(['session_id' => $session->id])->sum('amount');
    $refundAmount = \app\models\Refund::find()->where(['session_id' => $session->id])->sum('amount');
    $borrowingAmount = \app\models\Borrowing::find()->where(['session_id' => $session->id])->sum('amount');
    $transac = $savingAmount + $refundAmount - $borrowingAmount;
    ?>

    <div class="row animate-fade-in">
        <!-- En-tête de la page -->
        <div class="col-12">
            <div class="page-header">
                <h2>Session du <?= (new DateTime($session->date))->format("d-m-Y") ?></h2>
                <?php if ($session->active): ?>
                    <div class="session-status">Session Active</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Carte des transactions -->
        <div class="col-12">
            <div class="transaction-card">
                <div class="transaction-label">Total des transactions</div>
                <div class="transaction-amount"><?= number_format($transac, 0, ',', ' ') ?> XAF</div>
            </div>
        </div>

        <!-- Section Épargnes -->
        <div class="col-12">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">Épargnes</h3>
                    <span class="section-amount"><?= number_format($savingAmount ?: 0, 0, ',', ' ') ?> XAF</span>
                </div>

                <?php $savings = \app\models\Saving::findAll(['session_id' => $session->id]) ?>
                <?php if (count($savings)): ?>
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Membre</th>
                                    <th>Montant</th>
                                    <th>Administrateur</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($savings as $index => $saving): ?>
                                    <?php 
                                    $member = \app\models\Member::findOne($saving->member_id);
                                    $memberUser = \app\models\User::findOne($member->user_id);
                                    $administrator = \app\models\Administrator::findOne($saving->administrator_id);
                                    $administratorUser = \app\models\User::findOne($administrator->id);
                                    ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td class="text-capitalize"><?= $memberUser->name . " " . $memberUser->first_name ?></td>
                                        <td class="amount-cell"><?= number_format($saving->amount, 0, ',', ' ') ?> XAF</td>
                                        <td class="text-capitalize"><?= $administratorUser->name . " " . $administratorUser->first_name ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>Aucune épargne enregistrée pour cette session</h3>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section Remboursements -->
        <div class="col-12">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">Remboursements</h3>
                    <span class="section-amount"><?= number_format($refundAmount ?: 0, 0, ',', ' ') ?> XAF</span>
                </div>

                <?php $refunds = \app\models\Refund::findAll(['session_id' => $session->id]) ?>
                <?php if (count($refunds)): ?>
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Membre</th>
                                    <th>Montant</th>
                                    <th>Administrateur</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($refunds as $index => $refund): ?>
                                    <?php 
                                    $member = \app\models\Member::findOne((\app\models\Borrowing::findOne($refund->borrowing_id))->member_id);
                                    $memberUser = \app\models\User::findOne($member->user_id);
                                    $administrator = \app\models\Administrator::findOne($refund->administrator_id);
                                    $administratorUser = \app\models\User::findOne($administrator->id);
                                    ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td class="text-capitalize"><?= $memberUser->name . " " . $memberUser->first_name ?></td>
                                        <td class="amount-cell"><?= number_format($refund->amount, 0, ',', ' ') ?> XAF</td>
                                        <td class="text-capitalize"><?= $administratorUser->name . " " . $administratorUser->first_name ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>Aucun remboursement enregistré pour cette session</h3>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section Emprunts -->
        <div class="col-12">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">Emprunts</h3>
                    <span class="section-amount" style="color: #dc3545;"><?= number_format($borrowingAmount ?: 0, 0, ',', ' ') ?> XAF</span>
                </div>

                <?php $borrowings = \app\models\Borrowing::findAll(['session_id' => $session->id]) ?>
                <?php if (count($borrowings)): ?>
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Membre</th>
                                    <th>Montant</th>
                                    <th>Administrateur</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($borrowings as $index => $borrowing): ?>
                                    <?php 
                                    $member = \app\models\Member::findOne($borrowing->member_id);
                                    $memberUser = \app\models\User::findOne($member->user_id);
                                    $administrator = \app\models\Administrator::findOne($borrowing->administrator_id);
                                    $administratorUser = \app\models\User::findOne($administrator->id);
                                    ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td class="text-capitalize"><?= $memberUser->name . " " . $memberUser->first_name ?></td>
                                        <td class="amount-cell" style="color: #dc3545;"><?= number_format($borrowing->amount, 0, ',', ' ') ?> XAF</td>
                                        <td class="text-capitalize"><?= $administratorUser->name . " " . $administratorUser->first_name ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>Aucun emprunt enregistré pour cette session</h3>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
