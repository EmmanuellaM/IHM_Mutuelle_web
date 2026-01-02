<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 31/12/18
 * Time: 14:31
 */

use yii\widgets\LinkPager;

$this->beginBlock('title') ?>
Aides
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
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .blue-gradient {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        transition: transform var(--transition-speed);
    }

    .blue-gradient:hover {
        transform: translateY(-5px);
    }

    .card {
        height: auto;
        min-height: 21rem;
        border: none;
        border-radius: 12px !important;
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: all var(--transition-speed);
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-shadow);
    }

    .card-image {
        background-size: cover;
        background-position: center;
    }

    .rgba-black-strong {
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
        height: 100%;
        transition: background var(--transition-speed);
    }

    .card:hover .rgba-black-strong {
        background: rgba(0, 0, 0, 0.8);
    }

    #saving-amount-title {
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--text-light);
        margin: 1rem 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .text-muted {
        color: var(--text-dark) !important;
        font-weight: 600;
        font-size: 1.5rem;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border: none;
        border-radius: 50px;
        padding: 0.8rem 1.5rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all var(--transition-speed);
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
    }

    #btn-add {
        position: fixed !important;
        bottom: 2rem;
        right: 2rem;
        z-index: 1000;
        padding: 1rem 2rem;
        font-size: 0.9rem;
        box-shadow: 0 4px 20px rgba(33, 150, 243, 0.4);
    }

    .alert {
        border-radius: 12px;
        border: none;
        box-shadow: var(--card-shadow);
    }

    .card h2 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0.5rem 0;
    }

    .card h6 {
        font-size: 1rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9);
    }

    .card hr {
        opacity: 0.2;
        margin: 1rem 0;
    }

    .blue-text {
        color: var(--primary-color) !important;
    }

    .pagination {
        margin-top: 2rem;
    }

    .page-link {
        border-radius: 50%;
        margin: 0 0.3rem;
        border: none;
        color: var(--primary-color);
        transition: all var(--transition-speed);
    }

    .page-link:hover {
        background-color: var(--primary-color);
        color: var(--text-light);
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        #saving-amount-title {
            font-size: 2.5rem;
        }
        
        .container {
            padding: 1rem 0.5rem;
        }
        
        .card {
            margin: 0.5rem;
        }
    }
</style>
<?php $this->endBlock() ?>

<div class="container">
    <div class="row mb-4">
        <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= Yii::$app->session->getFlash('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <?php elseif (Yii::$app->session->hasFlash('error')): ?>
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= Yii::$app->session->getFlash('error') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="col-12">
            <div class="white-block text-center blue-gradient">
                <h3 class="text-white mb-3">Fond Social</h3>
                <h1 id="saving-amount-title">
                    <?= ($t = \app\managers\FinanceManager::getAvailableSocialFund()) ? ($t > 0 ? $t : 0) : 0 ?> XAF
                </h1>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="white-block">
                <h3 class="text-center text-muted mb-4">Aides financières auxquelles contribuer</h3>
                <hr class="mb-4">

                <?php if (count($activeHelps)): ?>
                <div class="row">
                    <?php foreach ($activeHelps as $help): ?>
                    <?php 
                        $user = $help->member()->user(); 
                        $helpType = $help->helpType(); 
                    ?>
                    <div class="col-md-4">
                        <div class="card card-image" style="background-image: url(<?= \app\managers\FileManager::loadAvatar($user, '512') ?>);">
                            <div class="text-white text-center d-flex justify-content-center align-items-center rgba-black-strong py-4 px-4">
                                <div>
                                    <h6 class="mb-2">Objectif</h6>
                                    <h2 class="mb-3"><?= $help->amount ?> XAF</h2>
                                    <h6 class="mb-3"><b>Contribution : <?= $help->unit_amount ?> XAF / membre</b></h6>
                                    <hr>
                                    <h6 class="mb-2">Contribution actuelle</h6>
                                    <h2 class="mb-3"><?= $help->contributedAmount ?> XAF</h2>
                                    <h5 class="blue-text mb-2"><i class="fas fa-user mr-2"></i><?= $user->name . " " . $user->first_name ?></h5>
                                    <p class="card-title mb-3"><strong><?= $helpType->title ?></strong></p>
                                    <a class="btn btn-primary" href="<?= Yii::getAlias("@administrator.help_details") . "?q=" . $help->id ?>">
                                        <i class="fas fa-clone mr-2"></i>Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <h6 class="text-center mt-2">Aucune aide repertoriée</h6>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="white-block">
                <h3 class="text-center text-muted mb-4">Aides financières totalement contribuées</h3>
                <hr class="mb-4">

                <?php if (count($helps)): ?>
                <div class="row">
                    <?php foreach ($helps as $help): ?>
                    <?php 
                        $user = $help->member()->user(); 
                        $helpType = $help->helpType(); 
                    ?>
                    <div class="col-md-4">
                        <div class="card card-image" style="background-image: url(<?= \app\managers\FileManager::loadAvatar($user, '512') ?>);">
                            <div class="text-white text-center d-flex justify-content-center align-items-center rgba-black-strong py-4 px-4">
                                <div>
                                    <h6 class="mb-2">Objectif atteint</h6>
                                    <h2 class="mb-3"><?= $help->amount ?> XAF</h2>
                                    <p class="mb-3"><?= $help->unit_amount ?> XAF / membre</p>
                                    <h5 class="blue-text mb-2"><i class="fas fa-user mr-2"></i><?= $user->name . " " . $user->first_name ?></h5>
                                    <p class="card-title mb-3"><strong><?= $helpType->title ?></strong></p>
                                    <a class="btn btn-primary" href="<?= Yii::getAlias("@administrator.help_details") . "?q=" . $help->id ?>">
                                        <i class="fas fa-clone mr-2"></i>Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <div class="col-12">
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
                                'linkOptions' => ['class' => 'page-link'],
                            ]) ?>
                        </nav>
                    </div>
                </div>
                <?php else: ?>
                <h6 class="text-center mt-2">Aucune aide repertoriée</h6>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<a href="<?= Yii::getAlias("@administrator.new_help") ?>" class="btn btn-primary" id="btn-add">
    <i class="fas fa-plus mr-2"></i>Nouvelle aide financière
</a>
