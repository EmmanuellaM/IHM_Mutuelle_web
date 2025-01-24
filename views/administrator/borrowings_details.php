<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Borrowings Details';
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12 white-block">
            <h3><?= Html::encode($memberUser->name . " " . $memberUser->first_name) ?></h3>
            <h4>Emprunts Total de la Session: <?= Html::encode($totalBorrowings ? $totalBorrowings : 0) ?> XAF</h4>
            <table class="table table-hover">
                <thead class="blue-grey lighten-4">
                    <tr>
                        <th>#</th>
                        <th>Montant</th>
                        <th>Intérêt</th>
                        <th>Net à payer</th>
                        <th>Reste</th>
                        <th>Administrateur</th>
                        <th>Date D'échéance</th>
                        <?php if($session->active) : ?>
                        <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($borrowings as $index => $borrowing) : ?>
                        <?php
                        $administrator = \app\models\Administrator::findOne($borrowing->administrator_id);
                        $administratorUser = \app\models\User::findOne($administrator->id);
                        $refundedAmountUser = \app\models\Refund::find()->where(['member_id' => $member->id, 'session_id' => $session->id, 'borrowing_id' => $borrowing->id])->sum('amount');
                        $Empruntpaye = $borrowing->amount + ($borrowing->amount * ($borrowing->interest / 100));
                        $Empruntpaye = $Empruntpaye - $refundedAmountUser;
                        // $Empruntpaye = $Empruntpaye - $borrowing->amount;
                        $totalBorrowings += $Empruntpaye;
                        $totalBorrowings = $totalBorrowings - $refundedAmountUser;
                        ?>
                        <tr>
                            <th scope="row"><?= $index + 1 ?></th>
                            <td><?= Html::encode($borrowing->amount) ?> XAF</td>
                            <td><?= $borrowing->interest ?> %</td>
                            <td><?= $borrowing->intendedAmount() ?> XAF</td>
                            <td><?= $Empruntpaye ?> XAF</td>
                            <td><?= Html::encode($administratorUser->name . " " . $administratorUser->first_name) ?></td>
                            <td><?= Html::encode($session->date_d_écheance_emprunt()) ?></td>
                            <?php if($session->active) : ?>
                                <?php if($Empruntpaye == 0) : ?>
                                    <td>
                                        <span style="color: green; font-size:larger" class="badge badge-success">Remboursé</span>
                                    </td>
                                <?php else: ?>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal<?= $borrowing->id ?>">Modifier</button>
                                            <button <?= ($session->active) ? 'data-target="#modalS' . $borrowing->id . '" data-toggle="modal"' : '' ?> class="btn btn-danger btn-sm">Supprimer</button>
                                        </td>
                                <?php endif; ?>
                            <?php endif; ?>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?= $borrowing->id ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?= $borrowing->id ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel<?= $borrowing->id ?>">Modifier Emprunt</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php $form = ActiveForm::begin([
                                            'action' => Url::to(['administrator/modifier-emprunt', 'id' => $borrowing->id]),
                                            'id' => 'edit-saving-form-' . $borrowing->id,
                                            'options' => ['data-pjax' => true]
                                        ]); ?>

                                        <?= $form->field($borrowing, 'member_id')->textInput(['value' => $memberUser->name . " " . $memberUser->first_name, 'readonly' => true]) ?>
                                        <?= $form->field($borrowing, 'amount')->textInput() ?>
                                        <?= Html::hiddenInput('Borrowing[id]', $borrowing->id) ?>

                                        <div class="form-group">
                                            <?= Html::submitButton('Enregistrer', ['class' => 'btn btn-success']) ?>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                                        </div>

                                        <?php ActiveForm::end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="modalS<?= $borrowing->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <p class="p-1 text-center">
                                            Êtes-vous sûr(e) de vouloir supprimer cette épargne?
                                        </p>
                                        <div class="text-center">
                                            <button data-dismiss="modal" class="btn btn-danger">Non</button>
                                            <a href="<?= Yii::getAlias("@administrator.delete_borrowing") . "?q=" . $borrowing->id ?>" class="btn btn-primary">Oui</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
