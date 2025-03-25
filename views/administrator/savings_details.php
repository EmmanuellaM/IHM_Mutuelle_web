<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->beginBlock('title') ?>
Détails des Épargnes
<?php $this->endBlock() ?>

<?php $this->beginBlock('style') ?>
<style>
    .page-header {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
        text-align: center;
    }

    .member-card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
    }

    .member-name {
        font-size: 1.5rem;
        color: #2193b0;
        margin-bottom: 0.5rem;
    }

    .total-savings {
        font-size: 2rem;
        font-weight: 600;
        color: #34a853;
        margin: 1rem 0;
    }

    .savings-table {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .savings-table .table {
        margin-bottom: 0;
    }

    .savings-table thead th {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        color: #495057;
        font-weight: 600;
        border: none;
        padding: 1rem;
    }

    .savings-table tbody tr {
        transition: all 0.3s ease;
    }

    .savings-table tbody tr:hover {
        background-color: rgba(33, 147, 176, 0.05);
    }

    .savings-table td {
        padding: 1rem;
        vertical-align: middle;
    }

    .amount-cell {
        font-weight: 600;
        color: #2193b0;
    }

    .date-cell {
        color: #6c757d;
    }

    .admin-cell {
        color: #495057;
    }

    .action-cell {
        white-space: nowrap;
    }

    .btn-edit {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        transition: all 0.3s ease;
        margin-right: 0.5rem;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(33, 147, 176, 0.2);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, #eb3349, #f45c43);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        transition: all 0.3s ease;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(235, 51, 73, 0.2);
        color: white;
    }

    .modal-content {
        border-radius: 0.5rem;
        border: none;
    }

    .modal-header {
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        border-radius: 0.5rem 0.5rem 0 0;
        border: none;
    }

    .modal-title {
        color: white;
    }

    .modal-body {
        padding: 2rem;
    }

    .form-control {
        border: 1px solid #e0e0e0;
        border-radius: 0.25rem;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #2193b0;
        box-shadow: 0 0 0 0.2rem rgba(33, 147, 176, 0.25);
    }

    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>
<?php $this->endBlock() ?>

<div class="container mt-5 mb-5">
    <div class="row animate-fade-in">
        <!-- En-tête de la page -->
        <div class="col-12">
            <div class="page-header">
                <h2>Détails des Épargnes</h2>
            </div>
        </div>

        <!-- Carte du membre -->
        <div class="col-12">
            <div class="member-card">
                <div class="member-name"><?= Html::encode($memberUser->name . " " . $memberUser->first_name) ?></div>
                <div class="total-savings"><?= number_format($totalSavings, 0, ',', ' ') ?> XAF</div>
                <div class="text-muted">Total des épargnes pour cette session</div>
            </div>
        </div>

        <!-- Table des épargnes -->
        <div class="col-12">
            <div class="savings-table">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Montant</th>
                                <th>Date d'ajout</th>
                                <th>Administrateur</th>
                                <?php if($session->active) : ?>
                                <th>Actions</th>
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
                                    <td><?= $index + 1 ?></td>
                                    <td class="amount-cell"><?= number_format($saving->amount, 0, ',', ' ') ?> XAF</td>
                                    <td class="date-cell"><?= (new DateTime($saving->created_at))->format('d-m-Y H:i') ?></td>
                                    <td class="admin-cell text-capitalize"><?= Html::encode($administratorUser->name . " " . $administratorUser->first_name) ?></td>
                                    <?php if($session->active) : ?>
                                    <td class="action-cell">
                                        <button class="btn btn-edit btn-sm" data-toggle="modal" data-target="#editModal<?= $saving->id ?>">
                                            <i class="fas fa-edit"></i> Modifier
                                        </button>
                                        <button class="btn btn-delete btn-sm" data-toggle="modal" data-target="#modalS<?= $saving->id ?>">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </td>
                                    <?php endif; ?>
                                </tr>

                                <!-- Modal de modification -->
                                <div class="modal fade" id="editModal<?= $saving->id ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?= $saving->id ?>" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?= $saving->id ?>">
                                                    Modifier l'épargne
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true" style="color: white;">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <?php $form = ActiveForm::begin([
                                                    'action' => Url::to(['administrator/modifier-epargne', 'id' => $saving->id]),
                                                    'id' => 'edit-saving-form-' . $saving->id,
                                                    'options' => ['data-pjax' => true]
                                                ]); ?>

                                                <?= $form->field($saving, 'member_id')->textInput([
                                                    'value' => $memberUser->name . " " . $memberUser->first_name,
                                                    'readonly' => true,
                                                    'class' => 'form-control mb-3'
                                                ])->label('Membre') ?>

                                                <?= $form->field($saving, 'amount')->textInput([
                                                    'class' => 'form-control mb-3',
                                                    'type' => 'number'
                                                ])->label('Montant') ?>

                                                <?= Html::hiddenInput('Saving[id]', $saving->id) ?>

                                                <div class="text-right mt-4">
                                                    <button type="button" class="btn btn-delete" data-dismiss="modal">Annuler</button>
                                                    <?= Html::submitButton('Enregistrer', ['class' => 'btn btn-edit ml-2']) ?>
                                                </div>

                                                <?php ActiveForm::end(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal de suppression -->
                                <div class="modal fade" id="modalS<?= $saving->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmation de suppression</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true" style="color: white;">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <p class="mb-4">Êtes-vous sûr(e) de vouloir supprimer cette épargne ?</p>
                                                <div>
                                                    <button data-dismiss="modal" class="btn btn-edit">Annuler</button>
                                                    <a href="<?= Yii::getAlias("@administrator.delete_saving") . "?q=" . $saving->id ?>" class="btn btn-delete ml-2">
                                                        Confirmer
                                                    </a>
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
    </div>
</div>
