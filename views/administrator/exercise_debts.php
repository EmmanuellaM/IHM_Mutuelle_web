<?php
use yii\helpers\Html;

$this->title = 'Dettes de l\'Exercice';
$this->params['breadcrumbs'][] = ['label' => 'Exercices', 'url' => ['exercices']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $this->beginBlock('style') ?>
<style>
    :root {
        --primary-color: #16a34a;
        --danger-color: #dc2626;
        --warning-color: #f59e0b;
        --text-dark: #1f2937;
        --text-muted: #6b7280;
        --background-light: #f8fafc;
        --border-radius: 12px;
        --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --transition: all 0.3s ease;
    }

    .info-card {
        background: white;
        padding: 2rem;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 2rem;
        transition: var(--transition);
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .exercise-info {
        font-size: 1.2rem;
        line-height: 1.8;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }

    .exercise-info strong {
        color: var(--primary-color);
        font-weight: 600;
    }

    .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th {
        background: #f8f9fa;
        font-weight: 600;
        color: var(--text-dark);
        padding: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .table tr:hover {
        background-color: #f8fafc;
    }

    .badge {
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-weight: 500;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .badge.bg-success {
        background-color: #16a34a;
        color: white;
    }

    .badge.bg-danger {
        background-color: #dc2626;
        color: white;
    }

    .badge.bg-warning {
        background-color: #f59e0b;
        color: white;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: var(--transition);
    }

    .btn-primary:hover {
        background-color: #118c4e;
        transform: translateY(-1px);
    }

    .modal-content {
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    .modal-header {
        border-bottom: 1px solid #e2e8f0;
    }

    .modal-footer {
        border-top: 1px solid #e2e8f0;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.75rem;
        transition: var(--transition);
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(22, 163, 73, 0.1);
    }

    .text-center {
        text-align: center;
    }

    .section-title {
        color: var(--text-dark);
        margin-bottom: 1rem;
    }

    .warning-block {
        background-color: #fefce8;
        border-left: 4px solid var(--warning-color);
        padding: 1rem;
        border-radius: var(--border-radius);
        margin: 1rem 0;
    }
</style>
<?php $this->endBlock() ?>

<div class="container-fluid py-5">
    <?php if (!$exercise): ?>
        <!-- Message quand aucun exercice n'existe -->
        <div class="row">
            <div class="col-12">
                <div class="info-card text-center">
                    <i class="fas fa-info-circle" style="font-size: 4rem; color: #f59e0b; margin-bottom: 1rem;"></i>
                    <h3 class="section-title">Aucun exercice disponible</h3>
                    <p class="text-muted" style="font-size: 1.1rem; margin-bottom: 2rem;">
                        Vous devez d'abord créer un exercice pour pouvoir consulter les dettes.
                    </p>
                    <a href="<?= Yii::$app->urlManager->createUrl(['administrator/accueil']) ?>" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Créer un exercice
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Messages Flash -->
        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i>
                <?= Yii::$app->session->getFlash('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        
        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <?= Yii::$app->session->getFlash('error') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        
        <?php if (Yii::$app->session->hasFlash('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                <?= Yii::$app->session->getFlash('warning') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        
        <!-- Contenu normal quand un exercice existe -->
    </div>





    <!-- Table des membres -->
    <div class="table-responsive">
        <div class="mb-3">
             <input type="text" id="memberSearchInput" class="form-control" placeholder="Rechercher un membre..." style="max-width: 300px;">
        </div>
        <table class="table table-striped" id="membersTable">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>État Inscription</th>
                    <th>État Fond Social</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member): ?>
                    <?php 
                        // FILTRE : Si tout est payé (Inscription + Fond Social), on n'affiche pas
                        $inscriptionPaid = ($member->inscription >= $exercise->inscription_amount);
                        $socialFundPaid = ($member->social_crown >= $exercise->social_crown_amount);
                        
                        if ($inscriptionPaid && $socialFundPaid) {
                            continue;
                        }
                    ?>
                    <tr>
                        <td class="member-name"><?= Html::encode($member->username) ?></td>
                        <td>
                            <?php if ($member->inscription >= $exercise->inscription_amount): ?>
                                <span class="badge bg-success">Payé</span>
                            <?php else: ?>
                                <span class="badge bg-danger">En retard</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($member->social_crown >= $exercise->social_crown_amount): ?>
                                <span class="badge bg-success">Payé</span>
                            <?php else: ?>
                                <span class="badge bg-danger">En retard</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php
// Utiliser la première session de l'exercice
$firstSession = !empty($sessions) ? $sessions[0] : null;

if ($firstSession) {
    $refunds = \app\models\Refund::find()
        ->where(['is not','exercise_id',null])
        ->andWhere(['session_id' => $firstSession->id])
        ->all();

    $members = \app\models\Member::find()
        ->where(['<','inscription', $exercise->inscription_amount])
        ->distinct()
        ->all();

    if (count($members)) {
?>
<div class="container-fluid mb-5 mt-5">
    <div class="row mb-2">
        <div class="col-12 white-block">
            <h3 class="text-center section-title">Inscriptions</h3>
            <hr>
            <table class="table table-hover">
                <thead class="blue-grey lighten-4">
                    <tr>
                        <th>#</th>
                        <th>Membre</th>
                        <th>montant réglé</th>
                        <th>Montant restant à payer</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $index => $member): ?>
                        <?php
                        $memberUser = $member->user();
                        ?>
                        <tr>
                            <th scope="row"><?= $index + 1 ?></th>
                            <td class="text-capitalize"><?= $memberUser->name . " " . $memberUser->first_name ?></td>
                            <td class="text-success fw-bold"><?= number_format($member->inscription, 0, ',', ' ') ?> XAF</td>
                            <td class="text-danger fw-bold"><?= number_format($exercise->inscription_amount - $member->inscription, 0, ',', ' ') ?> XAF</td>
                            <td>
                                <button class="btn btn-primary" data-target="#modalS<?= $member->id ?>" data-toggle="modal">
                                    <i class="fas fa-money-bill-wave"></i> Payer
                                </button>
                            </td>
                        </tr>

                        <!-- Modal pour le paiement -->
                        <div class="modal fade" id="modalS<?= $member->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <?php
                                    $model = new \app\models\forms\FixInscriptionForm();
                                    $form = \yii\widgets\ActiveForm::begin([
                                        'errorCssClass' => 'text-secondary',
                                        'method' => 'post',
                                        'action' => ['@administrator.fix_inscription', 'id' => $member->id],
                                        'options' => [
                                            'class' => 'col-12 white-block',
                                            'data-current-amount' => $member->inscription,
                                            'data-max-amount' => $exercise->inscription_amount - $member->inscription,
                                        ],
                                    ]);
                                    ?>

                                    <div class="text-center mb-4">
                                        <h4 class="mb-3">Paiement de l'inscription</h4>
                                        <p class="text-muted">Pour le membre : <?= $memberUser->name . " " . $memberUser->first_name ?></p>
                                    </div>
                                    <?= $form->field($model, 'amount')->input('number', [
                                        'required' => 'required',
                                        'min' => 1,
                                        'max' => $exercise->inscription_amount - $member->inscription,
                                        'class' => 'form-control',
                                        'placeholder' => 'Montant à payer'
                                    ])->label("Montant à payer") ?>
                                    <?= $form->field($model, 'id')->hiddenInput(['value' => $member->id])->label(false) ?>
                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-check-circle"></i> Valider le paiement
                                        </button>
                                    </div>
                                    <?php \yii\widgets\ActiveForm::end(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
    } else {
        echo '<p class="text-center blue-text">Aucune dette de règlement d\'inscription</p>';
    }
} else {
    echo '<p class="text-center blue-text">Aucune session disponible</p>';
}
?>
<div class="container-fluid">
    <div class="row">
            <div class="col-12 white-block">
                <h3 class="text-center section-title">Fond Social</h3>
                <hr>

                <?php
                $members = \app\models\Member::find()
                    ->where(['<', 'social_crown', $exercise->social_crown_amount])
                    ->all();
                if (count($members)):
                ?>
                <table class="table table-hover">
                    <thead class="blue-grey lighten-4">
                    <tr>
                        <th>#</th>
                        <th>Membre</th>
                        <th>montant réglé</th>
                        <th>Montant restant à payer</th>
                        <th>action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($members as $index => $member): ?>
                        <?php
                        $memberUser = $member->user();
                        ?>
                            <tr>
                                <th scope="row"><?= $index + 1 ?></th>
                                <td class="text-capitalize"><?= $memberUser->name . " " . $memberUser->first_name ?></td>
                                 <td class="blue-text"><?= number_format($member->social_crown, 0, ',', ' ') ?> XAF</td>

                                <td class="red-text"><?= number_format($exercise->social_crown_amount - $member->social_crown, 0, ',', ' ') ?> XAF</td>
                                    <td><button class="btn btn-primary p-2 m-0" data-target="#modalFondSocial<?= $member->id ?>" data-toggle="modal">payer </button></td>

                            </tr>



                        <div class="modal fade" id="modalFondSocial<?= $member->id?>" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <?php
                                    $modelFondSocial = new \app\models\forms\FixSocialCrownForm();
                                    $formFondSocial = \yii\widgets\ActiveForm::begin([
                                        'errorCssClass' => 'text-secondary',
                                        'method' => 'post',
                                        'action' => ['@administrator.fix_social_crown', 'id' => $member->id],
                                        'options' => [
                                            'class' => 'col-12 white-block',
                                            'data-max-fund' => $exercise->social_crown_amount - $member->social_crown,
                                        ]
                                    ]);
                                    ?>

                                    <div class="text-center mb-4">
                                        <h4 class="mb-3">Paiement du fond social</h4>
                                        <p class="text-muted">Pour le membre : <?= $memberUser->name . " " . $memberUser->first_name ?></p>
                                    </div>
                                    <?= $formFondSocial->field($modelFondSocial, 'amount')->input('number', [
                                        'required' => 'required',
                                        'min' => 1,
                                        'max' => $exercise->social_crown_amount - $member->social_crown,
                                        'class' => 'form-control',
                                        'placeholder' => 'Montant à payer'
                                    ])->label("Montant à payer") ?>
                                    <?= $formFondSocial->field($modelFondSocial, 'id')->hiddenInput(['value' => $member->id])->label(false) ?>
                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-check-circle"></i> Valider le paiement
                                        </button>
                                    </div>
                                    <?php \yii\widgets\ActiveForm::end(); ?>
                                </div>
                            </div>
                        </div>


                    <?php endforeach; ?>
                    </tbody>
                </table>

                <?php
                else:
                ?>
                <p class="text-center blue-text">Aucune dette de règlement de fond social</p>
                <?php
                endif;
                ?>
            </div>
    </div>



    <div class="row mt-4">
        <div class="col-12 white-block">
            <h3 class="text-muted text-center section-title">Dettes d'exercices</h3>
            <hr>
            <p class="warning-block text-center">
                Attention ! Il s'agit des emprunts d'exercices précédents qui n'ont pas été complètement remboursés.
            </p>

            <?php
            if (isset($unpaidBorrowings) && count($unpaidBorrowings)):
            ?>
            <table class="table table-hover">
                <thead class="blue-grey lighten-4">
                <tr>
                    <th>#</th>
                    <th>Membre</th>
                    <th>Montant Emprunté</th>
                    <th>Montant Remboursé</th>
                    <th>Reste à Payer</th>
                    <th>Année de l'exercice</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($unpaidBorrowings as $index => $borrowing): ?>
                    <?php 
                    $member = \app\models\Member::findOne($borrowing->member_id);
                    $memberUser = \app\models\User::findOne($member->user_id);
                    $session = \app\models\Session::findOne($borrowing->session_id);
                    $exerciseYear = \app\models\Exercise::findOne($session->exercise_id);
                    
                    $intendedAmount = $borrowing->intendedAmount();
                    $refundedAmount = $borrowing->refundedAmount();
                    $remainingAmount = $intendedAmount - $refundedAmount;
                    ?>
                    <tr>
                        <th scope="row"><?= $index + 1 ?></th>
                        <td class="text-capitalize"><?= $memberUser->name . " " . $memberUser->first_name ?></td>
                        <td class="text-primary fw-bold"><?= number_format($borrowing->amount, 0, ',', ' ') ?> XAF</td>
                        <td class="text-success fw-bold"><?= number_format($refundedAmount, 0, ',', ' ') ?> XAF</td>
                        <td class="text-danger fw-bold"><?= number_format($remainingAmount, 0, ',', ' ') ?> XAF</td>
                        <td class="text-capitalize"><?= $exerciseYear->year ?></td>
                        <td>
                            <a href="<?= Yii::getAlias("@administrator.borrowings_details") . "?member_id=" . $member->id . "&session_id=" . $session->id ?>" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> Voir Détails
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <?php
            else:
            ?>
            <h3 class="text-muted text-center">Aucune dette d'exercice enregistrée</h3>

            <?php
            endif;
            ?>
        </div>

    </div>
</div>

<?php endif; // Fin de la condition if (!$exercise) ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form[data-max-amount]').forEach(form => {
            form.addEventListener('submit', event => {
                const input = form.querySelector('input[name="FixInscriptionForm[amount]"]');
                const maxAmount = parseInt(form.dataset.maxAmount, 10);

                if (parseInt(input.value, 10) > maxAmount) {
                    event.preventDefault();
                    alert(`Le montant saisi dépasse le montant restant (${maxAmount} XAF). Veuillez corriger.`);
                }
            });
        });
    });
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form[data-max-fund]').forEach(form => {
            form.addEventListener('submit', event => {
                const input = form.querySelector('input[name="FixSocialCrownForm[amount]"]');
                const maxFund = parseInt(form.dataset.maxFund, 10);

                if (parseInt(input.value, 10) > maxFund ) {
                    event.preventDefault();
                    alert(`Le montant saisi dépasse le montant restant (${maxFund } XAF). Veuillez corriger.`);
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('memberSearchInput');
        const table = document.getElementById('membersTable');
        
        if(table && searchInput) {
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