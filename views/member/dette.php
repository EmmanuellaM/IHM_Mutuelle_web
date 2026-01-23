<?php
use app\models\Member;
use app\models\FinancialAid;
use app\managers\MemberSessionManager;
use app\managers\SettingManager;
use yii\helpers\Url;
/** @var $member app\models\Member */

// Récupération du membre connecté
//$member = Member::findOne(Yii::$app->user->id);( pas correst)




// ✅ CORRECT - Cherche le membre par user_id
$member = Member::find()
    ->where(['user_id' => Yii::$app->user->id])
    ->one();




    

// Récupération de l'exercice actif
$exercise = \app\models\Exercise::find()
    ->where(['active' => true])
    ->one();

// Calcul du montant de renfouement
$fondSocial = $member->social_crown;
$inscription = $member->inscription;

// Calcul de la somme des aides financières de l'année achevée
$currentYear = date('Y');
$totalAides = FinancialAid::find()
    ->where(['member_id' => $member->id])
    ->andWhere('EXTRACT(YEAR FROM date) = :year', [':year' => $currentYear - 1])
    ->sum('amount') ?? 0;

// Calcul des montants
$montantFondSocialTotal = $exercise->social_crown_amount;
$montantFondSocialPaye = $fondSocial;
$montantFondSocialReste = $montantFondSocialTotal - $montantFondSocialPaye;

$montantInscriptionTotal = $exercise->inscription_amount;
$montantInscriptionPaye = $inscription;
$montantInscriptionReste = $montantInscriptionTotal - $montantInscriptionPaye;

$registrationAmount = $exercise->inscription_amount;
$socialFundAmount = $member->getSocialFundAmount($exercise);
$borrowedAmount = $member->borrowedAmount($exercise);
$refundedAmount = $member->refundedAmount($exercise);
$socialCrownTarget = $exercise->social_crown_amount;
?>

<?php $this->beginBlock('style'); ?>
<style>
    :root {
        --primary-color: #2193b0;
        --secondary-color: #6dd5ed;
        --success-color: #27ae60;
        --danger-color: #e74c3c;
        --warning-color: #f1c40f;
    }

    .debt-dashboard {
        padding: 1rem 0;
    }

    .debt-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
        transition: transform 0.3s ease;
    }

    .debt-card:hover {
        transform: translateY(-3px);
    }

    .debt-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1rem;
        border-radius: 10px 10px 0 0;
        position: relative;
        overflow: hidden;
    }

    .debt-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30%, 30%);
    }

    .debt-header h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .debt-body {
        padding: 1rem;
    }

    .amount-display {
        text-align: center;
        padding: 0.5rem;
        margin: 0.5rem 0;
        border-radius: 8px;
        background: #f8f9fa;
    }

    .amount-label {
        color: #666;
        font-size: 0.85rem;
        margin-bottom: 0.3rem;
    }

    .amount-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--primary-color);
    }

    .amount-paid {
        color: var(--success-color);
    }

    .amount-remaining {
        color: var(--danger-color);
    }

    .btn-pay {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 0.5rem;
        text-decoration: none;
    }

    .btn-pay:hover {
        background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        transform: scale(1.02);
        color: white;
        text-decoration: none;
    }

    .debt-summary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }

    .debt-summary h2 {
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .progress {
        height: 8px;
        border-radius: 4px;
        margin: 0.5rem 0;
        background-color: rgba(255, 255, 255, 0.2);
    }

    .progress-bar {
        background: var(--secondary-color);
    }

    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 25px;
        font-size: 0.8rem;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.4);
    }

    .status-active {
        color: white;
    }

    .status-completed {
        background: var(--success-color);
        color: white;
    }
</style>
<?php $this->endBlock(); ?>

