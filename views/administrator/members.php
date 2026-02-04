<?php $this->beginBlock('title') ?>
Membres
<?php $this->endBlock()?>
<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="<?= Yii::getAlias('@web/css/admin-styles.css') ?>">
<?php $this->endBlock()?>

<div class="page-container">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="section-title mb-0">Liste des Membres</h1>
            <?php if (count($members)): ?>
                <a href="<?= Yii::getAlias("@administrator.new_member") ?>" class="btn-add-member">
                    <i class="fas fa-plus"></i>Ajouter un membre
                </a>
            <?php endif; ?>
        </div>

        <?php if (count($members)): ?>
            <div class="row g-4">
                <?php foreach ($members as $member):
                    $user = $member->user();
                ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="member-card">
                            <img class="card-img-top" 
                                 src="<?= \app\managers\FileManager::loadAvatar($user, "256") ?>" 
                                 alt="Photo de profil de <?= htmlspecialchars($user->name.' '.$user->first_name) ?>">

                            <div class="card-body">
                                <h4 class="card-title"><?= htmlspecialchars($user->name.' '.$user->first_name) ?></h4>
                                
                                <div class="card-text">
                                    <p>
                                        <span class="info-label">Pseudo :</span>
                                        <span class="info-value"><?= htmlspecialchars($member->username ?? '') ?></span>
                                    </p>
                                    <?php if ($user->tel): ?>
                                        <p>
                                            <span class="info-label">Téléphone :</span>
                                            <span class="info-value secondary"><?= htmlspecialchars($user->tel ?? '') ?></span>
                                        </p>
                                    <?php endif; ?>
                                    <?php if ($user->email): ?>
                                        <p>
                                            <span class="info-label">Email :</span>
                                            <span class="info-value"><?= htmlspecialchars($user->email ?? '') ?></span>
                                        </p>
                                    <?php endif; ?>
                                    <?php if ($user->address): ?>
                                        <p>
                                            <span class="info-label">Adresse :</span>
                                            <span class="info-value secondary"><?= htmlspecialchars($user->address ?? '') ?></span>
                                        </p>
                                    <?php endif; ?>
                                    <p>
                                        <span class="info-label">Statut :</span>
                                        <span class="<?= $member->active ? 'status-active' : 'status-inactive' ?>">
                                            <?= $member->active ? "En règle" : "Irrégulier" ?>
                                        </span>
                                    </p>
                                    <p>
                                        <span class="info-label">Créé le :</span>
                                        <span class="info-value secondary"><?= $user->created_at ?></span>
                                    </p>
                                </div>

                                <div class="text-end mt-3">
                                    <a href="<?= Yii::getAlias("@administrator.member")."?q=".$member->id ?>" 
                                       class="btn-details">
                                        <i class="fas fa-chart-line"></i>
                                        Détails des activités
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center">
                <div class="alert alert-info d-inline-block">
                    <i class="fas fa-info-circle me-2"></i>Aucun membre inscrit
                </div>
                <div class="d-flex justify-content-center">
                    <a href="<?= Yii::getAlias("@administrator.new_member") ?>" class="btn-add-member-center">
                        <i class="fas fa-plus"></i>Ajouter un membre
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>