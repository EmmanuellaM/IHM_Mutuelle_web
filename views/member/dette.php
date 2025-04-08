<?php
use app\managers\MemberSessionManager;
use app\managers\SettingManager;
use yii\helpers\Url;

/** @var $member app\models\Member */
/** @var $socialCrownTarget int */
$this->title = 'Ma Dette | Fond Social';
?>

<?php $this->beginBlock('style'); ?>
<style>
    .debt-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 200px);
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }
    .debt-card {
        width: 100%;
        max-width: 500px;
        background-color: white;
        border-radius: 1.5rem;
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    .debt-card:hover {
        transform: translateY(-10px);
    }
    .debt-header {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        text-align: center;
        padding: 2rem;
    }
    .debt-body {
        padding: 2rem;
        text-align: center;
    }
    .debt-section {
        margin-bottom: 1.5rem;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 1.5rem;
    }
    .debt-amount {
        font-size: 2rem;
        font-weight: bold;
        margin-top: 0.5rem;
    }
    .debt-total {
        color: #dc3545;
    }
    .debt-paid {
        color: #28a745;
    }
    .debt-remaining {
        color: #007bff;
    }
    .progress-container {
        background-color: #f0f0f0;
        border-radius: 10px;
        height: 15px;
        margin-top: 1rem;
    }
    .progress-bar {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        height: 100%;
        border-radius: 10px;
    }
    .btn-pay {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        border: none;
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: bold;
        transition: transform 0.3s ease;
    }
    .btn-pay:hover {
        transform: scale(1.05);
    }
</style>
<?php $this->endBlock(); ?>

<div class="debt-container">
    <div class="debt-card">
        <div class="debt-header">
            <h2>Situation du Fond Social</h2>
        </div>
        <div class="debt-body">
            <div class="debt-section">
                <p class="text-muted">Bonjour <strong><?= htmlspecialchars($member->user()->name) ?></strong>,</p>
                <p class="text-muted">Voici votre situation de renflouement.</p>
            </div>

            <div class="debt-section">
                <h5 class="text-muted">Montant Total</h5>
                <div class="debt-amount debt-total"><?= number_format($socialCrownTarget, 0, ',', ' ') ?> XAF</div>
                <div class="progress-container">
                    <?php 
                    $progressPercentage = $socialCrownTarget ? 
                        round(($member->social_crown / $socialCrownTarget) * 100) : 0;
                    ?>
                    <div class="progress-bar" style="width: <?= $progressPercentage ?>%"></div>
                </div>
            </div>

            <div class="debt-section">
                <div class="row">
                    <div class="col-6">
                        <h5 class="text-muted">Déjà Réglé</h5>
                        <div class="debt-amount debt-paid"><?= number_format($member->social_crown, 0, ',', ' ') ?> XAF</div>
                    </div>
                    <div class="col-6">
                        <h5 class="text-muted">Reste à Payer</h5>
                        <div class="debt-amount debt-remaining"><?= number_format($socialCrownTarget - $member->social_crown, 0, ',', ' ') ?> XAF</div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="#" class="btn btn-pay">Régler ma dette</a>
            </div>
        </div>
    </div>
</div>
