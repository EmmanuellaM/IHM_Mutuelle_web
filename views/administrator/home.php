<?php $this->beginBlock('title') ?>
Accueil
<?php $this->endBlock() ?>
<?php $this->beginBlock('style') ?>
<style>
    #saving-amount-title {
        font-size: 5rem;
        color: white;
    }

    .img-bravo {
        width: 100px;
        height: 100px;
        border-radius: 100px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.51);
    }

    .media {
        border-bottom: 1px solid #ededed;
    }

    #social-crown {
        font-size: 5rem;
    }
</style>
<?php $this->endBlock() ?>
<div class="container mt-5 mb-5">
    <div class="row mb-2">
        <div class="col-12 white-block text-center blue-gradient ">
            <?php if ($session) : ?>
                <?php
                $exercise = \app\models\Exercise::findOne(['active' => true]);

                ?>


                </h3>
                <?php
                if ($session->state == "SAVING" || $session->state == "REFUND" || $session->state == "BORROWING") :
                ?>
                    <div class="mt-3 row align-items-center">
                    <h4 class="col-3 text-left text-white">
                    <?php
                        $monthNames = [
                            '01' => 'Janvier',
                            '02' => 'Février',
                            '03' => 'Mars',
                            '04' => 'Avril',
                            '05' => 'Mai',
                            '06' => 'Juin',
                            '07' => 'Juillet',
                            '08' => 'Août',
                            '09' => 'Septembre',
                            '10' => 'Octobre',
                            '11' => 'Novembre',
                            '12' => 'Décembre',
                        ];

                        $monthNumber = Yii::$app->formatter->asDate($session->date, 'MM');
                        $monthName = $monthNames[$monthNumber];
                        ?>
                        Session du <?= Yii::$app->formatter->asDate($session->date, 'd')?> <?= $monthName ?>
                    </h4>
                        <div class="col-9 text-right">
                            <?php if (\app\managers\FinanceManager::numberOfSession() < 12) : ?>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modal-cloturer">Cloturer la session</button>
                                <button class="btn bg-success <?= $model->hasErrors() ? 'in' : '' ?>"  id = "modifier-session" data-toggle="modal" data-target="#modal-modifier-session">Modifier la session</button>
                            <?php else : ?>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modal-cloturer">Cloturer l'exercice</button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Modal for returning to refunds -->
                    <div class="modal fade" id="modal-rentrer-remboursement" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <p>Êtes-vous sûr(e) de vouloir retourner aux remboursements? Tous les emprunts enregistrés seront perdus.</p>
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Non</button>
                                        <a href="<?= Yii::getAlias("@administrator.back_to_refunds") . "?q=" . $session->id ?>" class="btn btn-primary">Oui</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (\app\managers\FinanceManager::numberOfSession() < 12) : ?>
                        <!-- Modal for closing a session -->
                        <div class="modal fade" id="modal-cloturer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <p>Êtes-vous sûr(e) de vouloir cloturer la session? Vous ne pourrez plus faire aucun enregistrement.</p>
                                        <div class="mt-3">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Non</button>
                                            <a href="<?= Yii::getAlias("@administrator.cloture_session") . "?q=" . $session->id ?>" class="btn btn-primary">Oui</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal for update a session -->
                        <div class="modal fade" id="modal-modifier-session" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <?php $form = \yii\widgets\ActiveForm::begin([
                                        'errorCssClass' => 'text-secondary',
                                        'method' => 'post',
                                        'action' => ['@administrator.update_session', 'id' => $session->id],
                                        'options' => ['class' => 'modal-body']
                                    ]) ?>
                                    <?= $form->field($model, 'date')->input('date', ['required' => 'required', "value" => $session-> date])->label("Date de la rencontre de la session actuelle") ?>
                                    <div class="form-group text-right">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">Modifier la session</button>
                                    </div>
                                    <?php \yii\widgets\ActiveForm::end(); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Modal for deleting a session -->
                        <div class="modal fade" id="modal-supprimer-session" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <p class="text-center text-danger">Attention! Cette action est irréversible. Êtes-vous sûr(e) de vouloir supprimer la session ?</p>
                                        <div class="mt-3 text-center">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
                                            <a href="<?= Yii::getAlias("@administrator.delete_session") . "?q=" . $session->id ?>" class="btn btn-danger">Oui</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <!-- Modal for closing the exercise -->
                        <div class="modal fade" id="modal-cloturer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="text-center mb-2">
                                            <img src="/img/bravo.jpg" alt="bravo" class="img-bravo">
                                        </div>
                                        <p class="text-center text-secondary">Félicitations !</p>
                                        <p>Vous êtes au terme de l'exercice. Voulez-vous passer au décaissement?</p>
                                        <div class="mt-3">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Non</button>
                                            <a href="<?= Yii::getAlias("@administrator.cloture_exercise") . "?q=" . $session->id ?>" class="btn btn-primary">Oui</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else : ?>
                <?php
                $exercise = \app\models\Exercise::findOne(['active' => true]);

                ?>
                <h3 class="mb-3 text-white">Aucune session en activité</h3>
                <button class="btn btn-primary <?= $model->hasErrors() ? 'in' : '' ?>" data-toggle="modal" data-target="#modalLRFormDemo">
                    <?php if ($exercise) : ?>
                        Commencer une nouvelle session
                    <?php else : ?>
                        Commencer un nouvel exercice
                    <?php endif; ?>
                </button>

                <div class="modal fade" id="modalLRFormDemo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <?php $form = \yii\widgets\ActiveForm::begin([
                                'errorCssClass' => 'text-secondary',
                                'method' => 'post',
                                'action' => '@administrator.new_session',
                                'options' => ['class' => 'modal-body']
                            ]) ?>
                            <?php if (!$exercise) : ?>
                                <?= $form->field($model, 'year')->input('text', [
                                    'required' => 'required',
                                    'readonly' => 'readonly',
                                    'value' => date('Y')
                                ]) ?>
                                <?= $form->field($model, 'interest')->input('number', ['required' => 'required', 'step' => '0.01'])->label("Taux d'intérêt (%)") ?>
                            <?php endif; ?>
                            <?= $form->field($model, 'date')->input('date', ['required' => 'required'])->label("Date de la rencontre de la première session") ?>
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">
                                    <?php if ($exercise) : ?>
                                        Créer la session
                                    <?php else : ?>
                                        Créer l'exercice
                                    <?php endif; ?>
                                </button>
                            </div>
                            <?php \yii\widgets\ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <?php
        ?>
        <div class="col-md-12  pr-4">
            <div class="row">
                <div class="col-5 white-block mr-2">
                    <h3 class="text-center text-muted">Inscriptions</h3>
                    <h1 id="social-crown" class="blue-text text-center"><?= ($t = \app\managers\FinanceManager::socialCrown()) ? ($t > 0 ? $t : 0) : 0 ?> XAF</h1>

                    <h3 class="text-center text-muted">Evènements de la mutuelle</h3>
                    <?php
                    $helps = \app\models\Help::findAll(['state' => true]);
                    ?>
                    <?php
                    if (count($helps)) :
                    ?>
                        <?php
                        foreach ($helps as $help) :
                            $member = $help->member();
                            $user = $member->user();
                            $helpType = $help->helpType();
                        ?>
                            <div class="media">
                                <img class="d-flex mr-3" width="60" height="60" src="<?= \app\managers\FileManager::loadAvatar($user) ?>" alt="Generic placeholder image">
                                <div class="media-body">
                                    <h5 class="mt-0 font-weight-bold"><?= $helpType->title ?></h5>
                                    <span class="blue-text"><b><?= $user->name . ' ' . $user->first_name ?></b></span>
                                    <br>
                                    <?= $help->comments ?>
                                    <br>
                                    <span style="font-size: 1.5rem" class="text-secondary"><?= ($t = $help->getContributedAmount()) ? $t : 0 ?> / <?= $help->amount ?> XAF</span>
                                    <div class="text-right">
                                        <a href="<?= Yii::getAlias("@administrator.help_details") . "?q=" . $help->id ?>" class="btn btn-primary p-2">Détails</a>
                                    </div>
                                </div>
                            </div>

                        <?php
                        endforeach;
                        ?>
                    <?php
                    else :
                    ?>
                        <p class="text-center text-primary">Aucune aide active</p>
                    <?php
                    endif;
                    ?>
                    <p class="text-center"><a href="<?= Yii::getAlias("@administrator.new_help") ?>" class="btn btn-primary">Créer une nouvelle aide</a></p>


                </div>

                <div class="col-6 white-block ml-2">
                    <h3 class="text-center text-muted">Evènements de la mutuelle</h3>
                    <?php
                    $tontines = \app\models\Tontine::findAll(['state' => true]);
                    ?>
                    <?php
                    if (count($tontines)) :
                    ?>
                        <?php
                        foreach ($tontines as $tontine) :
                            $member = $tontine->member();
                            $user = $member->user();
                            $tontineType = $tontine->TontineType();
                        ?>
                            <div class="media">
                                <img class="d-flex mr-3" width="60" height="60" src="<?= \app\managers\FileManager::loadAvatar($user) ?>" alt="Generic placeholder image">
                                <div class="media-body">
                                    <h5 class="mt-0 font-weight-bold"><?= $tontineType->title ?></h5>
                                    <span class="blue-text"><b><?= $user->name . ' ' . $user->first_name ?></b></span>
                                    <br>
                                    <?= $tontine->comments ?>
                                    <br>
                                    <span style="font-size: 1.5rem" class="text-secondary"><?= ($t = $tontine->contributedAmount()) ? $t : 0 ?> / <?= $tontine->amount ?> XAF</span>
                                    <div class="text-right">
                                        <a href="<?= Yii::getAlias("@administrator.tontine_details") . "?q=" . $tontine->id ?>" class="btn btn-primary p-2">Détails</a>
                                    </div>
                                </div>
                            </div>

                        <?php
                        endforeach;
                        ?>
                    <?php
                    else :
                    ?>
                        <p class="text-center text-primary">Aucune tontine active</p>
                    <?php
                    endif;
                    ?>
                    <p class="text-center"><a href="<?= Yii::getAlias("@administrator.new_tontine") ?>" class="btn btn-primary">Créer une nouvelle Tontine</a></p>


                </div>
            </div>
            <!-- <div class="col-m-auto mt-4 ">
            
            </div> -->
        </div>




        <?php
        if ($session) :
        ?>
            <?php
            $borrowings = $exercise->borrowings();
            ?>
            <?php
            if (count($borrowings)) :
            ?>
                <div class="white-block mt-2">
                    <h5 class="text-muted text-center mt-3">Emprunts actifs</h5>
                    <div class="d-flex flex-row justify-content-between">
                        <?php
                        foreach ($borrowings as $borrowing) :
                            $member = $borrowing->member();
                            $user = $member->user();

                            $intendedAmount = $borrowing->intendedAmount();
                            $refundedAmount = $borrowing->refundedAmount();
                            $rest = $intendedAmount - $refundedAmount;

                        ?>
                            <div class="media white-block m-2">
                                <img class="d-flex mr-3" width="50" height="50" src="<?= \app\managers\FileManager::loadAvatar($user) ?>" alt="Generic placeholder image">
                                <div class="media-body">
                                    <h5 class="mt-0 font-weight-bold"><?= $user->name . ' ' . $user->first_name ?></h5>
                                    Date : <span class="blue-text"><?= $borrowing->created_at ?> </span>
                                    <br>
                                    Dette : <span class="blue-text"><?= $intendedAmount ?> XAF</span>
                                    <br>
                                    Total remboursé : <span class="blue-text"><?= $borrowing->refundedAmount() ?> XAF</span>
                                    <br>
                                    Reste : <span class="text-secondary"><?= $rest ?> XAF</span>
                                    <br>


                                </div>
                            </div>
                        <?php
                        endforeach;
                        ?>
                    </div>
                </div>
            <?php
            endif;
            ?>
        <?php
        endif;
        ?>
    </div>


</div>