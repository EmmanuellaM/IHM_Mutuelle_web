<<<<<<< HEAD
<?php
use app\models\Tontine;

$this->beginBlock('title') ?>
=======
<?php $this->beginBlock('title') ?>
>>>>>>> 46a6216 (Il manque quelques détails à ajuster sinon c'est déja presque bon.)
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
                        <a href="" class="link"><?=  $tontineType->title ?></a>
                    </div>

                    <div class="col-4">
                        <?=  $tontineType->amount ?> XAF
                    </div>
                    <div class="col-2">
<<<<<<< HEAD
                        <?php if (!Tontine::isAlreadyRegistered(Yii::$app->user->identity->member->id, $tontineType->id)): ?>
                            <a href="<?= Yii::getAlias("@member.new_tontine")."?member_id=".Yii::$app->user->identity->member->id."&tontine_type_id=".$tontineType->id?>" class="btn btn-primary btn-sm">S'inscrire</a>
                        <?php else: ?>
                            <span class="badge badge-success">Déjà inscrit</span>
                        <?php endif; ?>
=======
                        <a href="<?= Yii::getAlias("@member.new_tontine")."?member_id=".Yii::$app->user->identity->member->id."&tontine_type_id=".$tontineType->id?>" class="btn btn-primary btn-sm">S'inscrire</a>
>>>>>>> 46a6216 (Il manque quelques détails à ajuster sinon c'est déja presque bon.)
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