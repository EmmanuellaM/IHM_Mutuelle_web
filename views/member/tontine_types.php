<?php
use app\models\Tontine;

$this->beginBlock('title') ?>
Types de tontine
<?php $this->endBlock()?>

<?php $this->beginBlock('style')?>
<style>
    .page-header {
        margin-bottom: 2rem;
        padding: 1.5rem 0;
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        border-radius: 0.5rem;
        text-align: center;
    }
    
    .white-block {
        padding: 2rem;
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .table-head {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .table-row {
        padding: 1rem;
        border-bottom: 2px solid #e9ecef;
        transition: all 0.2s ease;
    }

    .table-row:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.05);
    }

    .table-row:last-child {
        border-bottom: none;
    }

    .link {
        color: #2193b0;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .link:hover {
        color: #6dd5ed;
        text-decoration: none;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(33, 147, 176, 0.3);
    }

    .badge-success {
        background: linear-gradient(135deg, #28a745, #5dd879);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
    }

    .alert {
        border: none;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: linear-gradient(135deg, #28a745, #5dd879);
        color: white;
    }

    .alert-danger {
        background: linear-gradient(135deg, #dc3545, #ff6b6b);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }

    .empty-state h1 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .amount {
        font-weight: 600;
        color: #495057;
    }
</style>
<?php $this->endBlock()?>

<div class="container mt-5 mb-5">
    <div class="page-header">
        <h2>Types de tontine disponibles</h2>
    </div>

    <!-- Flash messages -->
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>
    <!-- End flash messages -->

    <?php if (count($tontineTypes)):?>
        <div class="white-block">
            <div class="table-head">
                <div class="row">
                    <div class="col-6">
                        <h3 class="mb-0">Titre</h3>
                    </div>
                    <div class="col-4">
                        <h3 class="mb-0">Montant</h3>
                    </div>
                    <div class="col-2">
                        <h3 class="mb-0">Action</h3>
                    </div>
                </div>
            </div>

            <?php foreach($tontineTypes as $tontineType): ?>
                <div class="table-row">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <a href="" class="link"><?= $tontineType->title ?></a>
                        </div>
                        <div class="col-4">
                            <span class="amount"><?= $tontineType->amount ?> XAF</span>
                        </div>
                        <div class="col-2">
                            <?php if (!Tontine::isAlreadyRegistered(Yii::$app->user->identity->member->id, $tontineType->id)): ?>
                                <a href="<?= Yii::getAlias("@member.new_tontine")."?member_id=".Yii::$app->user->identity->member->id."&tontine_type_id=".$tontineType->id?>" 
                                   class="btn btn-primary">S'inscrire</a>
                            <?php else: ?>
                                <span class="badge badge-success">Déjà inscrit</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    <?php else: ?>
        <div class="white-block empty-state">
            <h1>Aucune catégorie de tontine n'est disponible pour le moment</h1>
            <p>Revenez plus tard pour voir les nouvelles catégories</p>
        </div>
    <?php endif;?>
</div>