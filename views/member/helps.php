<?php
use yii\widgets\LinkPager;
$this->beginBlock('title') ?>
Aides
<?php $this->endBlock() ?>
<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="<?= Yii::getAlias('@web/css/admin-styles.css') ?>">
<?php $this->endBlock() ?>

<div class="page-container">
    <div class="container">
        <div class="help-dashboard">
            <h3 class="dashboard-title">Inscriptions</h3>
            <div class="dashboard-amount">
                <?= number_format(($t=\app\managers\FinanceManager::socialCrown()) ? ($t>0?$t:0) : 0, 0, ',', ' ') ?> XAF
            </div>
        </div>

        <h3 class="section-title">Aides financières actives</h3>
        <div class="section-divider"></div>

        <?php if (count($activeHelps)): ?>
            <div class="row">
                <?php foreach ($activeHelps as $help):
                    $user = $help->member->user;
                    $helpType = $help->helpType;
                    $progress = ($help->amount / $helpType->amount) * 100;
                ?>
                    <div class="col-md-4">
                        <div class="help-card">
                            <div class="help-card-header">
                                <h4 class="mb-1"><?= $helpType->title ?></h4>
                                <small>
                                    <i class="fas fa-user me-1"></i>
                                    <?= $user->name . " " . $user->first_name ?>
                                </small>
                            </div>
                            <div class="help-card-body">
                                <div class="help-amount">
                                    <?= number_format($helpType->amount, 0, ',', ' ') ?> XAF
                                </div>
                                
                                <div class="progress help-progress">
                                    <div class="progress-bar help-progress-bar" 
                                         role="progressbar" 
                                         style="width: <?= $progress ?>%"
                                         aria-valuenow="<?= $progress ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                
                                <p class="text-center mb-3">
                                    <strong>Contribution par membre :</strong><br>
                                    <?= number_format($help->unit_amount, 0, ',', ' ') ?> XAF
                                </p>
                                
                                <p class="text-center mb-3">
                                    <strong>Progression :</strong><br>
                                    <?= number_format($help->amount, 0, ',', ' ') ?> / <?= number_format($helpType->amount, 0, ',', ' ') ?> XAF
                                </p>
                                
                                <div class="help-actions">
                                    <a href="<?= Yii::getAlias("@member.help_details")."?q=".$help->id ?>" 
                                       class="btn btn-primary">
                                        <i class="fas fa-info-circle me-2"></i>Voir les détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                Aucune aide financière active pour le moment
            </div>
        <?php endif; ?>

        <h3 class="section-title mt-5">Aides financières complétées</h3>
        <div class="section-divider"></div>

        <?php if (count($helps)): ?>
            <div class="row">
                <?php foreach ($helps as $help):
                    $user = $help->member()->user();
                    $helpType = $help->helpType();
                ?>
                    <div class="col-md-4">
                        <div class="help-card">
                            <div class="help-card-header">
                                <h4 class="mb-1"><?= $helpType->title ?></h4>
                                <small>
                                    <i class="fas fa-user me-1"></i>
                                    <?= $user->name . " " . $user->first_name ?>
                                </small>
                            </div>
                            <div class="help-card-body">
                                <div class="help-amount">
                                    <?= number_format($help->amount, 0, ',', ' ') ?> XAF
                                </div>
                                
                                <div class="progress help-progress">
                                    <div class="progress-bar help-progress-bar bg-success" 
                                         style="width: 100%" 
                                         aria-valuenow="100" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                
                                <p class="text-center mb-3">
                                    <strong>Contribution par membre :</strong><br>
                                    <?= number_format($help->unit_amount, 0, ',', ' ') ?> XAF
                                </p>
                                
                                <div class="help-status">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Aide complétée
                                </div>
                                
                                <div class="help-actions">
                                    <a href="<?= Yii::getAlias("@member.help_details")."?q=".$help->id ?>" 
                                       class="btn btn-secondary">
                                        <i class="fas fa-info-circle me-2"></i>Voir les détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <?= LinkPager::widget([
                    'pagination' => $pagination,
                    'options' => [
                        'class' => 'pagination pagination-circle pg-blue mb-0',
                    ],
                    'pageCssClass' => 'page-item',
                    'disabledPageCssClass' => 'd-none',
                    'prevPageCssClass' => 'page-item',
                    'nextPageCssClass' => 'page-item',
                    'firstPageCssClass' => 'page-item',
                    'lastPageCssClass' => 'page-item',
                    'linkOptions' => ['class' => 'page-link']
                ]) ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                Aucune aide financière complétée
            </div>
        <?php endif; ?>
    </div>
</div>