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
            <?php 
            // Check if exercise is active and valid
            if (isset($exercise) && $exercise instanceof \app\models\Exercise): 
                // Inscription Payment Button
                if ($member->inscription < $exercise->inscription_amount):
            ?>
                <button class="btn btn-primary" data-toggle="modal" data-target="#modal-pay-inscription-<?= $member->id ?>">
                    <i class="fas fa-file-signature me-2"></i>Payer Inscription
                </button>
            <?php 
                endif; 
                
                // Social Fund Payment Button
                if ($member->social_crown < $exercise->social_crown_amount):
            ?>
                <button class="btn btn-info text-white" data-toggle="modal" data-target="#modal-pay-social-<?= $member->id ?>">
                    <i class="fas fa-hand-holding-heart me-2"></i>Payer Fond Social
                </button>
            <?php 
                endif;
            endif; 
            ?>
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

    <?php if (isset($exercise) && $exercise instanceof \app\models\Exercise): ?>
        <!-- Modal Paiement Inscription -->
        <?php if ($member->inscription < $exercise->inscription_amount): ?>
        <div class="modal fade" id="modal-pay-inscription-<?= $member->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <?php
                    $form = \yii\widgets\ActiveForm::begin([
                        'errorCssClass' => 'text-secondary',
                        'method' => 'post',
                        'action' => ['@administrator.fix_inscription', 'id' => $member->id],
                        'options' => [
                            'class' => 'col-12',
                            'data-current-amount' => $member->inscription,
                            'data-max-amount' => $exercise->inscription_amount - $member->inscription,
                        ],
                    ]);
                    ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Paiement Inscription</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <p class="text-muted">Membre : <strong><?= htmlspecialchars($user->name . " " . $user->first_name) ?></strong></p>
                            <p class="mb-1">Déjà payé : <span class="text-success"><?= number_format($member->inscription, 0, ',', ' ') ?> XAF</span></p>
                            <p>Reste à payer : <span class="text-danger font-weight-bold"><?= number_format($exercise->inscription_amount - $member->inscription, 0, ',', ' ') ?> XAF</span></p>
                        </div>

                        <?= $form->field($inscriptionModel ?? new \app\models\forms\FixInscriptionForm(), 'amount')->input('number', [
                            'required' => 'required',
                            'min' => 1,
                            'max' => $exercise->inscription_amount - $member->inscription,
                            'class' => 'form-control',
                            'placeholder' => 'Montant à payer'
                        ])->label("Montant à payer (XAF)") ?>

                        <?= $form->field($inscriptionModel ?? new \app\models\forms\FixInscriptionForm(), 'id')->hiddenInput(['value' => $member->id])->label(false) ?>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-circle me-2"></i>Valider
                        </button>
                    </div>
                    <?php \yii\widgets\ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Modal Paiement Fond Social -->
        <?php if ($member->social_crown < $exercise->social_crown_amount): ?>
        <div class="modal fade" id="modal-pay-social-<?= $member->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <?php
                    $formSocial = \yii\widgets\ActiveForm::begin([
                        'errorCssClass' => 'text-secondary',
                        'method' => 'post',
                        'action' => ['@administrator.fix_social_crown', 'id' => $member->id],
                        'options' => [
                            'data-max-fund' => $exercise->social_crown_amount - $member->social_crown,
                        ]
                    ]);
                    ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Paiement Fond Social</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <div class="text-center mb-4">
                            <p class="text-muted">Membre : <strong><?= htmlspecialchars($user->name . " " . $user->first_name) ?></strong></p>
                            <p class="mb-1">Déjà payé : <span class="text-success"><?= number_format($member->social_crown, 0, ',', ' ') ?> XAF</span></p>
                            <p>Reste à payer : <span class="text-danger font-weight-bold"><?= number_format($exercise->social_crown_amount - $member->social_crown, 0, ',', ' ') ?> XAF</span></p>
                        </div>

                        <?= $formSocial->field($socialModel ?? new \app\models\forms\FixSocialCrownForm(), 'amount')->input('number', [
                            'required' => 'required',
                            'min' => 1,
                            'max' => $exercise->social_crown_amount - $member->social_crown,
                            'class' => 'form-control',
                            'placeholder' => 'Montant à payer'
                        ])->label("Montant à payer (XAF)") ?>

                        <?= $formSocial->field($socialModel ?? new \app\models\forms\FixSocialCrownForm(), 'id')->hiddenInput(['value' => $member->id])->label(false) ?>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-info text-white">
                            <i class="fas fa-check-circle me-2"></i>Valider
                        </button>
                    </div>
                    <?php \yii\widgets\ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Script de validation pour les formulaires -->
        <script>
        // Use event delegation or check if script is already running to avoid duplicates if partial loaded multiple times
        // Simple check for now
        if (typeof paymentValidationInitialized === 'undefined') {
            document.addEventListener('submit', function(e) {
                if (e.target.matches('form[data-max-amount]')) {
                    const form = e.target;
                    const input = form.querySelector('input[name="FixInscriptionForm[amount]"]');
                    const maxAmount = parseInt(form.dataset.maxAmount, 10);
                    if (parseInt(input.value, 10) > maxAmount) {
                        e.preventDefault();
                        alert(`Le montant saisi dépasse le montant restant (${maxAmount} XAF).`);
                    }
                }
                if (e.target.matches('form[data-max-fund]')) {
                    const form = e.target;
                    const input = form.querySelector('input[name="FixSocialCrownForm[amount]"]');
                    const maxFund = parseInt(form.dataset.maxFund, 10);
                    if (parseInt(input.value, 10) > maxFund) {
                        e.preventDefault();
                        alert(`Le montant saisi dépasse le montant restant (${maxFund} XAF).`);
                    }
                }
            });
            window.paymentValidationInitialized = true;
        }
        </script>
    <?php endif; ?>
</div>
