<?php $this->beginBlock('title') ?>
Mon profil
<?php $this->endBlock()?>

<?php $this->beginBlock('style')?>
<style>
    .profile-container {
        padding: 2rem;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        min-height: 100vh;
    }

    .profile-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .status-badge {
        font-size: 1.1rem;
        padding: 0.5rem 1rem;
        border-radius: 15px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-badge i {
        font-size: 1.2rem;
    }

    .profile-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .img-container {
        display: inline-block;
        width: 200px;
        height: 200px;
        margin: 0 auto;
        position: relative;
    }

    .img-container img {
        width: 100%;
        height: 100%;
        border-radius: 1000px;
        object-fit: cover;
        border: 4px solid #4e73df;
    }

    .profile-info {
        margin-top: 2rem;
    }

    .info-row {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 0.75rem;
        border-radius: 8px;
        background: #f8f9fc;
    }

    .info-label {
        flex: 1;
        font-weight: 600;
        color: #495057;
    }

    .info-value {
        flex: 2;
        color: #2c3e50;
        font-size: 1.1rem;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #4e73df;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .edit-btn {
        background: #4e73df;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .edit-btn:hover {
        background: #2e59d9;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
    }

    .edit-section {
        text-align: center;
        margin-top: 2rem;
    }

    @media (max-width: 768px) {
        .profile-card {
            padding: 1rem;
        }

        .info-row {
            flex-direction: column;
            text-align: center;
        }

        .info-label {
            margin-bottom: 0.5rem;
        }

        .info-icon {
            margin-bottom: 0.5rem;
        }
    }
</style>
<?php $this->endBlock()?>

<div class="profile-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="text-center mb-4">
                            <img src="<?= \app\managers\FileManager::loadAvatar($this->params['user'], "512") ?>" alt="Avatar" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #4e73df;">
                            <h2 class="mt-3"><?= $this->params['member']->username ?></h2>
                            <p class="text-muted">Membre depuis <?= Yii::$app->formatter->asDate($this->params['member']->created_at, 'php:F Y') ?></p>
                            <div class="status-badge" data-toggle="tooltip" data-placement="bottom" title="<?= app\helpers\MemberStatusHelper::getStatusTooltip($this->params['member']) ?>">
                                <i class="fas fa-user-check"></i>
                                <?= app\helpers\MemberStatusHelper::getStatusLabel($this->params['member']) ?>
                            </div>
                        </div>
                    </div>

                    <div class="profile-info">
                        <div class="info-row">
                            <div class="info-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="info-label">Nom complet</div>
                            <div class="info-value"><?= $this->params['user']->name ?> <?= $this->params['user']->first_name ?></div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-label">Téléphone</div>
                            <div class="info-value"><?= $this->params['user']->tel ?></div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-label">Email</div>
                            <div class="info-value"><?= $this->params['user']->email ?></div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-label">Adresse</div>
                            <div class="info-value"><?= $this->params['user']->address ?></div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="info-label">Date d'inscription</div>
                            <div class="info-value"><?= $this->params['user']->created_at ?></div>
                        </div>
                    </div>

                    <div class="edit-section">
                        <a href="<?= Yii::getAlias("@member.modifier_profil") ?>" class="btn edit-btn">Modifier mon profil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>