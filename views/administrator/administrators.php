<?php $this->beginBlock('title') ?>
    Administrateurs
<?php $this->endBlock() ?>
<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="<?= Yii::getAlias('@web/css/admin-styles.css') ?>">
<?php $this->endBlock() ?>

<div class="page-container">
    <div class="container">
        <h1 class="section-title">Liste des Administrateurs</h1>
        
        <div class="row">
            <?php if (count($administrators)): ?>
                <?php foreach ($administrators as $administrator): 
                    $user = $administrator->user();
                ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="card admin-card">
                            <img class="admin-profile-img"
                                 src="<?= \app\managers\FileManager::loadAvatar($user, "256") ?>"
                                 alt="Photo de profil">
                            
                            <h4 class="admin-name text-capitalize">
                                <?= $administrator->username ?>
                                <?php if ($user->id == $this->params['user']->id): ?>
                                    <span class="text-secondary">(Vous)</span>
                                <?php endif; ?>
                            </h4>
                            
                            <div class="admin-info">
                                <p><i class="fas fa-phone me-2"></i><?= $user->tel ?></p>
                                <p><i class="fas fa-envelope me-2"></i><?= $user->email ?></p>
                                <p><i class="fas fa-map-marker-alt me-2"></i><?= $user->address ?></p>
                            </div>
                            
                            <div class="admin-actions">
                                <?php if ($administrator->id != 1): ?>
                                    <a href="<?= Yii::getAlias("@administrator.administrator") ?>?administrator=<?= $administrator->id ?>"
                                       class="btn btn-primary">
                                        <i class="fas fa-eye me-2"></i>Voir
                                    </a>
                                    <?php if ($this->params['administrator']->root): ?>
                                        <button type="button"
                                                onclick="deleteAdministrator(<?= $administrator->id ?>, '<?= $administrator->username ?>')"
                                                class="btn btn-danger">
                                            <i class="fas fa-trash me-2"></i>Supprimer
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        Aucun administrateur trouvé
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ($this->params['administrator']->root): ?>
            <a href="<?= Yii::getAlias("@administrator.new_administrator") ?>" 
               class="btn btn-primary position-fixed" 
               style="bottom: 2rem; right: 2rem; z-index: 1000;">
                <i class="fas fa-plus me-2"></i>Ajouter un Administrateur
            </a>
        <?php endif; ?>
    </div>
</div>