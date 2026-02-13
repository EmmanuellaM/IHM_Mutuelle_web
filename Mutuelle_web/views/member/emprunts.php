<?php use yii\widgets\LinkPager; ?>

<?php $this->beginBlock('title') ?>
Mes emprunts
<?php $this->endBlock() ?>

<?php $this->beginBlock('style') ?>
<style>
    .borrowing-card {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: transform 0.3s ease;
    }
    .borrowing-card:hover {
        transform: translateY(-5px);
    }
    .borrowing-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 1rem;
    }
    .progress-container {
        background-color: #f0f0f0;
        border-radius: 10px;
        height: 10px;
        margin-top: 0.5rem;
    }
    .progress-bar {
        background-color: #2193b0;
        height: 100%;
        border-radius: 10px;
    }
    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        font-weight: bold;
    }
    .status-active {
        background-color: #28a745;
        color: white;
    }
    .status-completed {
        background-color: #6c757d;
        color: white;
    }
</style>
<?php $this->endBlock() ?>

<div class="container mt-5 mb-5">
    <div class="row">
        <?php if (count($exercises)): ?>
            <div class="col-12 white-block mb-4">
                <?php
                $exercise = $exercises[0];
                $borrowings = $member->exerciseBorrowings($exercise);
                ?>
                <div class="borrowing-header">
                    <div>
                        <h2 class="text-muted">Exercice <span class="text-primary"><?= $exercise->year ?></span></h2>
                        <span class="status-badge <?= $exercise->active ? 'status-active' : 'status-completed' ?>">
                            <?= $exercise->active ? "En cours" : "Terminé" ?>
                        </span>
                    </div>
                    <div class="text-right">
                        <h4 class="text-muted">Total des emprunts</h4>
                        <h3 class="text-primary">
                            <?= array_sum(array_map(function($b) { return $b->amount; }, $borrowings)) ?> XAF
                        </h3>
                    </div>
                </div>

                <?php if (count($borrowings)): ?>
                    <?php foreach ($borrowings as $index => $borrowing): ?>
                        <?php
                        $amount = $borrowing->amount;
                        $administrator = $borrowing->administrator()->user();
                        $session = $borrowing->session();
                        $intendedAmount = $borrowing->intendedAmount();
                        $refundedAmount = $borrowing->refundedAmount();
                        $interest = $borrowing->interest;
                        $rest = $intendedAmount - $refundedAmount;
                        $progressPercentage = $intendedAmount ? round(($refundedAmount / $intendedAmount) * 100) : 0;
                        ?>
                        <div class="borrowing-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Emprunt #<?= $index + 1 ?></h5>
                                <span class="badge badge-primary"><?= $interest ?>% Intérêt</span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="progress-container mb-2">
                                        <div class="progress-bar" style="width: <?= $progressPercentage ?>%"></div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">Remboursé</small>
                                        <small class="text-muted"><?= $progressPercentage ?>%</small>
                                    </div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <p class="mb-1"><strong>Montant emprunté:</strong> <?= $amount ?> XAF</p>
                                    <p class="mb-1"><strong>Montant total:</strong> <?= $intendedAmount ?> XAF</p>
                                    <p class="mb-1"><strong>Reste à payer:</strong> <?= $rest ?> XAF</p>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <small class="text-muted">Administrateur</small>
                                    <p class="mb-0"><?= $administrator->name." ". $administrator->first_name ?></p>
                                </div>
                                <div class="text-right">
                                    <small class="text-muted">Date d'échéance</small>
                                    <p class="mb-0"><?= $session->date_d_écheance_emprunt() ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted p-4">
                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                        <p>Aucun emprunt pour cet exercice.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="col-12 text-center p-5">
                <i class="fas fa-folder-open fa-4x text-muted mb-4"></i>
                <h3 class="text-muted">Aucun exercice enregistré.</h3>
            </div>
        <?php endif; ?>

        <div class="col-12 p-2">
            <nav aria-label="Page navigation">
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
    </div>
</div>