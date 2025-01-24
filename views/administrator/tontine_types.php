<?php $this->beginBlock('title') ?>
Type d'aide
<?php $this->endBlock()?>
<?php $this->beginBlock('style')?>
<style>
    .table-head {
        background-color: rgba(30, 144, 255, 0.31);
        border-bottom: 1px solid dodgerblue;
    }
    #btn-add {
        position: fixed!important;
        bottom: 25px;
        right: 25px;
        z-index: 1000;
        font-size: 0.8rem;
        padding: 10px;
    }
    #message{

    }
</style>
<?php $this->endBlock()?>
<?php if (count($tontineTypes)):?>
    <br>
    <br>
    <div class="row">
        <div class="col-12 white-block">
            <div class="row table-head py-2">
                <h3 class="col-6">
                    Titre
                </h3>
                <h3 class="col-4">
                    Montant
                </h3>
                <h3 class="col-2">
                    Action
                </h3>
            </div>

            <?php foreach( $tontineTypes as  $tontineType): ?>
                <div class="row py-3" style="border-bottom: 1px solid #e6e6e6">
                    <div class="col-6">
                        <a href="<?= Yii::getAlias("@administrator.update_tontine_type")."?q=".$tontineType->id?>" class="link"><?=  $tontineType->title ?></a>
                    </div>

                    <div class="col-4">
                        <?=  $tontineType->amount ?> XAF
                    </div>
                    <div class="col-2">
                        <a href="<?= Yii::getAlias("@administrator.update_tontine_type")."?q=".$tontineType->id?>" class="btn btn-primary btn-sm">Details</a>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>



<?php else: ?>

    <br>
    <br>
    <div class="row">
        <h1 class="col-12 text-center text-muted">Aucune Nouvelle catégorie de tontine enregistrée</h1>
    </div>
<?php endif;?>

</div>

<a href="<?= Yii::getAlias("@administrator.new_tontine_type") ?>" class="btn btn-secondary" id="btn-add">Ajouter Type Tontine</a>