<div class="container-fluid debt-dashboard">
    <!-- En-tête avec résumé -->
    <div class="debt-summary">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2>Tableau de Bord des Dettes</h2>
                <p class="mb-0">Bienvenue, <strong><?= htmlspecialchars($member->user()->name) ?></strong></p>
            </div>
            <div class="col-md-4 text-end">
                <span class="status-badge <?= $member->active ? 'status-active' : 'status-inactive' ?>">
                    <?= $member->active ? "Compte en règle" : "Compte irrégulier" ?>
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Carte Fond Social -->
        <div class="col-md-4">
            <div class="debt-card">
                <div class="debt-header">
                    <h3>Fond Social</h3>
                </div>
                <div class="debt-body">
                    <div class="amount-display">
                        <div class="amount-label">Montant Total</div>
                        <div class="amount-value"><?= number_format($montantFondSocialTotal, 0, ',', ' ') ?> XAF</div>
                    </div>
                    
                    <div class="amount-display">
                        <div class="amount-label">Déjà Payé</div>
                        <div class="amount-value amount-paid"><?= number_format($montantFondSocialPaye, 0, ',', ' ') ?> XAF</div>
                    </div>

                    <div class="amount-display">
                        <div class="amount-label">Reste à Payer</div>
                        <div class="amount-value amount-remaining">
                            <?= number_format($montantFondSocialReste, 0, ',', ' ') ?> XAF
                        </div>
                    </div>

                    <div class="progress">
                        <div class="progress-bar" role="progressbar" 
                             style="width: <?= ($montantFondSocialPaye > 0 ? ($montantFondSocialPaye / $montantFondSocialTotal) * 100 : 0) ?>%">
                        </div>
                    </div>

                    <a href="<?= Url::to(['/member/process-payment', 'type' => 'social']) ?>" class="btn btn-pay">
                        Régler le Fond Social
                    </a>
                </div>
            </div>
        </div>

        <!-- Carte Inscription -->
        <div class="col-md-4">
            <div class="debt-card">
                <div class="debt-header">
                    <h3>Frais d'Inscription</h3>
                </div>
                <div class="debt-body">
                    <div class="amount-display">
                        <div class="amount-label">Montant Total</div>
                        <div class="amount-value">
                            <?= number_format($montantInscriptionTotal, 0, ',', ' ') ?> XAF
                        </div>
                    </div>

                    <div class="amount-display">
                        <div class="amount-label">Déjà Payé</div>
                        <div class="amount-value amount-paid">
                            <?= number_format($montantInscriptionPaye, 0, ',', ' ') ?> XAF
                        </div>
                    </div>

                    <div class="amount-display">
                        <div class="amount-label">Reste à Payer</div>
                        <div class="amount-value amount-remaining">
                            <?= number_format($montantInscriptionReste, 0, ',', ' ') ?> XAF
                        </div>
                    </div>

                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 100%"></div>
                    </div>

                    <a href="<?= Url::to(['/member/process-payment', 'type' => 'registration']) ?>" class="btn btn-pay">
                        Régler l'Inscription
                    </a>
                </div>
            </div>
        </div>

        <!-- Carte Contributions aux Aides -->
        <div class="col-md-4">
            <div class="debt-card">
                <div class="debt-header">
                    <h3>Contributions aux Aides</h3>
                </div>
                <div class="debt-body">
                    <?php 
                    $unpaidContributions = $member->getUnpaidHelpContributions();
                    $totalContributionsDue = $member->getTotalHelpContributionsDue();
                    $totalContributionsPaid = $member->getTotalHelpContributionsPaid();
                    $contributionsCount = $member->getUnpaidHelpContributionsCount();
                    ?>
                    
                    <div class="amount-display">
                        <div class="amount-label">Nombre d'aides à payer</div>
                        <div class="amount-value"><?= $contributionsCount ?></div>
                    </div>

                    <div class="amount-display">
                        <div class="amount-label">Total à Payer</div>
                        <div class="amount-value amount-remaining">
                            <?= number_format($totalContributionsDue, 0, ',', ' ') ?> XAF
                        </div>
                    </div>

                    <div class="amount-display">
                        <div class="amount-label">Déjà Payé</div>
                        <div class="amount-value amount-paid">
                            <?= number_format($totalContributionsPaid, 0, ',', ' ') ?> XAF
                        </div>
                    </div>

                    <div class="progress">
                        <div class="progress-bar" role="progressbar" 
                             style="width: <?= ($totalContributionsDue + $totalContributionsPaid > 0 ? ($totalContributionsPaid / ($totalContributionsDue + $totalContributionsPaid)) * 100 : 0) ?>%">
                        </div>
                    </div>

                    <?php if ($contributionsCount > 0): ?>
                        <a href="<?= Url::to(['/member/help-contributions']) ?>" class="btn btn-pay">
                            Voir mes Contributions
                        </a>
                    <?php else: ?>
                        <div class="text-center mt-3" style="color: var(--success-color); font-weight: 600;">
                            ✅ Aucune contribution en attente
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Carte Emprunts -->
        <div class="col-md-4">
            <div class="debt-card">
                <div class="debt-header">
                    <h3>Emprunts</h3>
                </div>
                <div class="debt-body">
                    <div class="amount-display">
                        <div class="amount-label">Total des Emprunts</div>
                        <div class="amount-value">
                            <?= number_format($borrowedAmount, 0, ',', ' ') ?> XAF
                        </div>
                    </div>

                    <div class="amount-display">
                        <div class="amount-label">Déjà Remboursé</div>
                        <div class="amount-value amount-paid">
                            <?= number_format($refundedAmount, 0, ',', ' ') ?> XAF
                        </div>
                    </div>

                    <div class="amount-display">
                        <div class="amount-label">Reste à Rembourser</div>
                        <div class="amount-value amount-remaining">
                            <?= number_format($borrowedAmount - $refundedAmount, 0, ',', ' ') ?> XAF
                        </div>
                    </div>

                    <div class="progress">
                        <div class="progress-bar" role="progressbar" 
                             style="width: <?= ($borrowedAmount > 0 ? ($refundedAmount / $borrowedAmount) * 100 : 0) ?>%">
                        </div>
                    </div>

                    <a href="<?= Url::to(['/member/process-payment', 'type' => 'loan']) ?>" class="btn btn-pay">
                        Rembourser un Emprunt
                    </a>
                </div>
            </div>

            <div class="text-center">
                <a href="#" class="btn btn-pay">Régler ma dette</a>
            </div>
        </div>
    </div>
</div>
