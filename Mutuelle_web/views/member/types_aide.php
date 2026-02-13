<?php $this->beginBlock('title') ?>
Types d'aide
<?php $this->endBlock()?>
<?php $this->beginBlock('style')?>
<style>
    .page-header {
        margin-bottom: 2rem;
        padding: 1.5rem 0;
        background: linear-gradient(135deg, #2193b0, #6dd5ed);
        color: white;
        border-radius: 0.5rem;
        text-align: center;
    }
    
    .white-block {
        padding: 2rem;
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .table {
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        color: #495057;
        font-weight: 600;
        padding: 1rem;
        text-transform: uppercase;
        font-size: 0.875rem;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
    }

    .table td, .table th {
        padding: 1rem;
        vertical-align: middle;
        border-top: 1px solid #e9ecef;
    }

    .amount {
        font-weight: 600;
        color: #2193b0;
    }

    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }

    .empty-state h1 {
        color: #6c757d;
        font-size: 1.5rem;
        font-weight: 500;
    }
</style>
<?php $this->endBlock()?>

<div class="container mt-5 mb-5">
    <div class="page-header">
        <h2>Types d'aide disponibles</h2>
    </div>

    <?php if (count($helpTypes)):?>
        <div class="white-block">
            <table class="table">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th width="60%">Titre</th>
                        <th width="30%">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($helpTypes as $index => $ht): ?>
                        <tr>
                            <th scope="row"><?= $index + 1 ?></th>
                            <td><?= $ht->title ?></td>
                            <td class="amount"><?= number_format($ht->amount, 0, ',', ' ') ?> XAF</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="white-block empty-state">
            <h1>Aucun type d'aide enregistré</h1>
        </div>
    <?php endif;?>
</div>