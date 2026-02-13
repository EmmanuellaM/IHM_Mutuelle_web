<?php
$user = $member->user();
?>
<?php $this->beginBlock('title') ?>
<?= $user->name." ".$user->first_name ?>
<?php $this->endBlock()?>
<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="<?= Yii::getAlias('@web/css/admin-styles.css') ?>">
<?php $this->endBlock()?>

<div class="page-container">
    <div class="container">
        <div class="member-profile">
            <div class="member-header">
                <img src="<?= \app\managers\FileManager::loadAvatar($user,"256")?>" 
                     alt="Photo de profil de <?= htmlspecialchars($user->name.' '.$user->first_name) ?>" 
                     class="member-avatar">
                <div>
                    <h2 class="member-name">
                        <?= htmlspecialchars($member->username) ?>
                        <span class="badge <?= $member->active ? 'bg-success' : 'bg-danger' ?> ms-2">
                            <?= $member->active ? 'Actif' : 'Inactif' ?>
                        </span>
                    </h2>
                    <div class="text-muted small">
                        Membre depuis le <?= (new DateTime($user->created_at))->format('d/m/Y') ?>
                    </div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nom :</div>
                    <div class="info-value"><?= htmlspecialchars($user->name) ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Prénom :</div>
                    <div class="info-value"><?= htmlspecialchars($user->first_name) ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Email :</div>
                    <div class="info-value"><?= htmlspecialchars($user->email) ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Tél :</div>
                    <div class="info-value"><?= htmlspecialchars($user->tel) ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Adresse :</div>
                    <div class="info-value"><?= htmlspecialchars($user->address) ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Inscriptions :</div>
                    <div class="info-value">
                        <?php if ($member->social_crown): ?>
                            <span class="badge bg-success">
                                <?= number_format($member->social_crown, 0, ',', ' ') ?> XAF
                            </span>
                        <?php else: ?>
                            <span class="badge bg-warning">Non payé</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="member-tabs">
            <ul class="nav nav-tabs mt-4">
                <li class="nav-item">
                    <a class="nav-link active" href="<?= Yii::getAlias("@administrator.member")."?q=".$member->id ?>">
                        <i class="fas fa-user me-2"></i>Général
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Yii::getAlias("@administrator.saving_member")."?q=".$member->id ?>">
                        <i class="fas fa-piggy-bank me-2"></i>Épargnes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Yii::getAlias("@administrator.borrowing_member")."?q=".$member->id ?>">
                        <i class="fas fa-hand-holding-usd me-2"></i>Emprunts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Yii::getAlias("@administrator.contribution_member")."?q=".$member->id ?>">
                        <i class="fas fa-donate me-2"></i>Contributions
                    </a>
                </li>
            </ul>
        </div>

        <div class="member-activities">
            <?php 
            $exercises = \app\models\Exercise::find()->orderBy("created_at",SORT_ASC)->all();
            if (count($exercises)): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Année</th>
                                <th>Montant d'inscription</th>
                                <th>Fonds social</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($exercises as $index => $exercise): 
                                $registration = $member->getRegistrationAmount($exercise);
                                $social = $member->getSocialFundAmount($exercise);
                                $total = $registration + $social;
                            ?>
                                <tr>
                                    <th scope="row"><?= $exercise->year ?></th>
                                    <td><?= number_format($registration, 0, ',', ' ') ?> XAF</td>
                                    <td><?= number_format($social, 0, ',', ' ') ?> XAF</td>
                                    <td><strong><?= number_format($total, 0, ',', ' ') ?> XAF</strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Aucun exercice enregistré.
                </div>
            <?php endif; ?>
        </div>

        <div class="d-flex justify-content-end mt-4 gap-2">
            <?php if ($member->active): ?>
                <a href="<?= Yii::getAlias("@administrator.disable_member")."?q=".$member->id ?>" 
                   class="btn btn-outline-danger">
                    <i class="fas fa-user-slash me-2"></i>Désactiver le membre
                </a>
            <?php else: ?>
                <a href="<?= Yii::getAlias("@administrator.enable_member")."?q=".$member->id ?>" 
                   class="btn btn-outline-success">
                    <i class="fas fa-user-check me-2"></i>Activer le membre
                </a>
            <?php endif; ?>
            
            <button class="btn btn-danger" data-toggle="modal" data-target="#modal">
                <i class="fas fa-trash me-2"></i>Supprimer
            </button>
        </div>

        <!-- Modal de confirmation -->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center p-4">
                        <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                        <h5>Êtes-vous sûr(e) de vouloir supprimer ce membre ?</h5>
                        <p class="text-muted">Cette action est irréversible.</p>
                        
                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Non
                            </button>
                            <a href="<?=Yii::getAlias("@administrator.delete_member")."?q=".$member->id?>" 
                               class="btn btn-danger">
                                <i class="fas fa-check me-2"></i>Oui
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
