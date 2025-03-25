<?php use yii\widgets\LinkPager;

$this->beginBlock('title') ?>
Sessions
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

    .page-header h1 {
        color: white;
        margin-bottom: 0.5rem;
        font-size: 2rem;
    }

    .page-header .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        font-size: 1rem;
        margin-top: 0.5rem;
    }

    .session-card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .session-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
    }

    .session-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .session-number {
        color: #dc3545;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .session-date {
        color: #6c757d;
        font-size: 1.1rem;
    }

    .active-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        background: #34a853;
        color: white;
        font-size: 0.9rem;
        margin-left: 0.5rem;
    }

    .session-info {
        margin-bottom: 1rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
    }

    .info-label {
        color: #6c757d;
        font-weight: 500;
    }

    .info-value {
        color: #2193b0;
        font-weight: 600;
    }

    .info-value.warning {
        color: #dc3545;
    }

    .btn-details {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-details:hover {
        background: linear-gradient(135deg, #1c7a94, #5bc0de);
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
        box-shadow: 0 0.5rem 1rem rgba(33, 147, 176, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .empty-state h1 {
        color: #6c757d;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    /* Pagination styling */
    .pagination {
        margin-top: 2rem;
    }

    .page-link {
        border: none;
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        border-radius: 0.25rem;
        color: #2193b0;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background: #e9ecef;
        color: #1c7a94;
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
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
    <div class="row animate-fade-in">
        <?php if(count($exercises)):?>
            <?php $sessions = \app\models\Session::find()->where(['exercise_id' => $exercises[0]->id])->orderBy(['created_at'=>SORT_DESC])->all() ?>
            
            <!-- En-tête de la page -->
            <div class="col-12">
                <div class="page-header">
                    <h1>Exercice de l'année <?= $exercises[0]->year ?></h1>
                    <div class="status-badge">
                        <?= $exercises[0]->active ? "En cours" : "Terminé" ?>
                    </div>
                </div>
            </div>

            <?php if (count($sessions)): ?>
                <?php
                $monthNames = [
                    '01' => 'Janvier',
                    '02' => 'Février',
                    '03' => 'Mars',
                    '04' => 'Avril',
                    '05' => 'Mai',
                    '06' => 'Juin',
                    '07' => 'Juillet',
                    '08' => 'Août',
                    '09' => 'Septembre',
                    '10' => 'Octobre',
                    '11' => 'Novembre',
                    '12' => 'Décembre',
                ];
                ?>
                <?php foreach ($sessions as $index=>$session): ?>
                    <?php 
                    $monthNumber = Yii::$app->formatter->asDate($session->date, 'MM');
                    $savingAmount = \app\models\Saving::find()->where(['session_id' => $session->id])->sum('amount');
                    $refundAmount = \app\models\Refund::find()->where(['session_id' => $session->id])->sum('amount');
                    $borrowingAmount = \app\models\Borrowing::find()->where(['session_id' => $session->id])->sum('amount');
                    ?>

                    <div class="col-12">
                        <div class="session-card">
                            <div class="session-header">
                                <div>
                                    <span class="session-number"><?= '#'. $session->number()?></span>
                                    <span class="session-date">
                                        Session du <?= Yii::$app->formatter->asDate($session->date, 'd')?> <?= $monthNames[$monthNumber] ?>
                                    </span>
                                    <?php if($session->active): ?>
                                        <span class="active-badge">Active</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="session-info">
                                <div class="info-row">
                                    <span class="info-label">Total des épargnes</span>
                                    <span class="info-value"><?= number_format($savingAmount ?: 0, 0, ',', ' ') ?> XAF</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Total des remboursements</span>
                                    <span class="info-value"><?= number_format($refundAmount ?: 0, 0, ',', ' ') ?> XAF</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Total des emprunts</span>
                                    <span class="info-value warning"><?= number_format($borrowingAmount ?: 0, 0, ',', ' ') ?> XAF</span>
                                </div>
                            </div>

                            <div class="text-right">
                                <a href="<?= Yii::getAlias("@administrator.session_details")."?q=".$session->id?>" class="btn btn-details">
                                    <i class="fas fa-info-circle me-1"></i> Détails
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Pagination -->
                <div class="col-12">
                    <nav aria-label="Navigation des sessions">
                        <?= LinkPager::widget([
                            'pagination' => $pagination,
                            'options' => [
                                'class' => 'pagination justify-content-center',
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
                <div class="col-12">
                    <div class="empty-state">
                        <h1>Aucune session créée pour cet exercice</h1>
                        <p class="text-muted">Créez une nouvelle session pour commencer à enregistrer les activités.</p>
                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="col-12">
                <div class="empty-state">
                    <h1>Aucun exercice créé</h1>
                    <p class="text-muted">Créez un nouvel exercice pour commencer à gérer les sessions.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>