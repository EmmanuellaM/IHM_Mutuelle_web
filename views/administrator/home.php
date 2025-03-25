<?php $this->beginBlock('style') ?>
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #2193b0, #6dd5ed);
        --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        --radius-md: 12px;
        --radius-lg: 20px;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    #saving-amount-title {
        font-size: 4.5rem;
        font-weight: 700;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        transition: transform 0.3s ease;
    }

    #saving-amount-title:hover {
        transform: translateY(-5px);
    }

    .img-bravo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        box-shadow: var(--shadow-md);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .img-bravo:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: var(--shadow-lg);
    }

    .media {
        border-bottom: 2px solid #f0f0f0;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        transition: transform 0.3s ease;
    }

    .media:hover {
        transform: translateX(5px);
    }

    #social-crown {
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1rem;
        transition: transform 0.3s ease;
    }

    #social-crown:hover {
        transform: scale(1.05);
    }

    .white-block {
        height: 100%;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background-color: #ffffff;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-md);
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .white-block:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .white-block h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .white-block h1 {
        font-size: 2.5rem;
        font-weight: 700;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
    }

    .btn {
        border-radius: 30px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: var(--primary-gradient);
        border: none;
        box-shadow: var(--shadow-sm);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .modal-content {
        border-radius: var(--radius-lg);
        border: none;
        box-shadow: var(--shadow-lg);
    }

    .modal-body {
        padding: 2rem;
    }

    .blue-gradient {
        background: var(--primary-gradient);
        color: white;
        border-radius: var(--radius-md);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stats-grid .white-block {
        margin: 0;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 200px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    .info-block {
        background: white;
        border-radius: var(--radius-md);
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        height: 100%;
    }

    .info-block-title {
        font-size: 1.25rem;
        color: #2c3e50;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .media-list {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    .media-list::-webkit-scrollbar {
        width: 6px;
    }

    .media-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .media-list::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .media-list::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
<?php $this->endBlock() ?>

<div class="container mt-5 mb-5">
    <div class="row mb-4">
        <div class="col-12 white-block blue-gradient">
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
                <div class="white-block">
                    <h3 class="text-muted">Aucune session active</h3>
                </div>
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

    <div class="stats-grid">
        <div class="white-block">
            <h3 class="text-muted">Inscriptions</h3>
            <h1 id="social-crown"><?= ($t = \app\managers\FinanceManager::socialCrown()) ? ($t > 0 ? $t : 0) : 0 ?> XAF</h1>
        </div>
        <div class="white-block">
            <h3 class="text-muted">Épargnes</h3>
            <h1 id="saving-amount-title"><?= ($t = \app\managers\FinanceManager::totalSavedAmount()) ? ($t > 0 ? $t : 0) : 0 ?> XAF</h1>
        </div>
        <div class="white-block">
            <h3 class="text-muted">Emprunts</h3>
            <h1><?= ($t = \app\managers\FinanceManager::totalBorrowedAmount()) ? ($t > 0 ? $t : 0) : 0 ?> XAF</h1>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="white-block h-100">
                <h3 class="info-block-title">Aides</h3>
                <div class="media-list">
                    <?php
                    $helps = \app\models\Help::findAll(['state' => true]);
                    if (count($helps)) :
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
                    else:
                    ?>
                        <p class="text-center text-primary">Aucune aide active</p>
                    <?php
                    endif;
                    ?>
                    <p class="text-center mt-3">
                        <a href="<?= Yii::getAlias("@administrator.new_help") ?>" class="btn btn-primary">Créer une nouvelle aide</a>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="white-block h-100">
                <h3 class="info-block-title">Emprunts actifs</h3>
                <div class="media-list">
                    <?php
                    if ($session && isset($exercise)) :
                        $borrowings = $exercise->borrowings();
                        if (count($borrowings)) :
                            foreach ($borrowings as $borrowing) :
                                $member = $borrowing->member();
                                $user = $member->user();
                                $intendedAmount = \app\managers\FinanceManager::intendedAmountFromBorrowing($borrowing);
                                $refundedAmount = $borrowing->refundedAmount();
                                $rest = $intendedAmount - $refundedAmount;
                    ?>
                                <div class="media">
                                    <img class="d-flex mr-3" width="50" height="50" src="<?= \app\managers\FileManager::loadAvatar($user) ?>" alt="Generic placeholder image">
                                    <div class="media-body">
                                        <h5 class="mt-0 font-weight-bold"><?= $user->name . ' ' . $user->first_name ?></h5>
                                        <span class="text-secondary">Montant emprunté : <?= $borrowing->amount ?> XAF</span>
                                        <br>
                                        <span class="text-secondary">Intérêt : <?= $borrowing->interest ?> %</span>
                                        <br>
                                        <span style="font-size: 1.5rem" class="text-secondary"><?= $refundedAmount ?> / <?= $intendedAmount ?> XAF</span>
                                        <div class="text-right">
                                            <a href="<?= Yii::getAlias("@administrator.borrowing_details") . "?q=" . $borrowing->id ?>" class="btn btn-primary p-2">Détails</a>
                                        </div>
                                    </div>
                                </div>
                    <?php
                            endforeach;
                        else:
                    ?>
                            <p class="text-center text-primary">Aucun emprunt actif</p>
                    <?php
                        endif;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>