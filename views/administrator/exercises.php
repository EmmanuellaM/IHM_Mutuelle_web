<?php use yii\widgets\LinkPager;

$this->beginBlock('title') ?>
Exercices
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

    .session-header {
        text-align: center;
        padding: 2rem 0;
    }

    .session-title {
        font-size: 1.5rem;
        color: var(--secondary-color);
        margin-bottom: 1rem;
    }

    .session-amount {
        font-size: 3rem;
        font-weight: 600;
        color: var(--primary-color);
        margin: 1rem 0;
    }

    .session-subtitle {
        color: var(--text-muted);
        font-size: 1.2rem;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 500;
        margin: 0.5rem;
        transition: all 0.3s ease;
    }

    .status-active {
        background-color: var(--success-color);
        color: white;
    }

    .status-inactive {
        background-color: var(--secondary-color);
        color: white;
    }

    .search-section {
        background: linear-gradient(to right, #f8f9fa, #ffffff);
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .search-title {
        color: var(--secondary-color);
        margin-bottom: 1rem;
        font-size: 1.2rem;
    }

    .search-input {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
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
        bottom: 2rem;
        right: 2rem;
        z-index: 1000;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .btn-floating:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    .modal-content {
        border-radius: 12px;
        border: none;
    }

    .modal-body {
        padding: 2rem;
    }

    .form-control {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
    }

    .btn {
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--text-muted);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }

    .info-card {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-align: center;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .info-card h5 {
        font-size: 1.1rem;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
    }

    .info-card h2 {
        font-size: 1.8rem;
        color: var(--primary-color);
        margin-bottom: 0;
    }

    .info-card i {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .chart-container {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .chart-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .chart-title {
        font-size: 1.5rem;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        text-align: center;
    }
</style>
<?php $this->endBlock() ?>

<div class="container mt-5 mb-5">
    <div class="row">
        <?php if(count($exercises)): ?>
            <?php
            $exercise = $exercises[0];
            $members = \app\models\Member::find()->all();  ?>
            <div class="col-12 white-block">
                <div class="session-header">
                    <div class="session-title">
                        Exercice de l'année
                        <span class="status-badge <?= $exercise->active ? 'status-active' : 'status-inactive' ?>">
                            <?= $exercise->active ? 'En cours' : 'Terminé' ?>
                        </span>
                    </div>
                    <div class="session-amount">
                        <?= number_format($exercise->exerciseAmount() ?: 0, 0, ',', ' ') ?> XAF
                    </div>
                    <div class="session-subtitle">Fond total</div>
                </div>
            </div>

            <div class="col-12 mb-2">
                <div class="row">
                    <?php if (count($members)): ?>
                    <div class="col-md-8 p-1">
                        <div class="chart-container">
                            <h3 class="chart-title">Répartition des intérêts</h3>
                            <canvas id="pieChart"></canvas>
                        </div>
                        <div class="chart-container mt-2">
                            <h3 class="chart-title">Évolution des entrées durant l'exercice</h3>
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-4 p-1">
                        <div class="info-grid">
                            <div class="info-card">
                                <i class="fas fa-wallet"></i>
                                <h5>Fond total</h5>
                                <h2><?= number_format($exercise->exerciseAmount() ?: 0, 0, ',', ' ') ?> XAF</h2>
                            </div>
                            <div class="info-card">
                                <i class="fas fa-piggy-bank"></i>
                                <h5>Montant épargné</h5>
                                <h2><?= number_format($exercise->totalSavedAmount() ?: 0, 0, ',', ' ') ?> XAF</h2>
                            </div>
                            <div class="info-card">
                                <i class="fas fa-hand-holding-usd"></i>
                                <h5>Montant emprunté</h5>
                                <h2><?= number_format($exercise->totalBorrowedAmount() ?: 0, 0, ',', ' ') ?> XAF</h2>
                            </div>
                            <div class="info-card">
                                <i class="fas fa-undo-alt"></i>
                                <h5>Montant remboursé</h5>
                                <h2><?= number_format($exercise->totalRefundedAmount() ?: 0, 0, ',', ' ') ?> XAF</h2>
                            </div>
                            <div class="info-card">
                                <i class="fas fa-percentage"></i>
                                <h5>Intérêt produit</h5>
                                <h2><?= number_format($exercise->interest() ?: 0, 0, ',', ' ') ?> XAF</h2>
                            </div>
                            <div class="info-card">
                                <i class="fas fa-gift"></i>
                                <h5>Montant Agapè</h5>
                                <h2><?= number_format($exercise->totalAgapeAmount() ?: 0, 0, ',', ' ') ?> XAF</h2>
                            </div>
                            <?php if($exercise && \app\managers\FinanceManager::numberOfSession() == 12): ?>
                            <div class="info-card">
                                <i class="fas fa-money-check-alt"></i>
                                <h5>Inscription pour le prochain Exercice</h5>
                                <h2><?= number_format($exercise->renflouementAmount() ?: 0, 0, ',', ' ') ?> XAF</h2>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (count($members)): ?>
                <div class="col-12 white-block">
                    <h3 class="text-center my-4 blue-text">Bilan de l'exercice</h3>
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
                            <tr>
                                <th>Membre</th>
                                <th>Montant épargné</th>
                                <th>Montant emprunté</th>
                                <th>Dette remboursée</th>
                                <th>Intérêt sur les dettes</th>
                                <th>Inscription</th>
                                <th>Fond Social</th>
                                <th>Renflouement</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($members as $member): ?>
                                <?php
                                $user = $member->user();
                                $savedAmount = $member->savedAmount($exercise);
                                $borrowedAmount = $member->borrowedAmount($exercise);
                                $refundedAmount = $member->refundedAmount($exercise);
                                $interest = $member->interest($exercise);
                                $sc = $member->social_crown;
                                $insc = $member->inscription;

                                $labels[] = $user->name . " " . $user->first_name;
                                $data[] = $interest ?: 0;
                                $colors[] = \app\managers\ColorManager::getColor();
                                ?>
                                <tr>
                                    <td class="text-capitalize"><?= $user->name . " " . $user->first_name ?></td>
                                    <td><?= number_format($savedAmount ?: 0, 0, ',', ' ') ?> XAF</td>
                                    <td><?= number_format($borrowedAmount ?: 0, 0, ',', ' ') ?> XAF</td>
                                    <td><?= number_format($refundedAmount ?: 0, 0, ',', ' ') ?> XAF</td>
                                    <td class="blue-text"><?= number_format($interest ?: 0, 0, ',', ' ') ?> XAF</td>
                                    <td class="blue-text"><?= number_format($insc ?: 0, 0, ',', ' ') ?> XAF</td>
                                    <td class="blue-text"><?= number_format($sc ?: 0, 0, ',', ' ') ?> XAF</td>
                                    <td class="blue-text"><?= number_format(\app\managers\SettingManager::getSocialCrown() - $sc, 0, ',', ' ') ?> XAF</td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-12">
                <nav aria-label="Navigation des pages">
                    <?= LinkPager::widget([
                        'pagination' => $pagination,
                        'options' => [
                            'class' => 'pagination pagination-circle justify-content-center pg-blue mb-0',
                        ],
                        'pageCssClass' => 'page-item',
                        'disabledPageCssClass' => 'd-none',
                        'prevPageCssClass' => 'page-item',
                        'nextPageCssClass' => 'page-item',
                        'firstPageCssClass' => 'page-item',
                        'lastPageCssClass' => 'page-item',
                        'linkOptions' => ['class' => 'page-link']
                    ]) ?>
                </nav>
            </div>
        <?php else: ?>
            <div class="col-12 white-block empty-state">
                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                <h4>Aucun exercice créé</h4>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $this->beginBlock('script') ?>
<script>
    <?php

    $lLabels = [];
    $lData = [];

    if(isset($exercise))
    {
        $sessions = \app\models\Session::find()->where(['exercise_id' => $exercise->id])->orderBy('created_at',SORT_ASC)->all();
        $sum = 0;

        foreach ($sessions as $index => $session) {
            $lLabels[] = "Session ".($index+1);
            $lData[] = ($session->totalAmount());
        }
    }

    ?>
    //line
    var ctxL = document.getElementById("lineChart").getContext('2d');
    var myLineChart = new Chart(ctxL, {
        type: 'line',
        data: {
            labels: <?= json_encode($lLabels) ?>,
            datasets: [
                {
                    backgroundColor: [
                        'rgba(120, 137, 132, .3)',
                    ],
                    borderColor: [
                        'rgba(0, 10, 130, .7)',
                    ],
                    data: <?= json_encode($lData) ?>
                }
            ]
        },
        options: {
            responsive: true,
            legend: false
        }
    });


    var ctxP = document.getElementById("pieChart").getContext('2d');
    var myPieChart = new Chart(ctxP, {
        type: 'pie',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                data:  <?= json_encode($data) ?>,
                backgroundColor: <?= json_encode($colors) ?>
            }]
        },
        options: {
            responsive: true,
            legend: {
                display : true
            }
        }
    });

</script>
<?php $this->endBlock() ?>