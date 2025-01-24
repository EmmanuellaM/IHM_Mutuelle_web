<?php $this->beginBlock('title') ?>
Membres
<?php $this->endBlock()?>
<?php $this->beginBlock('style') ?>
<style>
    #btn-add {
        position: fixed!important;
        bottom: 25px;
        right: 25px;
        z-index: 1000;
        border-radius: 100px;
        font-size: 1.3rem;
        width: 50px;
        height: 50px;
        padding: 10px;
    }
    .modal-title2 {
        color: red;
    }
</style>
<?php $this->endBlock()?>

<div class="container mt-5 mb-5">
    <div class="row">
        <?php
        $exercise = \app\models\Exercise::findOne(['active' => true])
        ?>
        <?php if (count($members) ):?>
        <div class="col-12 mt-2">
            <div class="row">
                <?php foreach ($members as $member):?>
                    <?php
                    $user = $member->user();
                    $borrowing = $member->activeBorrowing();
                    $savedAmount = $member->savedAmount($exercise);
                    ?>

                    <div class="col-4">
                        <div class="card member-card">
                            <!-- Card image -->
                            <div class="view overlay">
                                <img class="card-img-top" src="<?= \app\managers\FileManager::loadAvatar($user,"512") ?>" style="height: 12rem" alt="Image membre">
                                <a href="#">
                                    <div class="mask rgba-white-slight"></div>
                                </a>
                            </div>

                            <!-- Card content -->
                            <div class="card-body">

                                <!-- Title -->
                                <h4 class="card-title"><?= $user->name.' '.$user->first_name ?></h4>
                                <!-- Text -->
                                <p class="card-text">
                                    <span>Pseudo : </span><span class="blue-text"><?= $member->username ?></span>
                                    <br>
                                    <span>Téléphone : </span><span class="text-secondary"><?= $user->tel ?> </span>
                                    <br>
                                    <span>Email : </span><span class="blue-text"><?= $user->email ?></span>
                                    <br>
                                    <span>Adresse : </span><span class="text-secondary"><?= $user->address ?> </span>
                                    <br>
                                    <span>Créé le : </span><span class="text-secondary"><?= $user->created_at ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="access-denied-modal" tabindex="-1" role="dialog" aria-labelledby="accessDeniedModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title2" id="accessDeniedModalLabel" >Accès refusé</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Vous ne pouvez pas accéder au profil d'un autre membre. Seul les administrateurs peuvent acceder aux profils autres que les leurs.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

        <?php else:?>
            <h3 class="col-12 text-center text-muted">Aucun membre inscrit</h3>
        <?php endif;?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const memberCards = document.querySelectorAll('.member-card');

        memberCards.forEach(function (card) {
            card.addEventListener('click', function () {
                const memberId = this.dataset.memberId;
                const currentUserId = <?= Yii::$app->user->id ?>;
                if (memberId !== currentUserId) {
                    $('#access-denied-modal').modal('show');
                }
            });
        });
    });
</script>