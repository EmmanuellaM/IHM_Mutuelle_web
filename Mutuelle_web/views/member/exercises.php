<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;

$this->beginBlock('title') ?>
Exercices
<?php $this->endBlock() ?>

<?php $this->beginBlock('style') ?>
<style>
    :root {
        --primary-color: #2196F3;
        --primary-light: #E3F2FD;
        --secondary-color: #607D8B;
        --success-color: #4CAF50;
        --success-light: #E8F5E9;
        --warning-color: #FFC107;
        --danger-color: #F44336;
        --danger-light: #FFEBEE;
        --text-primary: #2c3e50;
        --text-secondary: #7f8c8d;
        --background-light: #f8f9fa;
        --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .container {
        animation: fadeIn 0.5s ease-in-out;
        max-width: 1200px;
        margin: 0 auto;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .white-block {
        padding: 2.5rem;
        background-color: white;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
    }

    .white-block:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(33, 150, 243, 0.15);
    }

    .exercise-header {
        margin-bottom: 3rem;
        text-align: center;
        position: relative;
        padding: 2rem;
        background: var(--primary-light);
        border-radius: 15px;
        overflow: hidden;
    }

    .exercise-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-color);
    }

    .exercise-header h1 {
        font-size: 2.5rem;
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .exercise-header .year {
        font-size: 3rem;
        color: var(--primary-color);
        font-weight: 800;
        text-shadow: 2px 2px 4px rgba(33, 150, 243, 0.2);
        margin: 1rem 0;
    }

    .status {
        font-size: 1rem;
        padding: 0.6rem 2rem;
        border-radius: 50px;
        display: inline-block;
        margin-top: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .status.active {
        background-color: var(--success-light);
        color: var(--success-color);
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.2);
    }

    .status.active::before {
        content: '●';
        margin-right: 8px;
        font-size: 12px;
    }

    .status.inactive {
        background-color: var(--background-light);
        color: var(--text-secondary);
    }

    .status.inactive::before {
        content: '○';
        margin-right: 8px;
        font-size: 12px;
    }

    .table-container {
        margin-top: 2rem;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .table {
        width: 100%;
        margin-bottom: 0;
        background-color: white;
        border-spacing: 0;
        border-collapse: separate;
        border-radius: 15px;
    }

    .table thead th {
        font-weight: 600;
        padding: 1.5rem;
        background-color: var(--background-light);
        color: var(--text-primary);
        border: none;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-align: center;
        position: relative;
    }

    .table thead th:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        height: 50%;
        width: 1px;
        background-color: rgba(0, 0, 0, 0.1);
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: var(--primary-light);
        transform: scale(1.01);
    }

    .table td {
        padding: 1.5rem;
        vertical-align: middle;
        text-align: center;
        border-top: 1px solid var(--background-light);
        font-weight: 500;
    }

    .amount {
        font-family: 'Roboto Mono', monospace;
        font-weight: 600;
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        display: inline-block;
        transition: all 0.3s ease;
        min-width: 150px;
    }

    .amount:hover {
        transform: translateY(-2px);
    }

    .amount.positive {
        color: var(--success-color);
        background: var(--success-light);
        box-shadow: 0 2px 8px rgba(76, 175, 80, 0.15);
    }

    .amount.negative {
        color: var(--danger-color);
        background: var(--danger-light);
        box-shadow: 0 2px 8px rgba(244, 67, 54, 0.15);
    }

    .amount.blue-text {
        color: var(--primary-color);
        background: var(--primary-light);
        box-shadow: 0 2px 8px rgba(33, 150, 243, 0.15);
    }

    .pagination {
        margin-top: 3rem;
        display: flex;
        justify-content: center;
        gap: 0.8rem;
    }

    .page-item {
        list-style: none;
    }

    .page-link {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        background-color: white;
        border: none;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        text-decoration: none;
        font-weight: 600;
    }

    .page-link:hover {
        background-color: var(--primary-color);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(33, 150, 243, 0.3);
    }

    .page-item.active .page-link {
        background-color: var(--primary-color);
        color: white;
        box-shadow: 0 5px 15px rgba(33, 150, 243, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: var(--primary-light);
        border-radius: 20px;
    }

    .empty-state h1 {
        color: var(--text-primary);
        font-size: 2rem;
        margin-bottom: 1.5rem;
        font-weight: 700;
    }

    .empty-state p {
        color: var(--text-secondary);
        font-size: 1.1rem;
        max-width: 500px;
        margin: 0 auto;
    }
</style>
<?php $this->endBlock() ?>

<div class="container mt-5 mb-5">
    <div class="row">
        <?php if (!empty($exercises)): ?>
            <div class="col-12 white-block mb-4">
                <div class="exercise-header">
                    <h1>Exercice de l'année</h1>
                    <div class="year"><?= Html::encode($exercises[0]->year) ?></div>
                    <div>
                        <span class="status <?= $exercises[0]->active ? 'active' : 'inactive' ?>">
                            <?= $exercises[0]->active ? "En cours" : "Terminé" ?>
                        </span>
                    </div>
                </div>

                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Montant épargné</th>
                                <th>Montant emprunté</th>
                                <th>Montant remboursé</th>
                                <th>Intérêt</th>
                                <th>Total obtenu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($exercises as $exercise): ?>
                                <?php
                                $savedAmount = $member->savedAmount($exercise);
                                $borrowingAmount = $member->borrowedAmount($exercise);
                                $refundedAmount = $member->refundedAmount($exercise);
                                $interest = $member->interest($exercise);
                                $total = $savedAmount + $interest;
                                ?>
                                <tr>
                                    <td><span class="amount positive"><?= number_format($savedAmount ?: 0, 0, ',', ' ') ?> XAF</span></td>
                                    <td><span class="amount negative"><?= number_format($borrowingAmount ?: 0, 0, ',', ' ') ?> XAF</span></td>
                                    <td><span class="amount positive"><?= number_format($refundedAmount ?: 0, 0, ',', ' ') ?> XAF</span></td>
                                    <td><span class="amount"><?= number_format($interest ?: 0, 0, ',', ' ') ?> XAF</span></td>
                                    <td><span class="amount blue-text"><?= $exercise->active ? "###" : number_format($total, 0, ',', ' ') . ' XAF' ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="col-12 white-block empty-state">
                <h1>Aucun exercice disponible</h1>
                <p>Vous n'avez pas encore d'exercices. Les exercices auxquels vous participerez apparaîtront ici.</p>
            </div>
        <?php endif; ?>

        <?php if (isset($pagination)): ?>
            <div class="col-12">
                <?= LinkPager::widget([
                    'pagination' => $pagination,
                    'options' => ['class' => 'pagination'],
                    'pageCssClass' => 'page-item',
                    'linkOptions' => ['class' => 'page-link'],
                    'disabledPageCssClass' => 'd-none',
                    'activePageCssClass' => 'active',
                ]) ?>
            </div>
        <?php endif; ?>
    </div>
</div>