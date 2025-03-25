<?php $this->beginBlock('title') ?>
Membres
<?php $this->endBlock()?>

<?php $this->beginBlock('style') ?>
<style>
    .page-header {
        margin-bottom: 2rem;
        padding: 1.5rem 0;
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        border-radius: 0.5rem;
        text-align: center;
    }

    .member-card {
        background: white;
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .member-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15);
    }

    .member-card .view {
        position: relative;
        overflow: hidden;
        background: #f8f9fa;
        height: 250px;
    }

    .member-card .card-img-top {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.3s ease;
    }

    .member-card:hover .card-img-top {
        transform: scale(1.05);
    }

    .member-card .mask {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.1);
        transition: background 0.3s ease;
    }

    .member-card:hover .mask {
        background: rgba(255, 255, 255, 0.2);
    }

    .member-card .card-body {
        padding: 1.5rem;
    }

    .member-card .card-title {
        color: #2193b0;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .member-info {
        margin-bottom: 0.5rem;
        display: flex;
        align-items: flex-start;
    }

    .member-info-label {
        font-weight: 600;
        color: #495057;
        min-width: 100px;
    }

    .member-info-value {
        color: #6c757d;
    }

    .member-info-value.highlight {
        color: #2193b0;
        font-weight: 500;
    }

    .modal-content {
        border: none;
        border-radius: 1rem;
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(135deg, #dc3545, #ff6b6b);
        color: white;
        border: none;
        padding: 1.5rem;
    }

    .modal-header .modal-title {
        font-weight: 600;
        margin: 0;
    }

    .modal-header .close {
        color: white;
        opacity: 1;
        text-shadow: none;
        transition: transform 0.2s ease;
    }

    .modal-header .close:hover {
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 2rem;
        font-size: 1.1rem;
        color: #495057;
    }

    .modal-footer {
        border: none;
        padding: 1rem 2rem 2rem;
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545, #ff6b6b);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(220, 53, 69, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        margin-top: 2rem;
    }

    .empty-state h3 {
        color: #6c757d;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
</style>
<?php $this->endBlock()?>

<div class="container mt-5 mb-5">
    <div class="page-header">
        <h2>Liste des membres</h2>
    </div>

    <div class="row">
        <?php
        $exercise = \app\models\Exercise::findOne(['active' => true])
        ?>
        <?php if (count($members)):?>
            <?php foreach ($members as $member):?>
                <?php
                $user = $member->user();
                $borrowing = $member->activeBorrowing();
                $savedAmount = $member->savedAmount($exercise);
                ?>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="member-card" data-member-id="<?= $member->id ?>">
                        <div class="view">
                            <img class="card-img-top" src="<?= \app\managers\FileManager::loadAvatar($user,"512") ?>" alt="Photo de <?= $user->name ?>">
                            <div class="mask"></div>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title"><?= $user->name.' '.$user->first_name ?></h4>
                            
                            <div class="member-info">
                                <span class="member-info-label">Pseudo :</span>
                                <span class="member-info-value highlight"><?= $member->username ?></span>
                            </div>

                            <div class="member-info">
                                <span class="member-info-label">Téléphone :</span>
                                <span class="member-info-value"><?= $user->tel ?></span>
                            </div>

                            <div class="member-info">
                                <span class="member-info-label">Email :</span>
                                <span class="member-info-value highlight"><?= $user->email ?></span>
                            </div>

                            <div class="member-info">
                                <span class="member-info-label">Adresse :</span>
                                <span class="member-info-value"><?= $user->address ?></span>
                            </div>

                            <div class="member-info">
                                <span class="member-info-label">Créé le :</span>
                                <span class="member-info-value"><?= $user->created_at ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Modal d'accès refusé -->
            <div class="modal fade" id="access-denied-modal" tabindex="-1" role="dialog" aria-labelledby="accessDeniedModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Accès refusé</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Vous ne pouvez pas accéder au profil d'un autre membre. Seuls les administrateurs peuvent accéder aux profils autres que les leurs.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="col-12">
                <div class="empty-state">
                    <h3>Aucun membre inscrit</h3>
                    <p>Il n'y a actuellement aucun membre enregistré dans le système.</p>
                </div>
            </div>
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