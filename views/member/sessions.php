<?php use yii\widgets\LinkPager;

$this->beginBlock('title') ?>
Gestion des sessions
<?php $this->endBlock()?>

<?php $this->beginBlock('style')?>
<style>
    .sessions-container {
        padding: 2rem;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        min-height: 100vh;
    }

    .exercise-header {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
    }

    .exercise-header h1 {
        font-size: 2rem;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .exercise-header h3 {
        color: #4e73df;
        font-weight: 600;
    }

    .session-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .session-card h4 {
        color: #2c3e50;
        margin-bottom: 1.5rem;
    }

    .session-card .session-number {
        color: #e74a3b;
        font-weight: 600;
    }

    .session-card .session-date {
        color: #4e73df;
        font-weight: 600;
    }

    .session-card .status {
        color: #28a745;
        font-weight: 600;
    }

    .amount-box {
        background: #f8f9fc;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .amount-box h5 {
        margin-bottom: 0.5rem;
        color: #495057;
    }

    .amount-value {
        font-weight: 600;
        color: #2c3e50;
    }

    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
        padding: 0.75rem 2rem;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #2e59d9;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
    }

    .pagination {
        justify-content: center;
        margin-top: 2rem;
    }

    .pagination .page-item .page-link {
        color: #4e73df;
        border: 1px solid #4e73df;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    .pagination .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
    }

    .pagination .page-item:not(.active) .page-link:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .no-content {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .exercise-header {
            padding: 1.5rem;
        }

        .session-card {
            padding: 1rem;
        }

        .btn-primary {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .pagination {
            margin-top: 1rem;
        }
    }
</style>
<?php $this->endBlock()?>

<div class="sessions-container">
    <div class="container">
        <div class="row">
            <?php if(count($exercises)):?>

                <?php $sessions = \app\models\Session::find()->where(['exercise_id' => $exercises[0]->id])->orderBy(['created_at'=>SORT_DESC])->all() ?>
                <div class="col-12">
                    <div class="exercise-header">
                        <h1>Exercice de l'année <span class="text-primary"><?= $exercises[0]->year ?></span></h1>
                        <h3><?= $exercises[0]->active ? "En cours" : "Terminé" ?></h3>
                    </div>
                </div>

                <?php if (count($sessions)): ?>

                    <?php foreach ($sessions as $index=>$session): ?>

                        <?php $savingAmount = \app\models\Saving::find()->where(['session_id' => $session->id])->sum('amount'); ?>
                        <?php $refundAmount = \app\models\Refund::find()->where(['session_id' => $session->id])->sum('amount'); ?>
                        <?php $borrowingAmount = \app\models\Borrowing::find()->where(['session_id' => $session->id])->sum('amount'); ?>

                        <div class="col-12">
                            <div class="session-card">
                                <h4><span class="session-number"><?= '#'. $session->number()?></span> Session du <span class="session-date"><?= (new DateTime($session->date))->format("d-m-Y") ?></span> <span class="status"><?= $session->active ? '(active)' : '' ?></span></h4>

                                <div class="amount-box">
                                    <h5>Total des épargnes</h5>
                                    <p class="amount-value"><?= $savingAmount ? $savingAmount : 0 ?> XAF</p>
                                </div>

                                <div class="amount-box">
                                    <h5>Total des remboursements</h5>
                                    <p class="amount-value"><?= $refundAmount ? $refundAmount : 0 ?> XAF</p>
                                </div>

                                <div class="amount-box">
                                    <h5>Total des emprunts</h5>
                                    <p class="amount-value"><?= $borrowingAmount ? $borrowingAmount : 0 ?> XAF</p>
                                </div>

                                <div class="text-right mt-3">
                                    <a href="<?= Yii::getAlias("@member.detailsession")."?q=".$session->id?>" class="btn btn-primary">Détails</a>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="col-12">
                        <div class="no-content">
                            <h1 class="text-muted">Aucune session créée pour cet exercice</h1>
                        </div>
                    </div>

                <?php endif; ?>

                <div class="col-12">
                    <nav aria-label="Navigation des sessions">
                        <?= LinkPager::widget([
                            'pagination' => $pagination,
                            'options' => [
                                'class' => 'pagination'
                            ],
                            'pageCssClass' => 'page-item',
                            'disabledPageCssClass' => 'page-item disabled',
                            'prevPageCssClass' => 'page-item',
                            'nextPageCssClass' => 'page-item',
                            'firstPageCssClass' => 'page-item',
                            'lastPageCssClass' => 'page-item',
                            'linkOptions' => [
                                'class' => 'page-link'
                            ]
                        ]) ?>
                    </nav>
                </div>

            <?php else: ?>

                <div class="col-12">
                    <div class="no-content">
                        <h1 class="text-muted">Aucun exercice créé</h1>
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>