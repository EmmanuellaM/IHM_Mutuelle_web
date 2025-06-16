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

    .sessions-list {
        background: white;
        padding: 2rem;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 2rem;
    }

    .sessions-timeline {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .session-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        transition: var(--transition);
    }

    .session-item:hover {
        background: #f1f5f9;
    }

    .session-date {
        font-weight: 600;
        color: var(--text-dark);
    }

    .session-status {
        font-size: 0.9rem;
        color: var(--text-muted);
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
    <!-- Information de l'exercice -->
    <div class="info-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title mb-0">Exercice en cours</h2>
            <div>
                <a href="<?= Yii::$app->urlManager->createUrl(['administrator/print-report', 'type' => 'exercise']) ?>" 
                   class="btn btn-primary" 
                   target="_blank">
                    <i class="fas fa-print"></i> Imprimer le bilan de l'exercice
                </a>
            </div>
        </div>
        <div class="exercise-info">
            <p><strong>Année :</strong> <?= $exercise->year ?></p>
            <p><strong>Taux d'intérêt :</strong> <?= $exercise->interest ?>%</p>
            <p><strong>Montant inscription :</strong> <?= number_format($exercise->inscription_amount, 0, ',', ' ') ?> XAF</p>
            <p><strong>Montant fond social :</strong> <?= number_format($exercise->social_crown_amount, 0, ',', ' ') ?> XAF</p>
        </div>
    </div>

    <!-- Liste des sessions de l'exercice -->
    <div class="sessions-list">
        <h3 class="section-title">Sessions de l'exercice</h3>
        <div class="sessions-timeline">
            <?php foreach ($sessions as $session): ?>
                <div class="session-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="session-date mr-3"><?= Yii::$app->formatter->asDate($session->date, 'php:F Y') ?></div>
                        <div class="session-status">
                            <?php if ($session->active) {
                                echo '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Active</span>';
                            } else {
                                echo '<span class="badge bg-secondary"><i class="fas fa-times-circle"></i> Clôturée</span>';
                            } ?>
                        </div>
                    </div>
                    <div>
                        <a href="<?= Yii::$app->urlManager->createUrl(['administrator/print-report', 'type' => 'session', 'id' => $session->id]) ?>" 
                           class="btn btn-outline-primary btn-sm" 
                           target="_blank">
                            <i class="fas fa-print"></i> Imprimer
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Table des membres -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>État Inscription</th>
                    <th>État Fond Social</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member): ?>
                    <tr>
                        <td><?= Html::encode($member->username) ?></td>
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
<div class="container mb-5 mt-5">
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
                                    <td><button class="btn btn-primary p-2 m-0" data-target="#modalS<?= $member->id ?>" data-toggle="modal">payer </button></td>

                            </tr>


                        <div class="modal fade" id="modalS<?= $member->id?>" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">


                                    <?php                                    $model = new  \app\models\forms\FixSocialCrownForm();

                                     $form = \yii\widgets\ActiveForm::begin([
                                'errorCssClass' => 'text-secondary',
                                'method' => 'post',
                                'action' => ['@administrator.fix_social_crown', 'id'=>$member->id],
                                'options' => [
                                    'class' => 'col-12 white-block',
                                    'data-max-fund' => $exercise->social_crown_amount - $member->social_crown,
                                ]
                                ]) ?>

                            <h3> Veuillez entrer le montant à payer</h3>
                                <?= $form->field($model, 'amount')->input('number', ['required' => 'required', 'min' =>1])->label("montant") ?>
                                <?= $form->field($model,'id')->hiddenInput(['value'=>$member->id])->label(false) ?>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary" >valider </button>
                                
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



    <div class="row">
        <div class="col-12 white-block">
            <h3 class="text-muted text-center section-title">Dettes d'exercices</h3>
            <hr>
            <p class="warning-block text-center">
                Attention ! Il s'agit des dettes d'exercices qui n'ont pas été remboursées.
                
            </p>

            <?php
            if (count($refunds)):
            ?>
            <table class="table table-hover">
                <thead class="blue-grey lighten-4">
                <tr>
                    <th>#</th>
                    <th>Membre</th>
                    <th>Montant</th>
                    <th>Année de l'exercice</th>
                    <th></th>
                </tr>

                </thead>
                <tbody>
                <?php foreach ($refunds as $index => $refund): ?>
                    <?php $member = \app\models\Member::findOne((\app\models\Borrowing::findOne($refund->borrowing_id))->member_id);
                    $memberUser = \app\models\User::findOne($member->user_id);
                    $exercise = \app\models\Exercise::findOne($refund->exercise_id);
                    ?>
                    <tr>
                        <th scope="row"><?= $index + 1 ?></th>
                        <td class="text-capitalize"><?= $memberUser->name . " " . $memberUser->first_name ?></td>
                        <td class="blue-text"><?= $refund->amount ?> XAF</td>
                        <td class="text-capitalize"><?= $exercise->year ?></td>
                        <td><button class="btn btn-primary m-0 p-2" data-toggle="modal" data-target="#modal<?= $index?>">Regler</button></td>
                    </tr>


                <div class="modal  fade" id="modal<?= $index ?>" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-body">

                                <p class="text-center">Êtes-vous sûr(e) de vouloir régler la dette de ce membre?
                                </p>

                                <div class="form-group text-center">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
                                    <a href="<?= Yii::getAlias("@administrator.treat_debt")."?q=".$refund->id?>" class="btn btn-primary">Oui</a>
                                </div>
                            </div>
                        </div>
                    </div>

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
</script>