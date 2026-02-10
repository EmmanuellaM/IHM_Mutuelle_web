<?php $this->beginBlock('title') ?>
    <?= htmlspecialchars($member->user()->name . ' ' . $member->user()->first_name) ?>
<?php $this->endBlock() ?>
<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="<?= Yii::getAlias('@web/css/admin-styles.css') ?>">
<?php $this->endBlock() ?>

<div class="container-fluid py-4">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="<?= Yii::getAlias('@administrator.members') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste des membres
        </a>
    </div>

    <!-- Member Profile Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <?= $this->render('_member_details', [
                'member' => $member,
                'exercise' => $exercise,
                'inscriptionModel' => $inscriptionModel,
                'socialModel' => $socialModel
            ]) ?>
        </div>
    </div>
</div>
