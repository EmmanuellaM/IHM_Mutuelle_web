<?php $this->beginBlock('title') ?>
    Administrateurs
<?php $this->endBlock() ?>
<?php $this->beginBlock('style') ?>
    <style>
        #btn-add {
        position: fixed!important;
        bottom: 25px;
        right: 25px;
        z-index: 1000;
       
        font-size: 0.8rem;
        padding: 10px;
    }
    </style>
<?php $this->endBlock() ?>
    <div class="container mt-5 mb-5">
        <div class="row">
            <?php if (count($administrators)): ?>
                <div class="col-12 mt-2">
                    <div class="row">
                        <?php foreach ($administrators as $administrator): ?>
                            <?php
                            $user = $administrator->user();
                            ?>
                            <div class="col-4 mb-2">
                                <div class="card">
                                    <!-- Card image -->
                                    <div class="view overlay">
                                        <img class="card-img-top"
                                             src="<?= \app\managers\FileManager::loadAvatar($user, "256") ?>"
                                             style="height: 12rem" alt="Card image cap">
                                        <a href="javascript:void()">
                                            <div class="mask rgba-white-slight"></div>
                                        </a>
                                    </div>

                                    <!-- Card content -->
                                    <div class="card-body">

                                        <!-- Title -->
                                        <h4 class="card-title text-capitalize"><?= $administrator->username ?>
                                            <?php
                                            if ($user->id == $this->params['user']->id):
                                                ?>
                                                <span class="text-secondary">(Vous)</span>
                                            <?php
                                            endif;
                                            ?>
                                        </h4>
                                        <!-- Text -->
                                        <p class="card-text">
                                            <span>Pseudo : </span><span
                                                    class="blue-text"><?= $administrator->username ?></span>
                                            <br>
                                            <span>Téléphone : </span><span class="text-secondary"><?= $user->tel ?></span>
                                            <br>
                                            <span>Email : </span><span class="blue-text"><?= $user->email ?></span>
                                            <br>
                                            <span>Adresse : </span><span class="text-secondary"><?= $user->address ?></span>
                                            <br>
                                            <span>Créé le : </span><span class="text-secondary"><?= $user->created_at ?></span>
                                        </p>
                                        <!-- Button -->
                                    </div>
                                    <?php
                                    if ($administrator->root):
                                    ?>
                                        <div class="card-footer primary-color text-white text-center" style="height: 50px;">
                                            Root
                                        </div>
                                    <?php
                                    else:
                                    ?>
                                        <?php
                                        $currentUserId = Yii::$app->user->identity->id;
                                        if ($currentUserId == 1):
                                        ?>
                                            <div class="card-footer text-white d-flex align-items-center" style="height: 50px;">
                                                <?php
                                                if ($administrator->active):
                                                ?>
                                                    <div class="flex-grow-1">
                                                        <a href="<?= Yii::getAlias("@administrator.disable_admin")."?q=".$administrator->id ?>" class="btn btn-warning">Desactiver</a>
                                                    </div>
                                                <?php
                                                else:
                                                ?>
                                                    <div class="flex-grow-1">
                                                        <a href="<?= Yii::getAlias("@administrator.enable_admin")."?q=".$administrator->id ?>" class="btn btn-primary">Activer</a>
                                                    </div>
                                                <?php
                                                endif;
                                                ?>
                                                <div>
                                                    <?= \yii\helpers\Html::a('Supprimer', ['administrator/supprimer-admin', 'q' => $administrator->id], [
                                                        'class' => 'btn btn-danger',
                                                        'data' => [
                                                            'confirm' => 'Are you sure you want to delete this administrator?',
                                                            'method' => 'post',
                                                        ],
                                                    ]) ?>
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
                        <?php
                        endforeach;
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php
if ($this->params['administrator']->root):
    ?>
    <a href="<?= Yii::getAlias("@administrator.new_administrator") ?>" class="btn btn-secondary text-black" id="btn-add">Ajouter Administrateur</a>
<?php
endif;