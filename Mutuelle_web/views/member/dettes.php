<?php

use yii\helpers\Html;
use app\models\Member;
use app\models\FinancialAid;

$this->title = "Mes Dettes - Mutuelle ENSPY";

// Récupération du membre connecté
$member = Member::findOne(Yii::$app->user->id);

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
    ->andWhere(['YEAR(date)' => $currentYear - 1])
    ->sum('amount') ?? 0;

// Calcul des montants
$montantFondSocialTotal = $exercise->social_crown_amount;
$montantFondSocialPaye = $fondSocial;
$montantFondSocialReste = $montantFondSocialTotal - $montantFondSocialPaye;

$montantInscriptionTotal = $exercise->inscription_amount;
$montantInscriptionPaye = $inscription;
$montantInscriptionReste = $montantInscriptionTotal - $montantInscriptionPaye;

?>

<!-- Ajout des styles personnalisés -->
<style>
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.info-card {
    height: 100%;
    padding: 1.5rem;
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.success-card {
    height: 100%;
    padding: 1.5rem;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
}

.warning-card {
    height: 100%;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f1c40f, #f39c12);
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.amount {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0;
}

.btn-payment {
    background: linear-gradient(135deg, #3498db, #2980b9);
    border: none;
    padding: 1rem 2rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-payment:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.main-title {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 2rem;
    position: relative;
    padding-bottom: 1rem;
}

.main-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 4px;
    background: linear-gradient(135deg, #3498db, #2980b9);
    border-radius: 2px;
}
</style>

<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="text-center main-title">État de mes Dettes</h2>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card info-card">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-coins fa-3x mb-3"></i>
                            <h5 class="card-title">Montant Total du Fond Social</h5>
                            <p class="amount"><?= number_format($montantFondSocialTotal, 0, ',', ' ') ?> FCFA</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card success-card">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <h5 class="card-title">Déjà Payé</h5>
                            <p class="amount"><?= number_format($montantFondSocialPaye, 0, ',', ' ') ?> FCFA</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card warning-card">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                            <h5 class="card-title">Reste à Payer</h5>
                            <p class="amount"><?= number_format($montantFondSocialReste, 0, ',', ' ') ?> FCFA</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <button class="btn btn-payment text-white" <?= $montantFondSocialReste <= 0 ? 'disabled' : '' ?>>
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Effectuer un Paiement
                </button>
            </div>

            <!-- Section Inscription -->
            <div class="row g-4 mt-5">
                <div class="col-md-4">
                    <div class="card info-card">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-file-invoice fa-3x mb-3"></i>
                            <h5 class="card-title">Montant Total de l'Inscription</h5>
                            <p class="amount"><?= number_format($montantInscriptionTotal, 0, ',', ' ') ?> FCFA</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card success-card">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <h5 class="card-title">Déjà Payé</h5>
                            <p class="amount"><?= number_format($montantInscriptionPaye, 0, ',', ' ') ?> FCFA</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card warning-card">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                            <h5 class="card-title">Reste à Payer</h5>
                            <p class="amount"><?= number_format($montantInscriptionReste, 0, ',', ' ') ?> FCFA</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <button class="btn btn-payment text-white" <?= $montantInscriptionReste <= 0 ? 'disabled' : '' ?>>
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Effectuer un Paiement
                </button>
            </div>
        </div>
    </div>
</div>
