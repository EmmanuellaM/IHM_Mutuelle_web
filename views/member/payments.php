<?php
use yii\helpers\Html;
use app\models\Member;
use app\models\Exercise;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $member app\models\Member */

$this->title = 'Mes Paiements';

// Récupérer le membre et l'exercice actif
$user = \Yii::$app->user->identity;
$member = Member::findOne(['user_id' => $user->id]);
$exercise = Exercise::findOne(['active' => true]);
?>

<?php $this->beginBlock('style'); ?>
<style>
    :root {
        --primary-color: #2193b0;
        --secondary-color: #6dd5ed;
        --success-color: #27ae60;
        --info-color: #3498db;
        --warning-color: #f39c12;
    }

    .payments-dashboard {
        padding: 1rem 0;
    }

    .section-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .section-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-header h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .section-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .section-body {
        padding: 1.5rem;
    }

    .payment-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s ease;
    }

    .payment-item:hover {
        background: #f8f9fa;
    }

    .payment-item:last-child {
        border-bottom: none;
    }

    .payment-info {
        flex: 1;
    }

    .payment-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.3rem;
    }

    .payment-date {
        font-size: 0.85rem;
        color: #666;
    }

    .payment-amount {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--success-color);
        text-align: right;
    }

    .payment-status {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-left: 1rem;
    }

    .status-paid {
        background: #d4edda;
        color: #155724;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #999;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    .summary-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: linear-gradient(135deg, var(--info-color), var(--primary-color));
        color: white;
        padding: 1.5rem;
        border-radius: 10px;
        text-align: center;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
</style>
<?php $this->endBlock(); ?>

<div class="container-fluid payments-dashboard">
    <!-- En-tête avec statistiques -->
    <div class="summary-stats">
        <div class="stat-card">
            <div class="stat-value"><?= number_format($member->inscription, 0, ',', ' ') ?> XAF</div>
            <div class="stat-label">Inscription Payée</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, var(--success-color), #27ae60);">
            <div class="stat-value"><?= number_format($member->social_crown, 0, ',', ' ') ?> XAF</div>
            <div class="stat-label">Fond Social Payé</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, var(--warning-color), #e67e22);">
            <div class="stat-value"><?= $dataProvider->totalCount ?></div>
            <div class="stat-label">Paiements Mobiles</div>
        </div>
    </div>

    <!-- Section : Paiements Uniques -->
    <div class="section-card">
        <div class="section-header">
            <h3><i class="fas fa-star mr-2"></i>Paiements Uniques</h3>
            <span class="section-badge">Paiements initiaux</span>
        </div>
        <div class="section-body">
            <!-- Inscription -->
            <div class="payment-item">
                <div class="payment-info">
                    <div class="payment-title">Frais d'Inscription</div>
                    <div class="payment-date">
                        <?php if ($member->inscription > 0): ?>
                            Payé le <?= date('d/m/Y', $member->created_at) ?>
                        <?php else: ?>
                            Non payé
                        <?php endif; ?>
                    </div>
                </div>
                <div class="payment-amount"><?= number_format($member->inscription, 0, ',', ' ') ?> XAF</div>
                <span class="payment-status <?= $member->inscription > 0 ? 'status-paid' : 'status-pending' ?>">
                    <?= $member->inscription > 0 ? '✓ Payé' : 'En attente' ?>
                </span>
            </div>

            <!-- Fond Social -->
            <div class="payment-item">
                <div class="payment-info">
                    <div class="payment-title">Fond Social</div>
                    <div class="payment-date">
                        <?php if ($member->social_crown > 0): ?>
                            Payé le <?= date('d/m/Y', $member->created_at) ?>
                        <?php else: ?>
                            Non payé
                        <?php endif; ?>
                    </div>
                </div>
                <div class="payment-amount"><?= number_format($member->social_crown, 0, ',', ' ') ?> XAF</div>
                <span class="payment-status <?= $member->social_crown > 0 ? 'status-paid' : 'status-pending' ?>">
                    <?= $member->social_crown > 0 ? '✓ Payé' : 'En attente' ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Section : Paiements Mobiles -->
    <?php if ($dataProvider->totalCount > 0): ?>
        <div class="section-card">
            <div class="section-header">
                <h3><i class="fas fa-mobile-alt mr-2"></i>Paiements Mobiles</h3>
                <span class="section-badge"><?= $dataProvider->totalCount ?> paiement(s)</span>
            </div>
            <div class="section-body">
                <?php foreach ($dataProvider->models as $payment): ?>
                    <div class="payment-item">
                        <div class="payment-info">
                            <div class="payment-title">
                                <?= Html::encode($payment->payment_method) ?>
                                <small class="text-muted">(#<?= Html::encode($payment->payment_id) ?>)</small>
                            </div>
                            <div class="payment-date">
                                <?= $payment->getFormattedDate('created_at') ?>
                                <?php if ($payment->phone_number): ?>
                                    • <?= Html::encode($payment->phone_number) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="payment-amount"><?= number_format($payment->amount, 0, ',', ' ') ?> XAF</div>
                        <span class="payment-status status-paid">✓ Payé</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Message si aucun paiement mobile -->
    <?php if ($dataProvider->totalCount == 0): ?>
        <div class="section-card">
            <div class="section-header">
                <h3><i class="fas fa-mobile-alt mr-2"></i>Paiements Mobiles</h3>
                <span class="section-badge">0 paiement</span>
            </div>
            <div class="section-body">
                <div class="empty-state">
                    <i class="fas fa-receipt"></i>
                    <p>Aucun paiement mobile enregistré pour le moment</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
