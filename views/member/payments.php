<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mes Paiements';
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Mes Paiements</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><?= Html::a('Accueil', ['member/accueil']) ?></li>
                        <li class="breadcrumb-item active">Mes Paiements</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Historique des paiements</h3>
                        </div>
                        <div class="card-body">
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'tableOptions' => ['class' => 'table table-striped table-bordered'],
                                'summary' => '<div class="text-right mb-3">Total : {totalCount} paiement(s)</div>',
                                'emptyText' => 'Aucun paiement trouvé',
                                'columns' => [
                                    [
                                        'attribute' => 'created_at',
                                        'label' => 'Date',
                                        'value' => function($model) {
                                            return $model->getFormattedDate('created_at');
                                        }
                                    ],
                                    [
                                        'attribute' => 'payment_id',
                                        'label' => 'ID Paiement'
                                    ],
                                    [
                                        'attribute' => 'amount',
                                        'label' => 'Montant',
                                        'value' => function($model) {
                                            return number_format($model->amount, 0, ',', ' ') . ' FCFA';
                                        }
                                    ],
                                    [
                                        'attribute' => 'payment_method',
                                        'label' => 'Mode de paiement'
                                    ],
                                    [
                                        'attribute' => 'transaction_id',
                                        'label' => 'ID Transaction'
                                    ],
                                    [
                                        'attribute' => 'phone_number',
                                        'label' => 'Numéro de téléphone',
                                        'value' => function($model) {
                                            return $model->phone_number ?: '-';
                                        }
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'label' => 'Statut',
                                        'format' => 'raw',
                                        'contentOptions' => function ($model) {
                                            return ['class' => 'text-center'];
                                        },
                                        'value' => function($model) {
                                            $class = $model->status === 'completed' ? 'success' : 'warning';
                                            return '<span class="badge badge-' . $class . '">' . 
                                                   ucfirst($model->status) . '</span>';
                                        }
                                    ],
                                ],
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.grid-view th {
    white-space: nowrap;
    background-color: #f4f6f9;
}
.grid-view td {
    vertical-align: middle;
}
.badge {
    font-size: 0.9em;
    padding: 0.4em 0.8em;
}
</style>
