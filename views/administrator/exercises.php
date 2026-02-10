<?php use yii\widgets\LinkPager;

$this->beginBlock('title') ?>
Exercices
<?php $this->endBlock() ?>
<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="<?= Yii::getAlias('@web/css/admin-styles.css') ?>">
<style>
    :root {
        --primary-color: #2196F3;
        --secondary-color: #607D8B;
        --success-color: #4CAF50;
        --danger-color: #f44336;
        --warning-color: #FFC107;
        --text-muted: #6c757d;
    }

    .white-block {
        padding: 2rem;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        margin-bottom: 2rem;
    }

    .white-block:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .session-header {
        text-align: center;
        padding: 2rem 0;
    }

    .session-title {
        font-size: 1.5rem;
        color: var(--secondary-color);
        margin-bottom: 1rem;
    }

    .session-amount {
        font-size: 3rem;
        font-weight: 600;
        color: var(--primary-color);
        margin: 1rem 0;
    }

    .session-subtitle {
        color: var(--text-muted);
        font-size: 1.2rem;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 500;
        margin: 0.5rem;
        transition: all 0.3s ease;
    }

    .status-active {
        background-color: var(--success-color);
        color: white;
    }

    .status-inactive {
        background-color: var(--secondary-color);
        color: white;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 1rem 0;
    }

    .modern-table thead th {
        background-color: #f8f9fa;
        color: var(--secondary-color);
        font-weight: 600;
        padding: 1rem;
        border-bottom: 2px solid #e9ecef;
    }

    .modern-table tbody tr {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .modern-table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
    }

    .modern-table td, .modern-table th {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .form-control {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
    }

    .btn {
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--text-muted);
    }
    
    .renflouement-complete {
        color: #4CAF50 !important; /* Vert pour payé */
        font-weight: 600;
    }
    
    .renflouement-incomplete {
        color: #f44336 !important; /* Rouge pour non payé */
        font-weight: 600;
    }
    
    .status-icon {
        margin-left: 0.5rem;
    }
</style>
<?php $this->endBlock() ?>

<?php // Utilisation du nouveau partiel d'en-tête ?>
<?= $this->render('_page_header', ['title' => 'Exercices']) ?>

