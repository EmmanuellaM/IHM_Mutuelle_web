<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 31/12/18
 * Time: 14:31
 */
$this->beginBlock('title') ?>
    Nouvelle aide
<?php $this->endBlock() ?>
<?php $this->beginBlock('style') ?>
    <style>
        .info-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .info-box h4 {
            margin-bottom: 15px;
            font-weight: bold;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 500;
        }
        .info-value {
            font-weight: bold;
            font-size: 1.1em;
        }
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
            display: none;
        }
        .success-box {
            background: #d4edda;
            border: 1px solid #28a745;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
            display: none;
        }
    </style>
<?php $this->endBlock() ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <?php if (count(\app\models\Member::find()->where(['active' => true]) ->all()) >1 ):?>
            <div class="col-12 mb-2">
                <h3 class="text-center text-muted">Nouvelle aide financière</h3>
            </div>
            <?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= Yii::$app->session->getFlash('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php elseif (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= Yii::$app->session->getFlash('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

            <!-- Info Box for Social Fund -->
            <div class="col-md-8 col-12">
                <div class="info-box">
                    <h4>💰 Informations Financières</h4>
                    <div class="info-item">
                        <span class="info-label">Fonds Social Disponible:</span>
                        <span class="info-value" id="available-fund"><?= number_format(\app\managers\FinanceManager::getAvailableSocialFund(), 0, ',', ' ') ?> XAF</span>
                    </div>
                    <div class="info-item" id="help-amount-row" style="display: none;">
                        <span class="info-label">Montant de l'aide sélectionnée:</span>
                        <span class="info-value" id="help-amount">0 XAF</span>
                    </div>
                    <div class="info-item" id="social-fund-status-row" style="display: none;">
                        <span class="info-label">Statut:</span>
                        <span class="info-value" id="social-fund-status"></span>
                    </div>
                </div>
                
                <div class="success-box" id="success-message">
                    ✅ Le fonds social couvrira entièrement cette aide.
                </div>
                
                <div class="warning-box" id="warning-message">
                    ⚠️ Le fonds social est insuffisant pour cette aide. Veuillez choisir un autre type d'aide ou attendre que le fonds social soit renfloué.
                </div>
            </div>

            <?php
            $form = \yii\widgets\ActiveForm::begin([
                'method' => 'post',
                'errorCssClass' => 'text-secondary',
                'action' => '@administrator.add_help',
                'options' =>['class' => 'col-md-8 col-12 white-block']
            ]);

            $help_types = \app\models\HelpType::find()->where(['active'=> true])->all();
            $members = \app\models\Member::find()->where(['active' => true])->all();

            $heps = [];
            $help_amounts = []; // Store amounts for JavaScript
            foreach ($help_types as $help_type) {
                $heps[$help_type->id] = $help_type->title." - ".$help_type->amount.' XAF';
                $help_amounts[$help_type->id] = $help_type->amount;
            }


            $items = [];
            foreach ($members as $member) {
                $user = \app\models\User::findOne($member->user_id);
                $items[$member->id] = $user->name . " " . $user->first_name;
            }

            ?>

            <?= $form->field($model,"help_type_id")->dropDownList($heps,['required'=> 'required', 'prompt' => 'Sélectionnez un type d\'aide', 'id' => 'help-type-select'])->label("Type d'aide") ?>
            <?= $form->field($model,"member_id")->dropDownList($items, ['required' => 'required', 'prompt' => 'Sélectionnez un membre'])->label("Membre concerné par l'aide") ?>
            <?= $form->field($model,"comments")->textarea()->label("Commentaires à propos de l'aide") ?>

        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </div>
            <?php
            \yii\widgets\ActiveForm::end();
            ?>
        <?php else:?>
        <div class="col-12">
            <h3 class="text-center text-muted">Impossible de créer une aide avec moins de 2 membres en règle.</h3>
            <div class="text-center mt-2">
                <a href="<?= Yii::getAlias("@administrator.new_member")?>" class="btn btn-primary">Nouveau membre</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const helpTypeSelect = document.getElementById('help-type-select');
    const availableFund = <?= \app\managers\FinanceManager::getAvailableSocialFund() ?>;
    const activeMembersCount = <?= count(\app\models\Member::find()->where(['active' => true])->all()) - 1 ?>;
    const helpAmounts = <?= json_encode($help_amounts) ?>;
    
    helpTypeSelect.addEventListener('change', function() {
        const selectedHelpTypeId = this.value;
        
        if (!selectedHelpTypeId || !helpAmounts[selectedHelpTypeId]) {
            // Hide all dynamic rows
            document.getElementById('help-amount-row').style.display = 'none';
            document.getElementById('social-fund-status-row').style.display = 'none';
            document.getElementById('warning-message').style.display = 'none';
            document.getElementById('success-message').style.display = 'none';
            return;
        }
        
        const helpAmount = parseFloat(helpAmounts[selectedHelpTypeId]);
        
        // Update display
        document.getElementById('help-amount').textContent = helpAmount.toLocaleString('fr-FR') + ' XAF';
        
        // Show rows
        document.getElementById('help-amount-row').style.display = 'flex';
        document.getElementById('social-fund-status-row').style.display = 'flex';
        
        // Check if social fund is sufficient
        if (availableFund >= helpAmount) {
            document.getElementById('social-fund-status').textContent = '✓ Suffisant';
            document.getElementById('social-fund-status').style.color = '#4ade80';
            document.getElementById('success-message').style.display = 'block';
            document.getElementById('warning-message').style.display = 'none';
        } else {
            document.getElementById('social-fund-status').textContent = '✗ Insuffisant';
            document.getElementById('social-fund-status').style.color = '#ef4444';
            document.getElementById('warning-message').style.display = 'block';
            document.getElementById('success-message').style.display = 'none';
        }
    });
});
</script>
