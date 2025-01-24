<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Savings Details';
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12 white-block">
            <h3><?= Html::encode($memberUser->name . " " . $memberUser->first_name) ?></h3>
            <h4>Total Savings in Session: <?= Html::encode($totalSavings) ?> XAF</h4>
            <table class="table table-hover">
                <thead class="blue-grey lighten-4">
                    <tr>
                        <th>#</th>
                        <th>Montant</th>
                        <th>Date D'ajout</th>
                        <th>Administrateur</th>
                        <?php if($session->active) : ?>
                        <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($savings as $index => $saving) : ?>
                        <?php
                        $administrator = \app\models\Administrator::findOne($saving->administrator_id);
                        $administratorUser = \app\models\User::findOne($administrator->id);
                        ?>
                        <tr>
                            <th scope="row"><?= $index + 1 ?></th>
                            <td><?= Html::encode($saving->amount) ?> XAF</td>
                            <td><?= Html::encode($saving->created_at) ?></td>
                            <td><?= Html::encode($administratorUser->name . " " . $administratorUser->first_name) ?></td>
                            <?php if($session->active) : ?>
                            <td>
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal<?= $saving->id ?>">Modifier</button>
                                <button <?= ($session->active) ? 'data-target="#modalS' . $saving->id . '" data-toggle="modal"' : '' ?> class="btn btn-danger btn-sm">Supprimer</button>
                            </td>
                            <?php endif; ?>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?= $saving->id ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?= $saving->id ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel<?= $saving->id ?>">Modifier Epargne</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php $form = ActiveForm::begin([
                                            'action' => Url::to(['administrator/modifier-epargne', 'id' => $saving->id]),
                                            'id' => 'edit-saving-form-' . $saving->id,
                                            'options' => ['data-pjax' => true]
                                        ]); ?>

                                        <?= $form->field($saving, 'member_id')->textInput(['value' => $memberUser->name . " " . $memberUser->first_name, 'readonly' => true]) ?>
                                        <?= $form->field($saving, 'amount')->textInput() ?>
                                        <?= Html::hiddenInput('Saving[id]', $saving->id) ?>

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
                        <div class="modal fade" id="modalS<?= $saving->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <p class="p-1 text-center">
                                            Êtes-vous sûr(e) de vouloir supprimer cette épargne?
                                        </p>
                                        <div class="text-center">
                                            <button data-dismiss="modal" class="btn btn-danger">Non</button>
                                            <a href="<?= Yii::getAlias("@administrator.delete_saving") . "?q=" . $saving->id ?>" class="btn btn-primary">Oui</a>
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
