<?php

use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->beginBlock('title') ?>
Remboursements
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
                <form method="get" action="<?= Yii::getAlias('@administrator.refunds') ?>">
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
                <?php $refundAmount = \app\models\Refund::find()->where(['session_id' => $selectedSession->id])->sum('amount'); ?>
                <div class="col-12 white-block text-center mb-5">
                    <h3>Session <?= $selectedSession->active ? '<span class="text-success">(active)</span>' : '<span class="text-danger">(inactive)</span>' ?></h3>
                    <h1 class="blue-text"><?= $refundAmount ? $refundAmount : 0 ?> XAF</h1>
                    <h3>remboursés</h3>
                </div>

                <button class="btn <?= $model->hasErrors() ? 'btn-danger' : 'btn-secondary' ?>" id="btn-add" data-toggle="modal" data-target="#modalLRFormDemo">Ajouter Remboursement</button>
                <div class="modal fade" id="modalLRFormDemo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <?php
                            $members = \app\models\Member::find()->where(['active' => true])->all();
                            $items = [];
                            foreach ($members as $member) {
                                $user = \app\models\User::findOne($member->user_id);
                                $items[$member->id] = $user->name . " " . $user->first_name;
                            }
                            ?>

                            <?php $form = \yii\widgets\ActiveForm::begin([
                                'errorCssClass' => 'text-secondary',
                                'method' => 'post',
                                'action' => '@administrator.new_refund',
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
                        </div>
                    </div>
                </div>

                <div class="col-12 white-block mb-2">
                    <h5 class="mb-4">Session du <span class="text-secondary"><?= (new DateTime($selectedSession->date))->format("d-m-Y") ?> <?= $selectedSession->active ? '<span class="text-success">(active)</span>' : '' ?></span> : <span class="blue-text"><?= $refundAmount ? $refundAmount : 0 ?> XAF</span></h5>

                    <?php
                    $refunds = \app\models\Refund::findAll(['session_id' => $selectedSession->id]);
                    ?>

                    <?php if (count($refunds)): ?>
                        <table class="table table-hover">
                            <thead class="blue-grey lighten-4">
                                <tr>
                                    <th>#</th>
                                    <th>Membre</th>
                                    <th>Montant</th>
                                    <th>Reste à payer</th>
                                    <th>Administrateur</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($refunds as $index => $refund): ?>
                                    <?php
                                    $borrowing = \app\models\Borrowing::findOne($refund->borrowing_id);
                                    $member = \app\models\Member::findOne($borrowing->member_id);
                                    $memberUser = \app\models\User::findOne($member->user_id);
                                    $administrator = \app\models\Administrator::findOne($refund->administrator_id);
                                    $administratorUser = \app\models\User::findOne($administrator->id);
                                    $remainingAmount = max($borrowing->intendedAmount() - $borrowing->refundedAmount(), 0);
                                    ?>
                                    <tr <?= $selectedSession->active ? 'data-target="#modalS' . $refund->id . '" data-toggle="modal"' : '' ?>>
                                        <th scope="row"><?= $index + 1 ?></th>
                                        <td class="text-capitalize"><?= Html::encode($memberUser->name . " " . $memberUser->first_name) ?></td>
                                        <td class="blue-text"><?= $refund->amount ?> XAF</td>
                                        <th><?= $remainingAmount ?> XAF</th>
                                        <td class="text-capitalize"><?= Html::encode($administratorUser->name . " " . $administratorUser->first_name) ?></td>
                                        <td>
                                            <?php if ($selectedSession->active): ?>
                                                <a href="" class="btn btn-primary btn-sm">Modifier</a>
                                                <a data-target="#modalS<?= $refund->id ?>" data-toggle="modal" href="" class="btn btn-danger btn-sm">Supprimer</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <?php if ($selectedSession->active): ?>
                                        <div class="modal fade" id="modalS<?= $refund->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <p class="p-1 text-center">
                                                            Êtes-vous sûr(e) de vouloir supprimer ce remboursement ?
                                                        </p>
                                                        <div class="text-center">
                                                            <button data-dismiss="modal" class="btn btn-danger">Non</button>
                                                            <a href="<?= Yii::getAlias("@administrator.delete_refund") . "?q=" . $refund->id ?>" class="btn btn-primary">Oui</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <h3 class="text-center text-muted">Aucun remboursement à cette session</h3>
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
