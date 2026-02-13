<?php $this->beginBlock('title') ?>
Accueil
<?php $this->endBlock() ?>

<?php $this->beginBlock('style') ?>
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }
    .news-card {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: transform 0.3s ease;
        min-height: 250px; /* Augmentation de la hauteur minimale */
        display: flex;
        flex-direction: column;
    }
    .news-card:hover {
        transform: translateY(-5px);
    }
    .news-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    .news-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        margin-right: 1rem;
        object-fit: cover;
    }
    .progress-container {
        background-color: #f0f0f0;
        border-radius: 10px;
        height: 10px;
        margin-top: 0.5rem;
    }
    .progress-bar {
        background-color: #2193b0;
        height: 100%;
        border-radius: 10px;
    }
    .dashboard-card {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
    }
    .news-details-container {
        margin-top: auto; /* Pousse le bouton en bas de la carte */
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .btn-details {
        background-color: #2193b0;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: background-color 0.3s ease;
    }
    .btn-details:hover {
        background-color: #6dd5ed;
    }
</style>
<?php $this->endBlock() ?>

<div class="container mt-5 mb-5">
    <div class="dashboard-grid">
        <div>
            <div class="white-block">
                <h3 class="text-center text-muted mb-4">Actualités de la Mutuelle</h3>
                <?php
                $helps = \app\models\Help::findAll(['state' => true]);
                if (count($helps)):
                    foreach ($helps as $help):
                        $member = $help->member;
                        $user = $member->user;
                        $helpType = $help->helpType;
                        $progressPercentage = $help->getContributedAmount() ? 
                            round(($help->getContributedAmount() / $help->amount) * 100) : 0;
                ?>
                    <div class="news-card">
                        <div class="news-header">
                            <img class="news-avatar" src="<?= \app\managers\FileManager::loadAvatar($user)?>" alt="Avatar">
                            <div>
                                <h5 class="mb-1 font-weight-bold"><?= $helpType->title ?></h5>
                                <span class="text-muted"><?= $user->name.' '.$user->first_name?></span>
                            </div>
                        </div>
                        <p class="text-muted mb-3"><?= $help->comments ?></p>
                        <div class="progress-container mb-2">
                            <div class="progress-bar" style="width: <?= $progressPercentage ?>%"></div>
                        </div>
                        <div class="news-details-container">
                            <small class="text-muted"><?= $help->getContributedAmount() ?: 0 ?> / <?= $help->amount?> XAF</small>
                            <a href="<?= Yii::getAlias("@member.help_details")."?q=".$help->id?>" class="btn-details">Détails</a>
                        </div>
                    </div>
                <?php
                    endforeach;
                else:
                ?>
                    <div class="text-center text-muted p-4">
                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                        <p>Aucune aide active pour le moment</p>
                    </div>
                <?php
                endif;
                ?>
            </div>
        </div>
        <div>
            <div class="dashboard-card">
                <h4>Votre Compte</h4>
                <i class="fas fa-wallet fa-3x mb-3"></i>
                <h2 id="social-crown"><?= $member->social_crown ?> XAF</h2>
                <p>Fonds Social Disponible</p>
            </div>
        </div>
    </div>
</div>