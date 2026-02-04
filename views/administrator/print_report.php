<?php
use yii\helpers\Html;

// Ajouter le style spécifique pour l'impression
$this->beginBlock('print-styles')
?>
<style>
    @media print {
        body {
            background: white;
            -webkit-print-color-adjust: exact;
        }
        
        .no-print {
            display: none;
        }
        
        .print-header {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1rem;
        }
        
        .print-header h1 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .print-header h2 {
            font-size: 1.2rem;
            color: #666;
            margin-top: 0;
        }
        
        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        
        .print-table th,
        .print-table td {
            border: 1px solid #ddd;
            padding: 0.5rem;
            text-align: left;
        }
        
        .print-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .print-summary {
            margin: 1rem 0;
            padding: 1rem;
            background-color: #f8f9fa;
        }
        
        .print-summary h3 {
            margin-top: 0;
            color: #333;
        }
        
        .print-summary p {
            margin: 0.5rem 0;
        }
        
        .page-break {
            page-break-after: always;
        }
    }
</style>
<?php
$this->endBlock()
?>

<div class="print-header">
    <h1><?= Html::encode(Yii::$app->name) ?></h1>
    <h2><?= Html::encode($title) ?></h2>
</div>

<?php if ($type === 'exercise'): ?>
    <div class="print-summary">
        <h3>Informations sur l'exercice</h3>
        <p><strong>Année :</strong> <?= $data['exercise']->year ?></p>
        <p><strong>Taux d'intérêt :</strong> <?= $data['exercise']->interest ?>%</p>
        <p><strong>Montant inscription :</strong> <?= number_format($data['exercise']->inscription_amount, 0, ',', ' ') ?> XAF</p>
        <p><strong>Montant fond social :</strong> <?= number_format($data['exercise']->social_crown_amount, 0, ',', ' ') ?> XAF</p>
    </div>

    <div class="print-summary">
        <h3>Liste des sessions</h3>
        <table class="print-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['sessions'] as $session): ?>
                    <tr>
                        <td><?= Yii::$app->formatter->asDate($session->date, 'php:F Y') ?></td>
                        <td><?= $session->active ? 'Active' : 'Clôturée' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <div class="print-summary">
        <h3>Liste des membres</h3>
        <table class="print-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>État Inscription</th>
                    <th>État Fond Social</th>
                    <th>Montant Epargne</th>
                    <th>Montant Dette</th>
                    <th>Montant Inscription</th>
                    <th>Montant Fond Social</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['members'] as $member): ?>
                    <tr>
                        <td><?= Html::encode($member->username) ?></td>
                        <td><?= $member->inscription >= $data['exercise']->inscription_amount ? 'Payé' : 'En retard' ?></td>
                        <td><?= $member->social_crown >= $data['exercise']->social_crown_amount ? 'Payé' : 'En retard' ?></td>
                        <td><?= number_format($member->inscription, 0, ',', ' ') ?> XAF</td>
                        <td><?= number_format($member->social_crown, 0, ',', ' ') ?> XAF</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="print-summary">
        <h3>Informations sur la session</h3>
        <p><strong>Date :</strong> <?= Yii::$app->formatter->asDate($data['session']->date, 'php:F Y') ?></p>
        <p><strong>Statut :</strong> <?= $data['session']->active ? 'Active' : 'Clôturée' ?></p>
        <p><strong>Année :</strong> <?= $data['exercise']->year ?></p>
        <p><strong>Taux d'intérêt :</strong> <?= $data['exercise']->interest ?>%</p>
        <p><strong>Montant inscription :</strong> <?= number_format($data['exercise']->inscription_amount, 0, ',', ' ') ?> XAF</p>
        <p><strong>Montant fond social :</strong> <?= number_format($data['exercise']->social_crown_amount, 0, ',', ' ') ?> XAF</p>
    </div>

    <div class="print-summary">
        <h3>Liste des membres</h3>
        <table class="print-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>État Inscription</th>
                    <th>État Fond Social</th>
                    <th>Montant Inscription</th>
                    <th>Montant Fond Social</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['members'] as $member): ?>
                    <tr>
                        <td><?= Html::encode($member->username) ?></td>
                        <td><?= $member->inscription >= $data['exercise']->inscription_amount ? 'Payé' : 'En retard' ?></td>
                        <td><?= $member->social_crown >= $data['exercise']->social_crown_amount ? 'Payé' : 'En retard' ?></td>
                        <td><?= number_format($member->inscription, 0, ',', ' ') ?> XAF</td>
                        <td><?= number_format($member->social_crown, 0, ',', ' ') ?> XAF</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
