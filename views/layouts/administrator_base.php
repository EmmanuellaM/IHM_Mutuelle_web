<?php

use app\managers\AdministratorSessionManager;
use yii\helpers\Html;
$this->title = "Mutuelle - ENSPY";
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <?php include Yii::getAlias("@app") . "/includes/links.php"; ?>

    <link href="<?= Yii::getAlias("@web") . '/css/admin.css' ?>" rel="stylesheet">

    <title>
        <?php if (isset($this->blocks['title'])) : ?>
            <?= $this->blocks['title'] ?>
        <?php else : ?>
            <?= Html::encode($this->title) ?>
        <?php endif; ?>
    </title>



    <style>
        .profile-icon {
            width: 30px;
            height: 30px;
            border-radius: 50px;
        }

        #btn-disconnect {
            margin: 5px;
            position: fixed;
            bottom: 10px;


        }

        ul li {
            padding: 5px;
            margin: 5px;
            border-radius: 50px;
            border-width: 2px;
            background-color: whitesmoke;
            color: black;
        }
    </style>

    <?php if (isset($this->blocks['style'])) : ?>
        <?= $this->blocks['style'] ?>
    <?php endif; ?>
</head>

<body class="grey lighten-3">
    <?php $this->beginBody() ?>

    <!--Main Navigation-->
    <header>
        <!-- Navbar -->
        <nav class="navbar fixed-top navbar-expand-lg navbar-light white scrolling-navbar blue-gradient">
            <div class="container-fluid">

                <!-- Brand -->
                <a class="navbar-brand waves-effect" href="<?= Yii::getAlias("@administrator.home") ?>">
                    <img src="/img/icon.png" alt="ENSP" style="width: 40px; height: 40px;" class="d-md-none">
                </a>

                <!-- Collapse -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Links -->
                <div class="collapse navbar-collapse " id="navbarSupportedContent">

                    <!-- Left -->
                    <ul class="navbar-nav mr-auto ">
                        <li class="nav-item">
                            <a class="nav-link waves-effect  <?= AdministratorSessionManager::isHeadHome() ? 'blue-text ' : '' ?>" href="<?= Yii::getAlias("@administrator.home") ?>">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link waves-effect  <?= AdministratorSessionManager::isHeadExercise() ? 'blue-text' : '' ?>" href="<?= Yii::getAlias("@administrator.exercises") ?>">Exercices</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link waves-effect  <?= AdministratorSessionManager::isHeadSession() ? 'blue-text' : '' ?>" href="<?= Yii::getAlias("@administrator.sessions") ?>">Sessions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link waves-effect  <?= AdministratorSessionManager::isHeadSaving() ? 'blue-text' : '' ?>" href="<?= Yii::getAlias("@administrator.savings") ?>">Epargnes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link waves-effect  <?= AdministratorSessionManager::isHeadBorrowing() ? 'blue-text' : '' ?>" href="<?= Yii::getAlias("@administrator.borrowings") ?>">Emprunts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link waves-effect  <?= AdministratorSessionManager::isHeadRefund() ? 'blue-text' : '' ?>" href="<?= Yii::getAlias("@administrator.refunds") ?>">Remboursements</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link waves-effect  <?= AdministratorSessionManager::isHeadTontine() ? 'blue-text' : '' ?>" href="<?= Yii::getAlias("@administrator.tontines") ?>">Tontine</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link waves-effect  <?= AdministratorSessionManager::isHeadExerciseDebt() ? 'blue-text' : '' ?>" href="<?= Yii::getAlias("@administrator.exercise_debts") ?>">Dettes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link waves-effect  <?= AdministratorSessionManager::isHeadHelp() ? 'blue-text' : '' ?>" href="<?= Yii::getAlias("@administrator.helps") ?>">Aides</a>
                        </li>
                    </ul>

                    <!-- Right -->
                    <ul class="navbar-nav nav-flex-icons">
                        <li class="nav-item dropdown mr-auto">
                            <a class="nav-link dropdown-toggle waves-effect" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="<?= \app\managers\FileManager::loadAvatar($this->params['user']) ?>" class="profile-icon" alt="<?= $this->params['administrator']->username ?>">
                                <span><?= $this->params['administrator']->username ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
                                <a href="<?= Yii::getAlias("@administrator.profile") ?>" class="dropdown-item waves-effect">
                                    <i class="fas fa-user mr-3"></i>Profil</a>
                                <div class="dropdown-divider"></div>
                                <a href="<?= Yii::getAlias("@administrator.settings") ?>" class="dropdown-item waves-effect">
                                    <i class="fas fa-cogs mr-3"></i>Configurations</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('disconnection-form').submit();">Déconnexion</a>
                            </div>
                        </li>
                        <form action="<?= Yii::getAlias('@administrator.disconnection') ?>" method="post" id="disconnection-form" style="display:none;">
                            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>" />
                        </form>
                    </ul>

                </div>

            </div>
        </nav>
        <!-- Navbar -->

        <!-- Sidebar -->
        <div class="sidebar-fixed position-fixed">
            <div class="text-center">
                <a class="logo-wrapper waves-effect" href="<?= Yii::getAlias("@administrator.home") ?>">
                    <img src="<?= Yii::getAlias("@web") . "/img/icon.png" ?>" class="img-fluid" alt="ENSP">
                </a>
            </div>


            <div class="side-wrapper">
                <div class="side-title">MAIN MENU</div>
                <div class="side-menu">
                    <!-- <div class="list-group list-group-flush"> -->
                    <a href="<?= Yii::getAlias("@administrator.home") ?>" class="list-group-item list-group-item-action <?= AdministratorSessionManager::isHome() ? 'active' : '' ?>  waves-effect">
                        <i class="fas fa-chart-pie mr-3"></i>Tableau de bord
                    </a>
                    <a href="<?= Yii::getAlias("@administrator.members") ?>" class="list-group-item list-group-item-action <?= AdministratorSessionManager::isMembers() ? 'active' : '' ?> waves-effect">
                        <i class="fas fa-users mr-3"></i>Membres</a>
                    <a href="<?= Yii::getAlias("@administrator.administrators") ?>" class="list-group-item list-group-item-action <?= AdministratorSessionManager::isAdministrators() ? 'active' : '' ?> waves-effect">
                        <i class="fas fa-robot mr-3"></i>Administrateurs</a>
                    <a href="<?= Yii::getAlias("@administrator.help_types") ?>" class="list-group-item list-group-item-action <?= AdministratorSessionManager::isHelps() ? 'active' : '' ?> waves-effect">
                        <i class="fas fa-hand-holding-heart mr-3"></i>Type d'aides</a>

                    <a href="<?= Yii::getAlias("@administrator.tontine_types") ?>" class="list-group-item list-group-item-action <?= AdministratorSessionManager::isTontine() ? 'active' : '' ?> waves-effect">
                        <i class="fas fa-coins mr-3"></i>Types de Tontines</a>
                </div>

            </div>
            <div class="side-wrapper">
                <div class="side-title">COMMUNICATION</div>
                <div class="side-menu">
                    <a href="<?= Yii::getAlias("@chat") ?>" class="list-group-item list-group-item-action <?= AdministratorSessionManager::isHeadChat() ? 'active' : '' ?> waves-effect">
                        <i class="fas fa-comments mr-3"></i>Chat
                    </a>
                </div>
            </div>

            <div class="side-wrapper">
                <div class="side-title">SETTING</div>
                <div class="side-menu">
                    <!--<div class="list-group list-group-flush">-->
                    <a href="<?= Yii::getAlias("@administrator.profile") ?>" class="list-group-item list-group-item-action <?= AdministratorSessionManager::isProfile() ? 'active' : '' ?> waves-effect">
                        <i class="fas fa-user mr-3"></i>Profil
                    </a>
                    <a href="<?= Yii::getAlias("@administrator.settings") ?>" class="list-group-item list-group-item-action <?= AdministratorSessionManager::isSettings() ? 'active' : '' ?> waves-effect">
                        <i class="fas fa-cogs mr-3"></i>Configurations
                    </a>
                    <?php if (\app\models\Exercise::findOne(['active' => true])) : ?>
                        <a href="<?= Yii::getAlias("@administrator.agape") ?>" class="list-group-item list-group-item-action <?= AdministratorSessionManager::isHeadAgape() ? 'active' : '' ?> waves-effect">
                            <i class="fas fa-heart mr-3"></i>Agape
                        </a>
                    <?php else : ?>
                        <a href="#" class="list-group-item list-group-item-action disabled waves-effect">
                            <i class="fas fa-heart mr-3"></i>Agape <span style="font-size: x-small; color: red">(Veuillez creer un exercice)</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="modal  fade" id="btn-disconnect" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">

                            <p class="text-center">Êtes-vous sûr(e) de vouloir vous déconnecter?
                            </p>

                            <div class="form-group text-center">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Non</button>
                                <button class="btn btn-primary" onclick="$('#disconnection-form').submit()">oui</button>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Sidebar -->

    </header>
    <!--Main Navigation-->

    <!--Main layout-->
    <main class="pt-5 mx-lg-5">
        <?= $content ?>
    </main>
    <!--Main layout-->

    <!--Footer
    <footer class="page-footer text-center font-small primary-color-dark darken-2 mt-4 wow fadeIn ">

        <div class="footer-copyright py-3">
            2018 Copyright: Groupe DEC
        </div>

    </footer>
    -->

    <!--/.Footer-->


    <?php include Yii::getAlias("@app") . "/includes/scripts.php"; ?>

    <!-- Initializations -->
    <script type="text/javascript">
        // Animations initialization
        new WOW().init();
    </script>




    <?php if (isset($this->blocks['script'])) : ?>
        <?= $this->blocks['script'] ?>
    <?php endif; ?>

    <?php $this->endBody(); ?>
</body>

</html>
<?php $this->endPage(); ?>