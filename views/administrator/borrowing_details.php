<?php
/**
 * Vue des détails d'un emprunt
 */

use app\models\Refund;

$this->beginBlock('title') ?>
    Détails Emprunt
<?php $this->endBlock() ?>
<?php $this->beginBlock('style') ?>
    <style>
        :root {
            --primary-color: #2196F3;
            --primary-dark: #1976D2;
            --success-color: #4CAF50;
            --text-dark: #333;
            --text-light: #fff;
            --background-light: #f8f9fa;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            --transition-speed: 0.3s;
            --border-radius: 12px;
            --secondary-color: #757575;
            --purple-color: #9C27B0;
        }

        body {
            background-color: var(--background-light);
            font-family: 'Roboto', 'Arial', sans-serif;
            line-height: 1.6;
        }

        .container {
            padding: 2rem 1rem;
        }

        .white-block {
            background: var(--text-light);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .img-container {
            display: inline-block;
            width: 200px;
            height: 200px;
            position: relative;
            margin-bottom: 1.5rem;
            transition: transform var(--transition-speed);
        }

        .img-container:hover {
            transform: scale(1.05);
        }

        .img-container img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            box-shadow: var(--card-shadow);
            object-fit: cover;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .text-secondary {
            color: var(--secondary-color) !important;
        }

        .objective {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 1rem 0;
            color: var(--primary-color);
        }

        .refunded {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 1rem 0;
            color: var(--success-color);
        }

        .table {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            margin: 2rem 0;
        }

        .table thead {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--text-light);
        }

        .table th, .table td {
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: background-color var(--transition-speed);
        }

        .table tbody tr:hover {
            background-color: rgba(33, 150, 243, 0.05);
        }

        .alert {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 2rem 0;
            text-align: center;
        }

        .member-name {
            font-size: 1.8rem;
            font-weight: 600;
            margin: 1rem 0;
            color: var(--primary-color);
        }

        .date-created {
            color: var(--secondary-color);
            font-size: 1rem;
            margin: 1rem 0;
        }
    </style>
<?php $this->endBlock() ?>
<?php
$member = $borrowing->member();
$user = $member->user();
$intendedAmount = \app\managers\FinanceManager::intendedAmountFromBorrowing($borrowing);
$refundedAmount = $borrowing->refundedAmount();
$rest = $intendedAmount - $refundedAmount;
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <?php if (Yii::$app->session->hasFlash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= Yii::$app->session->getFlash('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php elseif (Yii::$app->session->hasFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= Yii::$app->session->getFlash('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="white-block">
                <div class="row mb-5">
                    <div class="col-md-4 text-center">
                        <h3 class="section-title">Membre</h3>
                        <div class="img-container">
                            <img src="<?= \app\managers\FileManager::loadAvatar($user,"512") ?>" alt="<?= $user->name." ".$user->first_name ?>">
                        </div>
                        <h2 class="member-name"><?= $user->name." ".$user->first_name ?></h2>
                    </div>
                    <div class="col-md-8">
                        <h4 class="text-center" style="font-size: 1.4rem; color: var(--secondary-color); margin-bottom: 1.5rem;">Détails de l'emprunt</h4>
                        <h6 class="date-created">Créé le : <?= $borrowing->created_at ?></h6>
                        <div class="text-center">
                            <p class="objective">Montant emprunté : <?= $borrowing->amount ?> XAF</p>
                            <h4 class="text-primary mb-4">Intérêt : <?= $borrowing->interest ?> %</h4>
                            <h4 class="text-secondary mb-2">Montant à rembourser (avec intérêts) :</h4>
                            <p class="objective"><?= $intendedAmount ?> XAF</p>
                            <h4 class="text-secondary mb-2">Montant remboursé :</h4>
                            <p class="refunded"><?= $refundedAmount ?> XAF</p>
                            <h4 class="text-secondary mb-2">Reste à rembourser :</h4>
                            <p class="objective" style="color: <?= $rest > 0 ? 'red' : 'var(--success-color)' ?>"><?= $rest ?> XAF</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <h3 class="section-title">Détails des Remboursements</h3>
                        <?php
                        $refunds = Refund::findAll(['borrowing_id' => $borrowing->id]);
                        if (count($refunds)):
                        ?>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Montant remboursé</th>
                                    <th>Session</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($refunds as $index => $refund): ?>
                                    <?php 
                                    $session = $refund->session();
                                    ?>
                                    <tr>
                                        <th scope="row"><?= $index + 1 ?></th>
                                        <td class="text-primary"><?= (new DateTime($refund->created_at))->format("d-m-Y")  ?></td>
                                        <td class="text-primary font-weight-bold"><?= $refund->amount ?> XAF</td>
                                        <td class="text-capitalize"><?= $session ? (new DateTime($session->date))->format("d-m-Y") : 'N/A' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-center">Aucun remboursement enregistré</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
