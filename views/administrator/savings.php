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
            <?php $activeSession = \app\models\Session::findOne(['active' => true]); ?>
            <?php
            $allSessions = \app\models\Session::find()->all();
            $selectedSession = isset($_GET['session_id']) ? \app\models\Session::findOne($_GET['session_id']) : $activeSession;
            ?>

            <!-- Dropdown to select other sessions -->
            <div class="col-12 mb-3">
                <p>Rechercher une Session</p>
                <form method="get" action="<?= Yii::getAlias('@administrator.savings') ?>">
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
                <?php $savingAmount = \app\models\Saving::find()->where(['session_id' => $selectedSession->id])->sum('amount'); ?>
                <div class="col-12 white-block text-center mb-5">
                    <h3>Session <?= $selectedSession->active ? '<span class="text-success">(active)</span>' : '<span class="text-danger">(inactive)</span>' ?></h3>
                    <h1 class="blue-text"><?= $savingAmount ? $savingAmount : 0 ?> XAF</h1>
                    <h3>épargnés</h3>
                </div>

                <!-- <button class="btn <?= $model->hasErrors()?'in':''?> btn-secondary" id="btn-add" data-toggle="modal" data-target="#modalLRFormDemo">Ajouter Epargne</button> -->
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

                                <!-- <?php $form = ActiveForm::begin([
                                    'errorCssClass' => 'text-secondary',
                                    'method' => 'post',
                                    'action' => ['administrator/nouvelle-epargne'],
                                    'options' => ['class' => 'modal-body']
                                ]) ?>
                                <?= $form->field($model, 'member_id')->dropDownList($items)->label("Membre") ?>
                                <?= $form->field($model, "amount")->label("Montant")->input("number", ['required' => 'required']) ?>
                                <?= $form->field($model, 'session_id')->hiddenInput(['value' => $selectedSession->id])->label(false) ?>
                                <div class="form-group text-right">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-primary">Ajouter</button>
                                </div>
                                <?php ActiveForm::end(); ?> -->

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

                <div class="col-12 white-block mb-2">
                    <?php $savingAmount = \app\models\Saving::find()->where(['session_id' => $selectedSession->id])->sum('amount'); ?>
                    <h5 class="mb-4">Session du <span class="text-secondary"><?= (new DateTime($selectedSession->date))->format("d-m-Y") ?> <?= $selectedSession->active ? '<span class="text-success">(active)</span>' : '' ?></span> : <span class="blue-text"><?= $savingAmount ? $savingAmount : 0 ?> XAF</span></h5>

                    <?php if (count($members)): ?>
                        <table class="table table-hover">
                            <thead class="blue-grey lighten-4">
                                <tr>
                                    <th>#</th>
                                    <th>Membre</th>
                                    <th>Montant</th>
                                    <th>Administrateur</th>
                                    <?php if($selectedSession->active) : ?>
                                    <th>Ajouter Epargne</th>
                                    <?php endif; ?>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($members as $index => $member): ?>
                                    <?php
                                    $user = \app\models\User::findOne($member->user_id);
                                    $latestSaving = \app\models\Saving::find()
                                        ->where(['member_id' => $member->id, 'session_id' => $selectedSession->id])
                                        ->orderBy(['created_at' => SORT_DESC])
                                        ->one();
                                    $administrator = $latestSaving ? \app\models\Administrator::findOne($latestSaving->administrator_id) : null;
                                    $administratorUser = $administrator ? \app\models\User::findOne($administrator->id) : null;
                                    $savingAmountUser = \app\models\Saving::find()->where(['member_id' => $member->id])->sum('amount');
                                    ?>
                                    <tr>
                                        <th><?= $index + 1 ?></th>
                                        <td><?= Html::encode($user->name . " " . $user->first_name) ?></td>
                                        <td class="blue-text"><?= $savingAmountUser ?> XAF</td>
                                        <td class="text-capitalize"><?= $administratorUser ? $administratorUser->name . " " . $administratorUser->first_name : 'N/A' ?></td>

                                        <?php if($selectedSession->active) : ?>
                                        <td>
                                            <?php $form = ActiveForm::begin([
                                                'errorCssClass' => 'text-secondary',
                                                'method' => 'post',
                                                'action' => ['administrator/nouvelle-epargne'],
                                                'options' => ['class' => 'form-inline']
                                            ]) ?>
                                            <?= $form->field($model, 'member_id')->hiddenInput(['value' => $member->id])->label(false) ?>
                                            <?= $form->field($model, 'amount')->label(false)->input("number", ['required' => 'required', 'placeholder' => 'Montant', 'class' => 'form-control mr-2']) ?>
                                            <?= $form->field($model, 'session_id')->hiddenInput(['value' => $selectedSession->id])->label(false) ?>
                                            <div class="form-group text-right">
                                                <?= Html::submitButton('Enregistrer', ['class' => 'btn btn-success btn-sm']) ?>
                                            </div>
                                            <?php ActiveForm::end(); ?>
                                        </td>
                                        <?php endif; ?>

                                        <td>
                                            <a href="<?= Yii::getAlias("@administrator.savings_details") . "?member_id=" . $member->id . "&session_id=" . $selectedSession->id ?>" class="btn btn-primary">Details</a>
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
