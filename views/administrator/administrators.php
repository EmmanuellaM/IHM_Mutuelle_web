<?php $this->beginBlock('title') ?>
    Administrateurs
<?php $this->endBlock() ?>
<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="<?= Yii::getAlias('@web/css/admin-styles.css') ?>">
<?php $this->endBlock() ?>

<div class="page-container">
    <div class="container">
        <h1 class="section-title mb-4">Liste des Administrateurs</h1>
        
        <div class="row g-4">
            <?php if (count($administrators)): ?>
                <?php foreach ($administrators as $administrator): 
                    $user = $administrator->user();
                ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="admin-card">
                            <img class="admin-profile-img"
                                 src="<?= \app\managers\FileManager::loadAvatar($user, "256") ?>"
                                 alt="Photo de profil de <?= htmlspecialchars($administrator->username) ?>">
                            
                            <h4 class="admin-name">
                                <?= htmlspecialchars($administrator->username) ?>
                                <?php if ($user->id == $this->params['user']->id): ?>
                                    <span class="badge bg-secondary ms-2">Vous</span>
                                <?php endif; ?>
                            </h4>
                            
                            <div class="admin-info">
                                <?php if ($user->tel): ?>
                                    <p><i class="fas fa-phone"></i><?= htmlspecialchars($user->tel) ?></p>
                                <?php endif; ?>
                                <?php if ($user->email): ?>
                                    <p><i class="fas fa-envelope"></i><?= htmlspecialchars($user->email) ?></p>
                                <?php endif; ?>
                                <?php if ($user->address): ?>
                                    <p><i class="fas fa-map-marker-alt"></i><?= htmlspecialchars($user->address) ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($administrator->id != 1): ?>
                                <div class="admin-actions">
                                    <a href="<?= Yii::getAlias("@administrator.administrator") ?>?administrator=<?= $administrator->id ?>"
                                       class="btn btn-primary">
                                        <i class="fas fa-eye me-2"></i>Voir
                                    </a>
                                    <?php if ($this->params['administrator']->root): ?>
                                        <button type="button"
                                                onclick="deleteAdministrator(<?= $administrator->id ?>, '<?= htmlspecialchars($administrator->username) ?>')"
                                                class="btn btn-danger">
                                            <i class="fas fa-trash me-2"></i>Supprimer
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>Aucun administrateur trouvé
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($this->params['administrator']->root): ?>
            <div class="d-flex justify-content-end mt-4">
                <a href="<?= Yii::getAlias("@administrator.new_administrator") ?>" 
                   class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Ajouter un Administrateur
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>