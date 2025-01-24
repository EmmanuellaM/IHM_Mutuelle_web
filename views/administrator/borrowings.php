<?php

use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->beginBlock('title') ?>
Epargnes
<?php $this->endBlock() ?>

<?php $this->beginBlock('style') ?>
<style>
    #btn-add {
        position: fixed!important;
        bottom: 25px;
        right: 25px;
        z-index: 1000;
        font-size: 0.8rem;
        padding: 10px;
    }
</style>
<?php $this->endBlock() ?>

<div class="container mt-5 mb-5">
    <div class="row">
        <?php if (count($sessions)): ?>
            <?php
            $activeSession = \app\models\Session::findOne(['active' => true]);
            $allSessions = \app\models\Session::find()->all();
            $selectedSession = isset($_GET['session_id']) ? \app\models\Session::findOne($_GET['session_id']) : $activeSession;
            ?>

            <!-- Dropdown to select other sessions -->
            <div class="col-12 mb-3">
                <p>Rechercher une Session</p>
                <form method="get" action="<?= Yii::getAlias('@administrator.borrowings') ?>">
                    <div class="input-group">
                        <select name="session_id" class="form-control">
                            <?php foreach ($allSessions as $session) : ?>
                                <option value="<?= Html::encode($session->id) ?>" <?= $selectedSession && $session->id == $selectedSession->id ? 'selected' : '' ?>>
                                    Session <?= Html::encode(ucfirst((new IntlDateFormatter('fr_FR', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'MMMM'))->format(new DateTime($session->date)))) ?> <?= $session->active ? '<span class="text-success">(active)</span>' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Recherche</button>
                        </div>
                    </div>
                </form>
            </div>

            <?php if ($selectedSession): ?>
                <?php $borrowingAmount = \app\models\Borrowing::find()->where(['session_id' => $selectedSession->id])->sum('amount'); ?>
                <div class="col-12 white-block text-center mb-5">
                    <h3>Session <?= $selectedSession->active ? '<span class="text-success">(active)</span>' : '<span class="text-danger">(inactive)</span>' ?></h3>
                    <h1 class="blue-text"><?= $borrowingAmount ? $borrowingAmount : 0 ?> XAF</h1>
                    <h3>empruntés</h3>
                    
                    <?php if (\app\managers\FinanceManager::numberOfSession() == 12): ?>
                        <p class="mt-4 text-secondary">
                            Aucun nouvel emprunt ne peut être fait car nous sommes à la dernière session de l'exercice.
                        </p>
                    <?php endif; ?>
                </div>

                <?php if (\app\managers\FinanceManager::numberOfSession() < 12): ?>
                    <button class="btn <?= $model->hasErrors() ? 'btn-danger' : 'btn-secondary' ?>" id="btn-add" data-toggle="modal" data-target="#modalLRFormDemo">Ajouter Emprunt</button>
                    <div class="modal fade" id="modalLRFormDemo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <?php $members = \app\models\Member::find()->where(['active' => true])->all() ?>

                                <?php if (count($members)): ?>
                                    <?php
                                    $items = [];
                                    foreach ($members as $member) {
                                        $user = \app\models\User::findOne($member->user_id);
                                        $items[$member->id] = $user->name . " " . $user->first_name;
                                    }
                                    ?>

                                    <?php $form = \yii\widgets\ActiveForm::begin([
                                        'errorCssClass' => 'text-secondary',
                                        'method' => 'post',
                                        'action' => '@administrator.new_borrowing',
                                        'options' => ['class' => 'modal-body']
                                    ]) ?>
                                    <?= $form->field($model, 'member_id')->dropDownList($items)->label("Membre") ?>
                                    <?= $form->field($model, "amount")->label("Montant")->input("number", ['required' => 'required']) ?>
                                    <?= $form->field($model, 'session_id')->hiddenInput(['value' => $activeSession->id])->label(false) ?>
                                    <div class="form-group text-right">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">Ajouter</button>
                                    </div>
                                    <?php \yii\widgets\ActiveForm::end(); ?>
                                <?php else: ?>
                                    <div class="modal-body">
                                        <h3 class="text-muted text-center">Aucun membre inscrit</h3>
                                        <div class="text-center my-2">
                                            <a href="<?= Yii::getAlias("@administrator.new_member") ?>" class="btn btn-primary">Inscrire un membre</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-12 white-block mb-2">
                    <h5 class="mb-4">Session du <span class="text-secondary"><?= (new DateTime($selectedSession->date))->format("d-m-Y") ?> <?= $selectedSession->active ? '<span class="text-success">(active)</span>' : '' ?></span> : <span class="blue-text"><?= $borrowingAmount ? $borrowingAmount : 0 ?> XAF</span></h5>

                    <?php if (count($members)): ?>
                        <table class="table table-hover">
                            <thead class="blue-grey lighten-4">
                                <tr>
                                    <th>#</th>
                                    <th>Membre</th>
                                    <th>Total Empruntés</th>
                                    <th>Total Remboursés</th>
                                    <th>Total Aérés</th>
                                    <th>Net à payer</th>
                                    <?php if($selectedSession->active) : ?>
                                    <th>Ajouter Emprunt</th>
                                    <?php endif; ?>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($members as $index => $member): ?>
                                    <?php
                                    $user = \app\models\User::findOne($member->user_id);
                                    $latestBorrowing = \app\models\Borrowing::find()->where(['member_id' => $member->id, 'session_id' => $selectedSession->id])->one();
                                    $administrator = $latestBorrowing ? \app\models\Administrator::findOne($latestBorrowing->administrator_id) : null;
                                    $administratorUser = $administrator ? \app\models\User::findOne($administrator->id) : null;
                                    $borrowingAmountUser = \app\models\Borrowing::find()->where(['member_id' => $member->id])->sum('amount');
                                    $TotalrefundedAmountUser = \app\models\Refund::find()->where(['member_id' => $member->id])->sum('amount');
                                    $savingAmountUser = \app\models\Saving::find()->where(['member_id' => $member->id, 'session_id' => $selectedSession->id])->sum('amount');

                                    $totalRemainingAmount = 0;
                                    $borrowings = \app\models\Borrowing::find()->where(['member_id' => $member->id])->all();

                                    foreach ($borrowings as $borrowing) {
                                        $refundedAmountUser = \app\models\Refund::find()->where(['member_id' => $member->id, 'borrowing_id' => $borrowing->id])->sum('amount');
                                        $Empruntpaye = $borrowing->amount + ($borrowing->amount * ($borrowing->interest / 100));
                                        $remainingAmount = $Empruntpaye - $refundedAmountUser;
                                        $totalRemainingAmount += $remainingAmount;
                                    }
                                    ?>
                                    <tr>
                                        <th><?= $index + 1 ?></th>
                                        <td><?= Html::encode($user->name . " " . $user->first_name) ?></td>
                                        <td class="blue-text"><?= $borrowingAmountUser ?> XAF</td>
                                        <td class="blue-text"><?= $TotalrefundedAmountUser ? $TotalrefundedAmountUser : 0 ?> XAF</td>
                                        <td class="blue-text"><span style="color: <?= $totalRemainingAmount == 0 ? 'green' : 'red' ?>;"><?= $totalRemainingAmount ?> XAF</span></td>
                                        <td><?= $latestBorrowing ? $latestBorrowing->intendedAmount() . ' XAF' : 'N/A' ?></td>
                                        <?php if ($selectedSession->active): ?>
                                            <?php if ($savingAmountUser == 0): ?>
                                                <td class="red-text">Pour emprunter, veuillez epargner</td>
                                            <?php else: ?>
                                                <td>
                                                    <?php $form = ActiveForm::begin([
                                                        'errorCssClass' => 'text-secondary',
                                                        'method' => 'post',
                                                        'action' => ['administrator/nouvelle-emprunt'],
                                                        'options' => ['class' => 'form-inline']
                                                    ]) ?>
                                                    <?= $form->field($model, 'member_id')->hiddenInput(['value' => $member->id])->label(false) ?>
                                                    <?= $form->field($model, 'amount')->label(false)->input("number", ['required' => 'required', 'placeholder' => 'Montant', 'class' => 'form-control mr-2']) ?>
                                                    <?= $form->field($model, 'session_id')->hiddenInput(['value' => $selectedSession->id])->label(false) ?>
                                                    <div class="form-group text-right">
                                                        <?= Html::submitButton('Emprunter', ['class' => 'btn btn-success btn-sm']) ?>
                                                    </div>
                                                    <?php ActiveForm::end(); ?>
                                                </td>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <td>
                                            <a href="<?= Yii::getAlias("@administrator.borrowings_details") . "?member_id=" . $member->id . "&session_id=" . $selectedSession->id ?>" class="btn btn-primary btn-sm">Details</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted">Aucun membre trouvé pour cette session.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-muted">Aucune session trouvée.</p>
        <?php endif; ?>
    </div>
</div>

<?php $this->beginBlock('script') ?>
<script>
    $(document).ready(function() {
        $('#modalLRFormDemo').on('shown.bs.modal', function () {
            $('#modalLRFormDemo input:first').focus();
        });
    });
</script>
<?php $this->endBlock() ?>
