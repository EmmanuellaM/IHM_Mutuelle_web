<?php use yii\widgets\LinkPager;

$this->beginBlock('title') ?>
Mes épargnes
<?php $this->endBlock() ?>

<?php $this->beginBlock('style')?>
<style>
    .savings-container {
        padding: 2rem;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        min-height: 100vh;
    }

    .exercise-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        padding: 1.5rem;
    }

    .exercise-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .exercise-year {
        font-size: 1.5rem;
        color: #2c3e50;
        font-weight: 600;
    }

    .exercise-status {
        padding: 0.5rem 1rem;
        border-radius: 15px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .exercise-status.active {
        background: #4e73df;
        color: white;
    }

    .exercise-status.inactive {
        background: #e74a3b;
        color: white;
    }

    .savings-table {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-top: 1.5rem;
    }

    .savings-table th {
        background: #f8f9fc;
        color: #495057;
        font-weight: 600;
        padding: 1rem;
    }

    .savings-table td {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    .savings-table .amount-value {
        color: #2c3e50;
        font-weight: 600;
    }

    .savings-table .admin-name {
        color: #4e73df;
        font-weight: 500;
    }

    .savings-table .session-date {
        color: #495057;
    }

    .no-savings {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-top: 1.5rem;
    }

    .pagination {
        justify-content: center;
        margin-top: 2rem;
    }

    .page-item {
        margin: 0 0.25rem;
    }

    .page-link {
        color: #4e73df;
        border: 1px solid #4e73df;
        border-radius: 25px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background-color: #4e73df;
        color: white;
    }

    .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
        color: white;
    }

    @media (max-width: 768px) {
        .exercise-card {
            padding: 1rem;
        }

        .exercise-year {
            font-size: 1.2rem;
        }

        .exercise-status {
            padding: 0.3rem 0.8rem;
            font-size: 0.8rem;
        }

        .savings-table th,
        .savings-table td {
            padding: 0.75rem;
        }
    }
</style>
<?php $this->endBlock()?>

<div class="savings-container">
    <div class="container">
        <div class="row">
            <?php if (count($exercises)):?>
                <?php foreach ($exercises as $exercise): ?>
                    <div class="col-12">
                        <div class="exercise-card">
                            <?php
                            $savings = $member->exerciseSavings($exercise);
                            ?>
                            <div class="exercise-header">
                                <h2 class="exercise-year"><?= $exercise->year ?></h2>
                                <span class="exercise-status <?= $exercise->active ? 'active' : 'inactive' ?>">
                                    <?= $exercise->active ? 'En cours' : 'Terminé' ?>
                                </span>
                            </div>

                            <?php if (count($savings)):?>
                                <div class="savings-table">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Montant</th>
                                                <th>Administrateur</th>
                                                <th>Session</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($savings as $index => $saving): ?>
                                                <?php
                                                $amount = $saving->amount;
                                                $administrator = $saving->administrator()->user();
                                                $session = $saving->session();
                                                ?>
                                                <tr>
                                                    <th scope="row"><?= $index + 1 ?></th>
                                                    <td class="amount-value"><?= $amount ? $amount : 0 ?> XAF</td>
                                                    <td class="admin-name"><?= $administrator->name . ' ' . $administrator->first_name ?></td>
                                                    <td class="session-date"><?= $session->date() ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else:?>
                                <div class="no-savings">
                                    <h3 class="text-muted">Aucune épargne pour cet exercice</h3>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="col-12">
                    <nav aria-label="Navigation des pages">
                        <?= LinkPager::widget([
                            'pagination' => $pagination,
                            'options' => [
                                'class' => 'pagination'
                            ],
                            'pageCssClass' => 'page-item',
                            'linkOptions' => ['class' => 'page-link']
                        ]) ?>
                    </nav>
                </div>

            <?php else:?>
                <div class="col-12 text-center">
                    <h3 class="text-muted">Aucun exercice enregistré.</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>