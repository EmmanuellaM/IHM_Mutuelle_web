<?php use yii\widgets\LinkPager;

$this->beginBlock('title') ?>
Sessions
<?php $this->endBlock() ?>
<?php $this->beginBlock('style') ?>
<style>

</style>
<?php $this->endBlock() ?>

<div class="container mt-5 mb-5">
    <div class="row">
        <?php if(count($exercises)):?>
        <?php $sessions = \app\models\Session::find()->where(['exercise_id' => $exercises[0]->id])->orderBy( ['created_at'=>SORT_DESC])->all() ?>
        <div class="col-12 white-block mb-2">
            <h1 class="text-muted text-center">Exercice de l'année <span class="blue-text"><?= $exercises[0]->year ?></span></h1>
            <h3 class="text-secondary text-center"><?= $exercises[0]->active?"En cours":"Terminé" ?></h3>
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
                <?php $monthNumber = Yii::$app->formatter->asDate($session->date, 'MM');?>
                <?php $savingAmount = \app\models\Saving::find()->where(['session_id' => $session->id])->sum('amount'); ?>
                <?php $refundAmount = \app\models\Refund::find()->where(['session_id' => $session->id])->sum('amount'); ?>
                <?php $borrowingAmount = \app\models\Borrowing::find()->where(['session_id' => $session->id])->sum('amount'); ?>

            <div class="col-12 white-block mb-2">
                <h4 class="mb-4"><span class="text-danger"><?= '#'. $session->number()?></span> Session du <span
                  class="text-secondary"><?= Yii::$app->formatter->asDate($session->date, 'd')?> <?= $monthNames[$monthNumber] ?> <?= $session->active ? '(active)' : '' ?></span>
                </h4>
                <h5 class="text-muted">Total des épargnes : <span class="blue-text"><?= $savingAmount?$savingAmount:0 ?> XAF</span></h5>
                <h5 class="text-muted">Total des remboursements : <span class="blue-text"><?= $refundAmount?$refundAmount:0 ?> XAF</span></h5>
                <h5 class="text-muted">Total des emprunts : <span class="text-secondary"><?= $borrowingAmount?$borrowingAmount:0 ?> XAF</span></h5>
                <div class="text-right mt-2">
                    <a href="<?= Yii::getAlias("@administrator.session_details")."?q=".$session->id?>" class="btn btn-primary">Details</a>
                </div>
            </div>

            <?php endforeach; ?>

        <?php else: ?>

        <div class="col-12 white-block mb-2">
            <h1 class="text-center text-muted">Aucune session créée pour cette exercices.</h1>
        </div>

        <?php endif; ?>

            <div class="col-12 p-2">
                <nav aria-label="Page navigation example">
                    <?= LinkPager::widget(['pagination' => $pagination,
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

            <div class="col-12 white-block">
                <h1 class="text-center text-muted">Aucun exercice créé.</h1>
            </div>

        <?php endif; ?>

    </div>
</div>