<div class="container mt-5 mb-5">
    <div class="row">
        <?php if(count($exercises)): ?>
            <?php
            $exercise = $exercises[0];
            $members = \app\models\Member::find()->all();  ?>
            
            <!-- CASE 'EN COURS' -->
            <div class="col-12 white-block mb-4">
                <div class="session-header">
                    <div class="session-title">
                        Exercice de l'année
                        <span class="status-badge <?= $exercise->active ? 'status-active' : 'status-inactive' ?>">
                            <?= $exercise->active ? 'En cours' : 'Terminé' ?>
                        </span>
                    </div>
                    <!-- Note: Le montant et le sous-titre faisaient partie de la "case" visuelle, je les garde car c'est un bloc cohérent -->
                    <div class="session-amount">
                        <?= number_format($exercise->exerciseAmount() ?: 0, 0, ',', ' ') ?> XAF
                    </div>
                    <div class="session-subtitle">Fond total</div>
                </div>
            </div>


            <!-- BOUTON IMPRIMER & ACTIONS -->
            <div class="col-12 mb-4 text-center">
                 <div class="d-flex justify-content-center gap-3">
                    <?php if ($exercise->active): ?>
                        <?php if ($exercise->canBeClosed()): ?>
                            <a href="<?= Yii::$app->urlManager->createUrl(['administrator/cloturer-exercice', 'q' => $exercise->id]) ?>"
                               class="btn btn-danger btn-lg"
                               data-confirm="Êtes-vous sûr de vouloir clôturer cet exercice ? Cette action est irréversible et générera les renflouements."
                               style="margin-right: 10px;"
                               >
                                <i class="fas fa-lock"></i> Clôturer l'exercice
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                         <a href="<?= Yii::$app->urlManager->createUrl(['administrator/renflouements', 'q' => $exercise->id]) ?>"
                           class="btn btn-warning btn-lg"
                           style="margin-right: 10px;"
                           >
                            <i class="fas fa-money-bill-wave"></i> Voir Renflouements
                        </a>
                    <?php endif; ?>

                     <a href="<?= Yii::$app->urlManager->createUrl(['administrator/print-report', 'type' => 'exercise']) ?>" 
                       class="btn btn-primary btn-lg" 
                       target="_blank">
                        <i class="fas fa-print"></i> Imprimer le bilan de l'exercice (Fond social)
                    </a>
                 </div>
            </div>

            <!-- TABLEAU -->
            <?php if (count($members)): ?>
                <div class="col-12 white-block">
                    <h3 class="text-center my-4 blue-text">Bilan de l'exercice</h3>

                    <!-- BARRE DE RECHERCHE -->
                    <div class="row mb-3 justify-content-end">
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" id="memberSearchInput" class="form-control" placeholder="Rechercher un membre...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
                            <tr>
                                <th>Membre</th>
                                <th>Montant épargné</th>
                                <th>Montant emprunté</th>
                                <th>Dette remboursée</th>
                                <th>Intérêt sur les dettes</th>
                                <th>Inscription</th>
                                <th>Fond Social</th>
                                <th>Reste F. Social</th>
                                <th>Renflouement</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($members as $member): ?>
                                <?php
                                $user = $member->user();
                                $savedAmount = $member->savedAmount($exercise);
                                $borrowedAmount = $member->borrowedAmount($exercise);
                                $refundedAmount = $member->refundedAmount($exercise);
                                $interest = $member->interest($exercise);
                                $sc = $member->social_crown;
                                $insc = $member->inscription;

                            // CALCUL DU RENFLOUEMENT
                              $montantRenflouementTotal = \app\managers\SettingManager::getSocialCrown();
                              $montantRenflouementPaye = $sc;
                              $montantRenflouementRestant = $montantRenflouementTotal - $montantRenflouementPaye;
    
                            // DÉTERMINER SI C'EST PAYÉ COMPLÈTEMENT
                              $renflouementComplete = ($montantRenflouementRestant <= 0);
    
                            // CLASSE CSS À APPLIQUER
                            $renflouementClass = $renflouementComplete ? 'renflouement-complete' : 'renflouement-incomplete';

                            // DÉTERMINER SI RÉGLÉ (INSCRIPTION)
                            $montantInscriptionTotal = \app\managers\SettingManager::getInscription();
                            $inscriptionComplete = ($insc >= $montantInscriptionTotal);

                                $labels[] = $user->name . " " . $user->first_name;
                                $data[] = $interest ?: 0;
                                $colors[] = \app\managers\ColorManager::getColor();
                                ?>
                                <tr>
                                    <td class="text-capitalize member-name"><?= $user->name . " " . $user->first_name ?></td>
                                    <td><?= number_format($savedAmount ?: 0, 0, ',', ' ') ?> XAF</td>
                                    <td><?= number_format($borrowedAmount ?: 0, 0, ',', ' ') ?> XAF</td>
                                    <td><?= number_format($refundedAmount ?: 0, 0, ',', ' ') ?> XAF</td>
                                    <td class="blue-text"><?= number_format($interest ?: 0, 0, ',', ' ') ?> XAF</td>
                                    <td class="blue-text"><?= number_format($insc ?: 0, 0, ',', ' ') ?> XAF</td>
                                    <td class="blue-text"><?= number_format($sc ?: 0, 0, ',', ' ') ?> XAF</td>
                                    
                                     <!-- COLONNE RESTE FOND SOCIAL -->
                                     <td class="<?= $renflouementClass ?>">
                                       <?= number_format($montantRenflouementRestant, 0, ',', ' ') ?> XAF
                                       <?php if ($renflouementComplete): ?>
                                           <i class="fas fa-check-circle status-icon"></i>
                                        <?php else: ?>
                                           <i class="fas fa-exclamation-circle status-icon"></i>
                                        <?php endif; ?>
                                     </td>

                                     <!-- COLONNE RENFLOUEMENT REEL -->
                                     <?php 
                                        // Chercher le renflouement de l'exercice PRECEDENT qui doit être payé dans CET exercice
                                        $renflouementModel = \app\models\Renflouement::findOne([
                                            'member_id' => $member->id, 
                                            'next_exercise_id' => $exercise->id
                                        ]);
                                     ?>
                                     <td>
                                        <?php if ($renflouementModel): ?>
                                            <span class="<?= $renflouementModel->status == 'paye' ? 'text-success' : 'text-danger' ?>">
                                            <?= number_format($renflouementModel->getRemainingAmount(), 0, ',', ' ') ?> XAF
                                            </span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                     </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-12">
                <nav aria-label="Navigation des pages">
                    <?= LinkPager::widget([
                        'pagination' => $pagination,
                        'options' => [
                            'class' => 'pagination pagination-circle justify-content-center pg-blue mb-0',
                        ],
                        'pageCssClass' => 'page-item',
                        'disabledPageCssClass' => 'd-none',
                        'prevPageCssClass' => 'page-item',
                        'nextPageCssClass' => 'page-item',
                        'firstPageCssClass' => 'page-item',
                        'lastPageCssClass' => 'page-item',
                        'linkOptions' => ['class' => 'page-link']
                    ]) ?>
                </nav>
            </div>
        <?php else: ?>
            <div class="col-12 white-block empty-state">
                <i class="fas fa-calendar-times fa-3x mb-3" style="color: var(--warning-color);"></i>
                <h4>Aucun exercice créé</h4>
                <p class="text-muted mt-3 mb-4">
                    Pour commencer à utiliser la mutuelle, vous devez d'abord créer un exercice en ajoutant une première session.
                </p>
                <a href="<?= Yii::$app->urlManager->createUrl(['administrator/accueil']) ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus-circle"></i> Créer le premier exercice
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $this->beginBlock('script') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('memberSearchInput');
    const table = document.querySelector('.modern-table'); 
    
    if(table) {
        const tbody = table.querySelector('tbody');
        const rows = tbody.getElementsByTagName('tr');

        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();

            for (let i = 0; i < rows.length; i++) {
                const nameCell = rows[i].querySelector('.member-name');
                if (nameCell) {
                    const txtValue = nameCell.textContent || nameCell.innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        rows[i].style.display = "";
                    } else {
                        rows[i].style.display = "none";
                    }
                }
            }
        });
    }
});
</script>
<?php $this->endBlock() ?>