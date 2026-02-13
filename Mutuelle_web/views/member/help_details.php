<?php

$this->beginBlock('title') ?>
    Détails de l'Aide
<?php $this->endBlock() ?>

<?php $this->beginBlock('style') ?>
<style>
    .help-details-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        padding: 2rem 0;
    }
    .help-card {
        background-color: white;
        border-radius: 1.5rem;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    .help-card:hover {
        transform: translateY(-10px);
    }
    .help-header {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        text-align: center;
        padding: 2rem;
    }
    .help-avatar {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.2);
    }
    .help-info-section {
        padding: 2rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .help-progress-container {
        background-color: #f0f0f0;
        border-radius: 10px;
        height: 15px;
        margin-top: 1rem;
    }
    .help-progress-bar {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        height: 100%;
        border-radius: 10px;
    }
    .help-contribution-card {
        background-color: #f8f9fa;
        border-radius: 1rem;
        padding: 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        transition: transform 0.3s ease;
    }
    .help-contribution-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    .help-contribution-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 1rem;
        object-fit: cover;
    }
    .pending-member-card {
        display: flex;
        align-items: center;
        background-color: #f8f9fa;
        border-radius: 1rem;
        padding: 0.75rem;
        margin-bottom: 1rem;
    }
    .pending-member-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 1rem;
        object-fit: cover;
    }
</style>
<?php $this->endBlock() ?>

<?php
$member = $help->member;  // Sans parenthèses
$user = $member ? $member->user : null;  // Vérification de l'existence de $member
$helpType = $help->helpType;
?>

<div class="help-details-container">
    <div class="container">
        <div class="help-card">
            <div class="help-header">
                <h2><?= $helpType->title ?></h2>
            </div>

            <div class="help-info-section">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="<?= $user ? \app\managers\FileManager::loadAvatar($user,"512") : 'default-avatar.jpg' ?>" alt="Avatar" class="help-avatar mb-3">
                        <h3 class="text-primary"><?= $user ? $user->name." ".$user->first_name : 'Utilisateur non trouvé' ?></h3>
                    </div>
                    <div class="col-md-8">
                        <p class="text-muted mb-4"><?= $help->comments ?></p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">Montant de l'aide</small>
                                <h4 class="text-primary mb-3"><?= $help->amount ?> XAF</h4>
                                
                                <small class="text-muted">Contribution par membre</small>
                                <h5 class="text-secondary"><?= $help->unit_amount ?> XAF</h5>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Contributions reçues</small>
                                <?php 
                                $contributedAmount = $help->getContributedAmount() ?: 0;
                                $progressPercentage = $help->amount ? round(($contributedAmount / $help->amount) * 100) : 0;
                                ?>
                                <h4 class="text-secondary mb-2"><?= $contributedAmount ?> XAF</h4>
                                
                                <div class="help-progress-container">
                                    <div class="help-progress-bar" style="width: <?= $progressPercentage ?>%"></div>
                                </div>
                                <small class="text-muted"><?= $progressPercentage ?>% collecté</small>
                            </div>
                        </div>
                        
                        <div class="text-right mt-3">
                            <small class="text-muted">Créée le : <?= $help->created_at ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="help-info-section">
                <h4 class="text-center text-muted mb-4">Détails des Contributions</h4>
                <?php
                $contributions = $help->contributions;
                if (count($contributions)):
                ?>
                    <div class="row">
                        <?php foreach ($contributions as $index => $contribution): ?>
                            <?php 
                            $m = $contribution->member;
                            $u = $m ? $m->user : null;
                            $a = $contribution->member;
                            $adminUser = $a ? $a->user : null;
                            ?>
                            <div class="col-md-6">
                                <div class="help-contribution-card">
                                    <img src="<?= $u ? \app\managers\FileManager::loadAvatar($u) : 'default-avatar.jpg' ?>" 
                                         alt="Avatar" class="help-contribution-avatar">
                                    <div>
                                        <h6 class="mb-1"><?= $u ? $u->name . " " . $u->first_name : 'Membre non trouvé' ?></h6>
                                        <small class="text-muted">
                                            <?= (new DateTime($contribution->date))->format("d-m-Y") ?> 
                                            | <?= $adminUser ? $adminUser->name . ' ' . $adminUser->first_name : 'Administrateur inconnu' ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted p-4">
                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                        <p>Aucune contribution pour le moment</p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($help->state): ?>
                <div class="help-info-section">
                    <h4 class="text-center text-muted mb-4">Membres en Attente de Contribution</h4>
                    <div class="row">
                        <?php
                        foreach ($help->waitedContributions as $contribution):
                            $member = $contribution->member;
                            $user = $member ? $member->user() : null;
                        ?>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="pending-member-card">
                                    <img src="<?= $user ? \app\managers\FileManager::loadAvatar($user) : 'default-avatar.jpg' ?>" 
                                         alt="Avatar" class="pending-member-avatar">
                                    <span><?= $user ? $user->name.' '.$user->first_name : 'Utilisateur inconnu' ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
