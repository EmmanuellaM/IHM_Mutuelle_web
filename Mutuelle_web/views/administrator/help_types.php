<?php $this->beginBlock('title') ?>
Type d'aide
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

    .table-container {
        overflow-x: auto;
    }

    .table {
        margin: 0;
        width: 100%;
    }

    .table-head {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 1rem;
        border-radius: 0.5rem;
    }

    .table-head h3 {
        color: #495057;
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
        text-transform: uppercase;
    }

    .table-row {
        transition: all 0.2s ease;
        border-bottom: 1px solid #e9ecef;
        align-items: center;
    }

    .table-row:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
    }

    .table-row a.link {
        color: #2193b0;
        text-decoration: none;
        font-weight: 500;
    }

    .table-row a.link:hover {
        color: #1a7389;
        text-decoration: underline;
    }

    .amount {
        font-weight: 600;
        color: #2193b0;
    }

    .btn-primary {
        background-color: #2193b0;
        border-color: #2193b0;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #1a7389;
        border-color: #1a7389;
        transform: translateY(-1px);
    }

    #btn-add {
        position: fixed !important;
        bottom: 25px;
        right: 25px;
        z-index: 1000;
        padding: 1rem 1.5rem;
        border-radius: 2rem;
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        border: none;
        color: white;
        font-weight: 500;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    #btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.2);
    }

    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }

    .empty-state h1 {
        color: #6c757d;
        font-size: 1.5rem;
        font-weight: 500;
    }
</style>
<?php $this->endBlock()?>

<div class="container mt-5 mb-5">
    <div class="page-header">
        <h2>Types d'aide disponibles</h2>
    </div>

    <?php if (count($helpTypes)):?>
        <div class="white-block">
            <div class="table-container">
                <div class="table">
                    <div class="row table-head py-3">
                        <h3 class="col-6">Titre</h3>
                        <h3 class="col-4">Montant</h3>
                        <h3 class="col-2">Action</h3>
                    </div>

                    <?php foreach($helpTypes as $helpType): ?>
                        <div class="row table-row py-3">
                            <div class="col-6">
                                <a href="<?= Yii::getAlias("@administrator.update_help_type")."?q=".$helpType->id?>" class="link"><?= $helpType->title ?></a>
                            </div>
                            <div class="col-4 amount">
                                <?= number_format($helpType->amount, 0, ',', ' ') ?> XAF
                            </div>
                            <div class="col-2">
                                <a href="<?= Yii::getAlias("@administrator.update_help_type")."?q=".$helpType->id?>" class="btn btn-primary btn-sm">Details</a>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="white-block empty-state">
            <h1>Aucun type d'aide enregistré</h1>
        </div>
    <?php endif;?>
</div>

<a href="<?= Yii::getAlias("@administrator.new_help_type") ?>" class="btn" id="btn-add">
    Ajouter Types d'aide
</a>