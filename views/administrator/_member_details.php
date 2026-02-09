<?php
$user = $member->user();
?>
<div class="member-profile-detail">
    <div class="member-header mb-4">
        <div class="d-flex align-items-center">
            <img src="<?= \app\managers\FileManager::loadAvatar($user,"256")?>" 
                 alt="Photo de profil de <?= htmlspecialchars($user->name.' '.$user->first_name) ?>" 
                 class="member-avatar rounded-circle me-3" style="width: 100px; height: 100px; object-fit: cover;">
            <div>
                <h2 class="member-name mb-1">
                    <?= htmlspecialchars($user->name.' '.$user->first_name) ?>
                </h2>
                <div class="text-muted small">
                    <i class="fas fa-user-tag me-1"></i><?= htmlspecialchars($member->username) ?>
                    <span class="badge <?= $member->active ? 'bg-success' : 'bg-danger' ?> ms-2">
                        <?= $member->active ? 'En règle' : 'Irrégulier' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="info-grid row g-3">
        <div class="col-md-6">
            <div class="info-item p-3 border rounded h-100">
                <div class="info-label text-muted small fw-bold text-uppercase">Email</div>
                <div class="info-value"><?= htmlspecialchars($user->email ?: 'Non renseigné') ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-item p-3 border rounded h-100">
                <div class="info-label text-muted small fw-bold text-uppercase">Téléphone</div>
                <div class="info-value"><?= htmlspecialchars($user->tel ?: 'Non renseigné') ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-item p-3 border rounded h-100">
                <div class="info-label text-muted small fw-bold text-uppercase">Adresse</div>
                <div class="info-value"><?= htmlspecialchars($user->address ?: 'Non renseignée') ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-item p-3 border rounded h-100">
                <div class="info-label text-muted small fw-bold text-uppercase">Inscription</div>
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

    <div class="member-activities mt-4">
        <h5 class="mb-3"><i class="fas fa-history me-2"></i>Activités récentes</h5>
        <?php 
        $exercises = \app\models\Exercise::find()->orderBy("created_at",SORT_DESC)->limit(5)->all();
        if (count($exercises)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Année</th>
                            <th>Inscription</th>
                            <th>Fonds social</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($exercises as $exercise): 
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
            <div class="alert alert-light border">
                <i class="fas fa-info-circle me-2"></i>Aucun exercice enregistré.
            </div>
        <?php endif; ?>
    </div>

    <div class="d-flex flex-wrap gap-2 mt-4 justify-content-between">
        <div class="d-flex gap-2">
            <a href="<?= Yii::getAlias("@administrator.member")."?q=".$member->id ?>" 
               class="btn btn-primary" style="background-color: #4e73df; border-color: #4e73df;">
                <i class="fas fa-external-link-alt me-2"></i>Voir profil complet
            </a>
        </div>
        <div class="d-flex gap-2">
            <?php if ($member->active): ?>
                <a href="<?= Yii::getAlias("@administrator.disable_member")."?q=".$member->id ?>" 
                   class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-user-slash me-1"></i>Désactiver
                </a>
            <?php else: ?>
                <a href="<?= Yii::getAlias("@administrator.enable_member")."?q=".$member->id ?>" 
                   class="btn btn-outline-success btn-sm">
                    <i class="fas fa-user-check me-1"></i>Activer
                </a>
            <?php endif; ?>
            
            <button class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#modal-delete-<?= $member->id ?>">
                <i class="fas fa-trash me-1"></i>Supprimer
            </button>
        </div>
    </div>

    <!-- Modal de confirmation unique pour ce membre si chargé en AJAX -->
    <div class="modal fade" id="modal-delete-<?= $member->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                    <h5>Supprimer <?= htmlspecialchars($user->name) ?> ?</h5>
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
