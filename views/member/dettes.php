<?php

use yii\helpers\Html;
use app\models\Member;
use app\models\FinancialAid;

$this->title = "Mes Dettes - Mutuelle ENSPY";

// Récupération du membre connecté
$member = Member::findOne(Yii::$app->user->id);

// Calcul du montant de renfouement
$fondSocial = $member->social_crown;

// Calcul de la somme des aides financières de l'année achevée
$currentYear = date('Y');
$totalAides = FinancialAid::find()
    ->where(['member_id' => $member->id])
    ->andWhere(['YEAR(date)' => $currentYear - 1])
    ->sum('amount') ?? 0;

$montantRenfouement = $fondSocial - $totalAides;
$montantPaye = 0; // valeur par défaut, à remplacer par la valeur réelle
$resteAPayer = $montantRenfouement - $montantPaye;

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
                            <h5 class="card-title">Montant Total du Renfouement</h5>
                            <p class="amount"><?= number_format($montantRenfouement, 0, ',', ' ') ?> FCFA</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card success-card">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <h5 class="card-title">Montant Déjà Payé</h5>
                            <p class="amount"><?= number_format($montantPaye, 0, ',', ' ') ?> FCFA</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card warning-card">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                            <h5 class="card-title">Reste à Payer</h5>
                            <p class="amount"><?= number_format($resteAPayer, 0, ',', ' ') ?> FCFA</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <button class="btn btn-payment text-white" <?= $resteAPayer <= 0 ? 'disabled' : '' ?>>
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Effectuer un Paiement
                </button>
            </div>
        </div>
    </div>
</div>
