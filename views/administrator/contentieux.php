<?php
/** @var $defaultBorrowings app\models\Borrowing[] */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Gestion des Contentieux";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Emprunts en Défaut de Paiement</h3>
            </div>
            <div class="panel-body">
                <?php if (empty($defaultBorrowings)): ?>
                    <div class="alert alert-success">
                        Aucun contentieux en cours. Tous les emprunts sont conformes.
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <strong>Attention !</strong> Les emprunts ci-dessous sont en défaut (Retard > 6 mois et couverture épargne insuffisante).
                        Veuillez appliquer la pénalité pour régulariser ou déclarer l'insolvabilité.
                    </div>

                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Emprunt ID</th>
                            <th>Membre</th>
                            <th>Date Emprunt</th>
                            <th>Montant Emprunté</th>
                            <th>Reste à Payer</th>
                            <th>Epargne Actuelle</th>
                            <th>Retard (Sessions)</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($defaultBorrowings as $borrowing): 
                            $member = $borrowing->member;
                            $sessionsElapsed = $borrowing->getSessionsElapsed();
                            $remaining = $borrowing->getRemainingAmount();
                            $savings = $member->savedAmount($borrowing->session->exercise);
                        ?>
                            <tr>
                                <td>#<?= $borrowing->id ?></td>
                                <td><?= Html::encode($member->name . ' ' . $member->first_name) ?></td>
                                <td><?= $borrowing->session->date() ?></td>
                                <td><?= number_format($borrowing->amount) ?> XAF</td>
                                <td class="text-danger"><strong><?= number_format($remaining) ?> XAF</strong></td>
                                <td><?= number_format($savings) ?> XAF</td>
                                <td><?= $sessionsElapsed ?> sessions</td>
                                <td>
                                    <?php if ($borrowing->last_penalty_session_id == \app\models\Session::findOne(['active'=>true])->id): ?>
                                        <span class="label label-success">Pénalité Appliquée</span>
                                    <?php else: ?>
                                        <a href="<?= Url::to(['administrator/appliquer-penalite', 'id' => $borrowing->id]) ?>" 
                                           class="btn btn-danger btn-sm"
                                           data-confirm="Êtes-vous sûr de vouloir appliquer la pénalité sur l'épargne de ce membre ? Cela pourrait le rendre insolvable.">
                                            <i class="glyphicon glyphicon-warning-sign"></i> Appliquer Pénalité
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
