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

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">État de mes Dettes</h2>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Montant Total du Renfouement</h5>
                                    <h3 class="card-text"><?= number_format($montantRenfouement, 0, ',', ' ') ?> FCFA</h3>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Montant Déjà Payé</h5>
                                    <h3 class="card-text"><?= number_format($montantPaye, 0, ',', ' ') ?> FCFA</h3>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Reste à Payer</h5>
                                    <h3 class="card-text"><?= number_format($resteAPayer, 0, ',', ' ') ?> FCFA</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button class="btn btn-primary btn-lg" <?= $resteAPayer <= 0 ? 'disabled' : '' ?>>
                            <i class="fas fa-money-bill-wave mr-2"></i>
                            Effectuer un Paiement
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
