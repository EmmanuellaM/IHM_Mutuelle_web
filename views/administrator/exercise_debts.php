<?php $this->beginBlock('title') ?>
    Dettes
<?php $this->endBlock() ?>
<?php $this->beginBlock('style') ?>
    <style>
        .white-block {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 30px;
        }

        .warning-block {
            border: none;
            color: #e67e22;
            background-color: #fff3e0;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(230, 126, 34, 0.1);
        }

        .section-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: #3498db;
            border-radius: 3px;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .table th {
            font-weight: 500;
            border: none;
            padding: 15px;
        }

        .table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            border-radius: 5px;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-content h3 {
            color: #2c3e50;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .blue-text {
            color: #3498db !important;
            font-weight: 600;
        }

        .red-text {
            color: #e74c3c !important;
            font-weight: 600;
        }

        .text-muted {
            color: #7f8c8d !important;
        }
    </style>
<?php $this->endBlock() ?>
<?php
$refunds = \app\models\Refund::find()->where(['is not','exercise_id',null])->all();
?>
<div class="container mb-5 mt-5">


        <div class="row mb-2">
            <div class="col-12 white-block">
                <h3 class="text-center section-title">Inscriptions</h3>
                <hr>

                <?php
                $members = \app\models\Member::find()->where(['<','social_crown', \app\managers\SettingManager::getInscription()])->all();
                if (count($members)):
                ?>
                <table class="table table-hover">
                    <thead class="blue-grey lighten-4">
                    <tr>
                        <th>#</th>
                        <th>Membre</th>
                        <th>montant réglé</th>
                        <th>Montant restant à payer</th>
                        <th>action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($members as $index => $member): ?>
                        <?php
                        $memberUser = $member->user();
                        ?>
                            <tr>
                                <th scope="row"><?= $index + 1 ?></th>
                                <td class="text-capitalize"><?= $memberUser->name . " " . $memberUser->first_name ?></td>
                                <td class="blue-text"><?=  $member->social_crown ?> XAF</td>

                                <td class="red-text"><?=  \app\managers\SettingManager::getInscription()- $member->social_crown ?></td>
                                    <td><button class="btn btn-primary p-2 m-0" data-target="#modalS<?= $member->id ?>" data-toggle="modal">payer </button></td>

                            </tr>


                    <div class="modal fade" id="modalS<?= $member->id ?>" tabindex="-1" role="dialog"
                    aria-labelledby="myModalLabel"
                    aria-hidden="true">
                         <div class="modal-dialog" role="document">
                            <div class="modal-content">

                            <?php
                            $model = new \app\models\forms\FixInscriptionForm();
            $form = \yii\widgets\ActiveForm::begin([
                'errorCssClass' => 'text-secondary',
                'method' => 'post',
                'action' => ['@administrator.fix_inscription', 'id' => $member->id],
                'options' => [
                    'class' => 'col-12 white-block',
                    'data-current-amount' => $member->social_crown,
                    'data-max-amount' => \app\managers\SettingManager::getInscription() - $member->social_crown,
                ],
            ]);
            ?>

            <h3>Veuillez entrer le montant à payer</h3>
            <?= $form->field($model, 'amount')->input('number', [
                'required' => 'required',
                'min' => 1,
                'max' => \app\managers\SettingManager::getInscription() - $member->social_crown,
            ])->label("Montant") ?>
            <?= $form->field($model, 'id')->hiddenInput(['value' => $member->id])->label(false) ?>
            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary">Valider</button>
            </div>
            <?php \yii\widgets\ActiveForm::end(); ?>

        </div>
    </div>
