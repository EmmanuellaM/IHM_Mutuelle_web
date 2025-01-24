<?php
use app\managers\MemberSessionManager;
use app\managers\SettingManager;
use yii\helpers\Url; // Import pour générer des URLs
/** @var $member app\models\Member */
/** @var $socialCrownTarget int */
$this->title = 'Ma Dette | Fond Social';
?>

<?php $this->beginBlock('style'); ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .card-container {
            margin-top: 50px;
        }

        .debt-card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 10px;
            overflow: hidden;
            background-color: #ffffff;
        }

        .debt-header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .debt-body {
            padding: 20px;
            text-align: center;
        }

        .debt-amount {
            font-size: 2rem;
            color: #dc3545;
            font-weight: bold;
        }

        .btn-pay {
            margin-top: 20px;
        }
    </style>
<?php $this->endBlock(); ?>

<div class="container card-container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card debt-card">
                <div class="debt-header">
                    <h3>Ma Situation de Renflouement</h3>
                </div>
                <div class="debt-body">
                    <p class="mb-4">Bonjour <strong><?= htmlspecialchars($member->user()->name) ?></strong>,</p>
                    
                    <p>Le montant total à payer pour le <strong>Fond Social</strong> est :</p>
                    <div class="debt-amount"><?= number_format($socialCrownTarget, 0, ',', ' ') ?> XAF</div>

                    <p>Montant déjà réglé :</p>
                    <div class="debt-amount text-success">
                        <?= number_format($member->social_crown, 0, ',', ' ') ?> XAF
                    </div>

                    <p class="mt-4">Montant restant :</p>
                    <div class="debt-amount">
                        <?= number_format($socialCrownTarget - $member->social_crown, 0, ',', ' ') ?> XAF
                    </div>

                    <!-- Bouton pour diriger vers un paiement -->
                    <a href="#" class="btn btn-primary btn-lg btn-pay">Régler ma dette</a>
                </div>
            </div>
        </div>
    </div>
</div>
