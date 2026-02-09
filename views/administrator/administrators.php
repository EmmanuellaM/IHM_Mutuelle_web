<?php $this->beginBlock('title') ?>
    Administrateurs
<?php $this->endBlock() ?>
<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="<?= Yii::getAlias('@web/css/admin-styles.css') ?>">
<?php $this->endBlock() ?>

<div class="page-container">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="section-title mb-0">Équipe Administrative</h1>
            <?php if ($this->params['administrator']->root): ?>
                <a href="<?= Yii::$app->urlManager->createUrl(['/administrator/nouvel-administrateur']) ?>" 
                   class="btn btn-primary shadow-sm" style="border-radius: 12px; padding: 0.8rem 1.5rem;">
                    <i class="fas fa-plus me-2"></i>Nouvel Admin
                </a>
            <?php endif; ?>
        </div>
        
        <div class="row g-4">
            <?php if (count($administrators)): ?>
                <?php foreach ($administrators as $administrator): 
                    $user = $administrator->user();
                    $isCurrentUser = ($user->id == $this->params['user']->id);
                ?>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="admin-card">
                            <div class="position-relative mb-2">
                                <img class="admin-profile-img"
                                     src="<?= \app\managers\FileManager::loadAvatar($user, "256") ?>"
                                     alt="<?= htmlspecialchars($administrator->username) ?>">
                                <?php if ($isCurrentUser): ?>
                                    <span class="badge bg-primary position-absolute bottom-0 start-50 translate-middle-x mb-3 px-3 shadow-sm" style="border-radius: 20px; font-size: 0.7rem;">VOUS</span>
                                <?php endif; ?>
                            </div>
                            
                            <h4 class="admin-name mb-1">
                                <?= htmlspecialchars($administrator->username) ?>
                            </h4>
                            <span class="text-primary small fw-bold mb-3 d-block italic"><?= $administrator->root ? 'Administrateur Racine' : 'Gestionnaire' ?></span>
                            
                            <div class="admin-info w-100">
                                <?php if ($user->tel): ?>
                                    <p><i class="fas fa-phone-alt opacity-75"></i><?= htmlspecialchars($user->tel) ?></p>
                                <?php endif; ?>
                                <?php if ($user->email): ?>
                                    <p><i class="fas fa-envelope opacity-75"></i> <span class="text-truncate" style="max-width: 180px;"><?= htmlspecialchars($user->email) ?></span></p>
                                <?php endif; ?>
                                <?php if ($user->address): ?>
                                    <p><i class="fas fa-map-marker-alt opacity-75"></i><?= htmlspecialchars($user->address) ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="admin-actions mt-3">
                                <a href="<?= Yii::$app->urlManager->createUrl(['/administrator/administrator', 'administrator' => $administrator->id]) ?>"
                                   class="btn btn-light border" title="Voir le profil">
                                    <i class="fas fa-eye text-primary"></i>
                                </a>
                                <?php if ($this->params['administrator']->root && $administrator->id != 1 && !$isCurrentUser): ?>
                                    <a href="<?= Yii::$app->urlManager->createUrl(['administrator/supprimer-admin', 'q' => $administrator->id]) ?>" 
                                       class="btn btn-outline-danger"
                                       title="Supprimer"
                                       onclick="return confirm('Voulez-vous vraiment supprimer cet administrateur ?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-user-shield fa-4x text-muted opacity-25 mb-3"></i>
                        <p class="text-muted fs-5">Aucun administrateur trouvé</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>