</div>

                    <?php endforeach; ?>
                    </tbody>
                </table>

                <?php
                else:
                ?>
                <p class="text-center blue-text">Aucune dette de règlement de Inscription</p>
                <?php
                endif;
                ?>
            </div>
        </div>


        <div class="row mb-2">
            <div class="col-12 white-block">
                <h3 class="text-center section-title">Fond Social</h3>
                <hr>

                <?php
                $members = \app\models\Member::find()->where(['<','social_crown', \app\managers\SettingManager::getSocialCrown()])->all();
                if (count($members)):
                ?>
                <table class="table table-hover">
                    <thead class="blue-grey lighten-4">
                    <tr>
                        <th>#</th>
                        <th>Membre</th>
                        <th>montant réglé</th>
                        <th>Montant restant à payer</th>
                        <th>action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($members as $index => $member): ?>
                        <?php
                        $memberUser = $member->user();
                        ?>
                            <tr>
                                <th scope="row"><?= $index + 1 ?></th>
                                <td class="text-capitalize"><?= $memberUser->name . " " . $memberUser->first_name ?></td>
                                <td class="blue-text"><?=  $member->inscription ?> XAF</td>

                                <td class="red-text"><?=  \app\managers\SettingManager::getSocialCrown()- $member->inscription ?></td>
                                    <td><button class="btn btn-primary p-2 m-0" data-target="#modalS<?= $member->id ?>" data-toggle="modal">payer </button></td>

                            </tr>


                        <div class="modal fade" id="modalS<?= $member->id?>" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">


                                    <?php                                    $model = new  \app\models\forms\FixInscriptionForm();

                                     $form = \yii\widgets\ActiveForm::begin([
                                'errorCssClass' => 'text-secondary',
                                'method' => 'post',
                                'action' => ['@administrator.fix_social_crown', 'id'=>$member->id],
                                'options' => ['class' => 'col-12 white-block']
                                ]) ?>

                            <h3> Veuillez entrer le montant à payer</h3>
                                <?= $form->field($model, 'amount')->input('number', ['required' => 'required', 'min' =>1])->label("montant") ?>
                                <?= $form->field($model,'id')->hiddenInput(['value'=>$member->id])->label(false) ?>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary" >valider </button>
                                
                            </div>
                            <?php \yii\widgets\ActiveForm::end(); ?>

                                    
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                    </tbody>
                </table>

                <?php
                else:
                ?>
                <p class="text-center blue-text">Aucune dette de règlement de fond social</p>
                <?php
                endif;
                ?>
            </div>
        </div>



    <div class="row">
        <div class="col-12 white-block">
            <h3 class="text-muted text-center section-title">Dettes d'exercices</h3>
            <hr>
            <p class="warning-block text-center">
                Attention ! Il s'agit des dettes d'exercices qui n'ont pas été remboursées.
                
            </p>

            <?php
            if (count($refunds)):
            ?>
            <table class="table table-hover">
                <thead class="blue-grey lighten-4">
                <tr>
                    <th>#</th>
                    <th>Membre</th>
                    <th>Montant</th>
                    <th>Année de l'exercice</th>
                    <th></th>
                </tr>

                </thead>
                <tbody>
                <?php foreach ($refunds as $index => $refund): ?>
                    <?php $member = \app\models\Member::findOne((\app\models\Borrowing::findOne($refund->borrowing_id))->member_id);
                    $memberUser = \app\models\User::findOne($member->user_id);
                    $exercise = \app\models\Exercise::findOne($refund->exercise_id);
                    ?>
                    <tr>
                        <th scope="row"><?= $index + 1 ?></th>
                        <td class="text-capitalize"><?= $memberUser->name . " " . $memberUser->first_name ?></td>
                        <td class="blue-text"><?= $refund->amount ?> XAF</td>
                        <td class="text-capitalize"><?= $exercise->year ?></td>
                        <td><button class="btn btn-primary m-0 p-2" data-toggle="modal" data-target="#modal<?= $index?>">Regler</button></td>
                    </tr>


                <div class="modal  fade" id="modal<?= $index ?>" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">

                                <p class="text-center">Êtes-vous sûr(e) de vouloir régler la dette de ce membre?
                                </p>

                                <div class="form-group text-center">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
                                    <a href="<?= Yii::getAlias("@administrator.treat_debt")."?q=".$refund->id?>" class="btn btn-primary">Oui</a>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
                </tbody>
            </table>

            <?php
            else:
            ?>
            <h3 class="text-muted text-center">Aucune dette d'exercice enregistrée</h3>

            <?php
            endif;
            ?>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form[data-max-amount]').forEach(form => {
            form.addEventListener('submit', event => {
                const input = form.querySelector('input[name="FixInscriptionForm[amount]"]');
                const maxAmount = parseInt(form.dataset.maxAmount, 10);

                if (parseInt(input.value, 10) > maxAmount) {
                    event.preventDefault();
                    alert(`Le montant saisi dépasse le montant restant (${maxAmount} XAF). Veuillez corriger.`);
                }
            });
        });
    });
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form[data-max-fund]').forEach(form => {
            form.addEventListener('submit', event => {
                const input = form.querySelector('input[name="FixSocialCrownForm[fund]"]');
                const maxSocialCrown = parseInt(form.dataset.maxSocialCrown , 10);

                if (parseInt(input.value, 10) > maxSocialCrown ) {
                    event.preventDefault();
                    alert(`Le montant saisi dépasse le montant restant (${maxSocialCrown } XAF). Veuillez corriger.`);
                }
            });
        });
    });
</script>