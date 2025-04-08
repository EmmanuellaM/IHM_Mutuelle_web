<?php
$this->beginBlock('title') ?>
Détails de la session
<?php $this->endBlock()?>

<?php $this->beginBlock('style')?>
<style>
    .details-container {
        padding: 2rem;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        min-height: 100vh;
    }

    .session-info {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .session-info h1 {
        font-size: 2rem;
        color: #2c3e50;
        margin-bottom: 1.5rem;
    }

    .session-info .info-row {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #f8f9fc;
        border-radius: 8px;
    }

    .session-info .info-label {
        flex: 0 0 150px;
        color: #495057;
        font-weight: 600;
    }

    .session-info .info-value {
        flex: 1;
        color: #2c3e50;
    }

    .amount-box {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .amount-box h4 {
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .amount-box .amount-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .amount-box .amount-label {
        color: #495057;
        font-size: 0.9rem;
    }

    .member-list {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .member-list h4 {
        color: #2c3e50;
        margin-bottom: 1.5rem;
    }

    .member-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    .member-item:last-child {
        border-bottom: none;
    }

    .member-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 1rem;
        background: #4e73df;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    .member-info {
        flex: 1;
    }

    .member-name {
        color: #2c3e50;
        font-weight: 600;
    }

    .member-role {
        color: #4e73df;
        font-size: 0.9rem;
    }

    .no-content {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
        padding: 0.75rem 2rem;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #2e59d9;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
    }

    @media (max-width: 768px) {
        .session-info {
            padding: 1.5rem;
        }

        .info-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .info-label {
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>
<?php $this->endBlock()?>

<div class="details-container">
    <div class="container">
        <?php if($session):?>

            <div class="session-info">
                <h1>Détails de la session</h1>

                <div class="info-row">
                    <div class="info-label">Numéro de session</div>
                    <div class="info-value"><?= $session->number() ?></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Date</div>
                    <div class="info-value"><?= (new DateTime($session->date))->format("d-m-Y") ?></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Statut</div>
                    <div class="info-value"><?= $session->active ? "Active" : "Terminée" ?></div>
                </div>
            </div>

            <div class="amount-box">
                <h4>Total des montants</h4>
                
                <?php $savingAmount = \app\models\Saving::find()->where(['session_id' => $session->id])->sum('amount'); ?>
                <div class="amount-value"><?= $savingAmount ? $savingAmount : 0 ?> XAF</div>
                <div class="amount-label">Total des épargnes</div>

                <?php $refundAmount = \app\models\Refund::find()->where(['session_id' => $session->id])->sum('amount'); ?>
                <div class="amount-value"><?= $refundAmount ? $refundAmount : 0 ?> XAF</div>
                <div class="amount-label">Total des remboursements</div>

                <?php $borrowingAmount = \app\models\Borrowing::find()->where(['session_id' => $session->id])->sum('amount'); ?>
                <div class="amount-value"><?= $borrowingAmount ? $borrowingAmount : 0 ?> XAF</div>
                <div class="amount-label">Total des emprunts</div>
            </div>

            <div class="member-list">
                <h4>Membres présents</h4>

                <?php $savings = \app\models\Saving::find()->where(['session_id' => $session->id])->all() ?>
                <?php $members = array_unique(array_map(function($saving) { return $saving->member_id; }, $savings)); ?>

                <?php if(count($members)): ?>
                    <?php foreach($members as $memberId): ?>
                        <?php $member = \app\models\Member::findOne($memberId); ?>
                        <?php $user = $member->user(); ?>
                        <?php $administrator = $member->administrator(); ?>
                        <div class="member-item">
                            <div class="member-avatar">
                                <?= substr($user->name, 0, 1) . substr($user->first_name, 0, 1) ?>
                            </div>
                            <div class="member-info">
                                <div class="member-name"><?= $user->name . ' ' . $user->first_name ?></div>
                                <div class="member-role"><?= $administrator ? "Administrateur" : "Membre" ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-content">
                        <h1 class="text-muted">Aucun membre présent</h1>
                    </div>
                <?php endif; ?>
            </div>

            <div class="text-center mt-4">
                <a href="<?= Yii::getAlias("@member.sessions") ?>" class="btn btn-primary">Retour aux sessions</a>
            </div>

        <?php else: ?>
            <div class="no-content">
                <h1 class="text-muted">Session non trouvée</h1>
            </div>
        <?php endif; ?>
    </div>
</div>
