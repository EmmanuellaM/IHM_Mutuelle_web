<?php
use yii\helpers\Url;
use yii\helpers\Html;

$this->beginBlock('title');
echo 'Mes Contributions aux Aides';
$this->endBlock();

// Get member
$member = \app\models\Member::find()
    ->where(['user_id' => Yii::$app->user->id])
    ->one();

if (!$member) {
    throw new \yii\web\NotFoundHttpException('Membre non trouvé.');
}

$unpaidContributions = $member->getUnpaidHelpContributions();
?>

<?php $this->beginBlock('style'); ?>
<style>
    :root {
        --primary-color: #2193b0;
        --secondary-color: #6dd5ed;
        --success-color: #27ae60;
        --danger-color: #e74c3c;
    }

    .contributions-container {
        padding: 2rem 0;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }

    .contribution-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .contribution-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .contribution-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1rem 1.5rem;
    }

    .contribution-body {
        padding: 1.5rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #666;
        font-weight: 500;
    }

    .info-value {
        font-weight: 600;
        color: var(--primary-color);
    }

    .btn-pay-contribution {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        margin-top: 1rem;
    }

    .btn-pay-contribution:hover {
        background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        transform: scale(1.05);
        color: white;
        text-decoration: none;
    }

    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #666;
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--success-color);
        margin-bottom: 1rem;
    }
</style>
<?php $this->endBlock(); ?>

<div class="container contributions-container">
    <div class="page-header">
        <h2>Mes Contributions aux Aides</h2>
        <p class="mb-0">Gérez vos contributions aux aides financières de vos collègues</p>
    </div>

    <?php if (count($unpaidContributions) > 0): ?>
        <?php foreach ($unpaidContributions as $contribution): ?>
            <?php 
            $help = $contribution->help;
            if (!$help) continue;
            
            $helpType = $help->helpType();
            $beneficiary = $help->member()->user();
            ?>
            
            <div class="contribution-card">
                <div class="contribution-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-0"><?= Html::encode($helpType->title) ?></h4>
                            <small>Pour: <?= Html::encode($beneficiary->name . ' ' . $beneficiary->first_name) ?></small>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="status-badge status-pending">En attente</span>
                        </div>
                    </div>
                </div>
                
                <div class="contribution-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="info-row">
                                <span class="info-label">Montant total de l'aide:</span>
                                <span class="info-value"><?= number_format($help->amount, 0, ',', ' ') ?> XAF</span>
                            </div>
                            
                            <div class="info-row">
                                <span class="info-label">Pris en charge par le fonds social:</span>
                                <span class="info-value" style="color: var(--success-color);">
                                    <?= number_format($help->amount_from_social_fund, 0, ',', ' ') ?> XAF
                                </span>
                            </div>
                            
                            <div class="info-row">
                                <span class="info-label">Votre contribution:</span>
                                <span class="info-value" style="color: var(--danger-color); font-size: 1.2rem;">
                                    <?= number_format($help->unit_amount, 0, ',', ' ') ?> XAF
                                </span>
                            </div>
                            
                            <div class="info-row">
                                <span class="info-label">Date limite:</span>
                                <span class="info-value"><?= Yii::$app->formatter->asDate($help->limit_date, 'long') ?></span>
                            </div>
                            
                            <?php if ($help->comments): ?>
                            <div class="info-row">
                                <span class="info-label">Commentaire:</span>
                                <span class="info-value"><?= Html::encode($help->comments) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-4 text-center">
                            <div class="mt-3">
                                <h5>Montant à payer</h5>
                                <h2 style="color: var(--primary-color);">
                                    <?= number_format($help->unit_amount, 0, ',', ' ') ?> XAF
                                </h2>
                                <a href="<?= Url::to(['/member/process-payment', 'type' => 'help_contribution', 'contribution_id' => $contribution->id, 'amount' => $help->unit_amount]) ?>" 
                                   class="btn-pay-contribution">
                                    💳 Payer maintenant
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div class="text-center mt-4">
            <a href="<?= Url::to(['/member/dette']) ?>" class="btn btn-secondary">
                ← Retour à mes dettes
            </a>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-check-circle"></i>
            <h3>Aucune contribution en attente</h3>
            <p>Vous n'avez actuellement aucune contribution aux aides à payer.</p>
            <a href="<?= Url::to(['/member/dette']) ?>" class="btn btn-pay-contribution">
                ← Retour à mes dettes
            </a>
        </div>
    <?php endif; ?>
</div>
