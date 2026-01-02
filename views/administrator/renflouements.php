<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $exercise app\models\Exercise */
/* @var $renflouements app\models\Renflouement[] */

$this->title = 'Renflouement de l\'exercice ' . $exercise->year;
$this->params['breadcrumbs'][] = ['label' => 'Exercices', 'url' => ['exercices']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="white-block">
            <h3 class="section-title text-center">Renflouement de l'exercice <?= $exercise->year ?></h3>
            <hr>
            
            <div class="alert alert-info">
                <strong>Info:</strong> Montant total à recouvrer par membre : 
                <strong><?= number_format($renflouements ? $renflouements[0]->amount : 0, 0, ',', ' ') ?> XAF</strong>
                <br>
                <?php if ($renflouements): ?>
                    <?php 
                        $elapsed = $renflouements[0]->getSessionsElapsed();
                        $remaining = max(0, 3 - $elapsed);
                    ?>
                    Délai : <strong>3 sessions</strong> (Sessions écoulées : <?= $elapsed ?> / Restantes : <?= $remaining ?>)
                    <?php if ($remaining == 0): ?>
                        <br><span class="text-danger"><strong>Attention :</strong> La prochaine session entraînera la désactivation des membres impayés.</span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php if (empty($renflouements)): ?>
                <div class="alert alert-warning text-center">Aucun renflouement à afficher pour cet exercice.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="blue-grey lighten-4">
                            <tr>
                                <th>Membre</th>
                                <th>Montant Total</th>
                                <th>Déjà Payé</th>
                                <th>Reste à Payer</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($renflouements as $renflouement): ?>
                                <?php 
                                    $member = $renflouement->member;
                                    $user = $member->user();
                                    $remaining = $renflouement->getRemainingAmount();
                                ?>
                                <tr>
                                    <td><?= Html::encode($user->name . ' ' . $user->first_name) ?></td>
                                    <td><?= number_format($renflouement->amount, 0, ',', ' ') ?> XAF</td>
                                    <td class="text-success"><?= number_format($renflouement->paid_amount, 0, ',', ' ') ?> XAF</td>
                                    <td class="text-danger font-weight-bold"><?= number_format($remaining, 0, ',', ' ') ?> XAF</td>
                                    <td>
                                        <?php if ($renflouement->status === \app\models\Renflouement::STATUS_PAID): ?>
                                            <span class="badge badge-success">Payé</span>
                                        <?php elseif ($remaining < $renflouement->amount): ?>
                                            <span class="badge badge-warning">Partiel</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Impayé</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($remaining > 0): ?>
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#payModal<?= $renflouement->id ?>">
                                                <i class="fas fa-money-bill-wave"></i> Payer
                                            </button>
                                            
                                            <!-- Modal Paiement -->
                                            <div class="modal fade" id="payModal<?= $renflouement->id ?>" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Payer le renflouement</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <?php $form = ActiveForm::begin([
                                                                'action' => ['administrator/regler-renflouement', 'id' => $renflouement->id],
                                                                'method' => 'post',
                                                            ]); ?>
                                                            
                                                            <div class="form-group">
                                                                <label>Montant à payer (Max: <?= $remaining ?>)</label>
                                                                <?= Html::input('number', 'amount', $remaining, [
                                                                    'class' => 'form-control', 
                                                                    'max' => $remaining,
                                                                    'min' => 1,
                                                                    'required' => true
                                                                ]) ?>
                                                                <!-- Note: Using simple input since we handle generic post in controller or I can use Form model if prefered -->
                                                                <!-- Using simple input name 'FixRenflouementForm[amount]' to match model load -->
                                                                <input type="hidden" name="FixRenflouementForm[amount]" id="fixrenflouementform-amount-<?= $renflouement->id ?>" >
                                                                <script>
                                                                    // Simple script to map name if needed, but actually ActiveForm field is better.
                                                                    // Let's rely on standard form field if I had used $model.
                                                                    // Since I don't have a model instance here easily without loop, I will use manual name.
                                                                    // The controller expects $model->load(Yii::$app->request->post()).
                                                                    // So name should be FixRenflouementForm[amount].
                                                                </script>
                                                            </div>
                                                            <!-- Correct Input -->
                                                            <div class="form-group">
                                                                <input type="number" name="FixRenflouementForm[amount]" class="form-control" value="<?= $remaining ?>" max="<?= $remaining ?>" min="1" required>
                                                            </div>

                                                            <div class="form-group text-right">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                                <button type="submit" class="btn btn-primary">Valider</button>
                                                            </div>

                                                            <?php ActiveForm::end(); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form').forEach(form => {
            const input = form.querySelector('input[name="FixRenflouementForm[amount]"]');
            if (input) {
                form.addEventListener('submit', event => {
                    const maxAmount = parseFloat(input.getAttribute('max'));
                    const enteredValue = parseFloat(input.value);

                    if (enteredValue > maxAmount) {
                        event.preventDefault();
                        alert(`Le montant saisi dépasse le montant restant (${maxAmount} XAF). Veuillez corriger.`);
                    }
                });
            }
        });
    });
</script>
