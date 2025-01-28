<?php
use app\managers\MemberSessionManager;
use yii\helpers\Html;
use app\models\FinancialAid;

$this->title = "Mutuelle - ENSPY";
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <?php include Yii::getAlias("@app") . "/includes/links.php"; ?>

        <link href="<?= Yii::getAlias("@web").'/css/member.css' ?>" rel="stylesheet">

        <title>
            <?php if (isset($this->blocks['title'])): ?>
                <?= $this->blocks['title'] ?>
            <?php else: ?>
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

            .side-wrapper {
                display: flex;
                flex-direction: column;
                height: 100%;
            }

            .side-menu {
                flex: 1;
            }

            .contact_admin {
                padding: 15px;
                text-align: center;
                margin-top: 150px;
            }
        </style>

        <?php if (isset($this->blocks['style'])): ?>
            <?= $this->blocks['style'] ?>
        <?php endif; ?>
    </head>
    <body  class="grey lighten-3">
    <?php $this->beginBody() ?>

    <!--Main Navigation-->
    <header>
        <!-- Navbar -->
        <nav class="navbar fixed-top navbar-expand-lg navbar-light  scrolling-navbar blue-gradient">
            <div class="container-fluid">

                <!-- Brand -->
                <a class="navbar-brand waves-effect" href="<?= Yii::getAlias("@member.home")?>">
                    <img src="/img/icon.png" alt="ENSP" style="width: 40px; height: 40px;" class="d-md-none">
                </a>

                <!-- Collapse -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Links -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- Left -->
                    <ul class="navbar-nav mr-auto navbar-custom">
                        <li class="nav-item nav-separator">
                            <a class="nav-link waves-effect <?= MemberSessionManager::isAccueil()?'blue-text':'' ?>" href="<?= Yii::getAlias("@member.home")?>">Accueil</a>
                        </li>
                        <li class="nav-item nav-separator">
                            <a href="<?= Yii::getAlias("@member.epargnes") ?>" class="nav-link waves-effect <?= MemberSessionManager::isEpargnes()?'blue-text':''?>" >Mes épargnes</a>
                        </li>
                        <!-- Menu dettes temporairement désactivé
                        <li class="nav-item nav-separator">
                            <a href="<?= Yii::getAlias("@member.dettes") ?>" class="nav-link waves-effect <?= MemberSessionManager::isDettes()?'blue-text':''?>" >
                                <i class="fas fa-hand-holding-usd mr-1"></i>Mes dettes
                            </a>
                        </li>
                        -->
                        <li class="nav-item nav-separator">
                            <a href="<?= Yii::getAlias("@member.emprunts") ?>" class="nav-link waves-effect  <?= MemberSessionManager::isEmprunts()?'blue-text':''?>" >Mes emprunts</a>
                        </li>
                        <li class="nav-item nav-separator">
                            <a href="<?= Yii::getAlias("@member.contributions") ?>" class="nav-link waves-effect  <?= MemberSessionManager::isContributions()?'blue-text':''?>" >Mes contributions</a>
                        </li>

                        <li class="nav-item nav-separator">
                            <a href="<?= yii\helpers\Url::to(['/member/dette']) ?>" class="nav-link waves-effect <?= MemberSessionManager::isDette() ? 'blue-text' : '' ?>">Ma Dette</a>
                        </li>
                        <li class="nav-item nav-separator">
                            <a class="nav-link waves-effect  <?= MemberSessionManager::isAides()?'blue-text':''?>" href="<?= Yii::getAlias("@member.helps") ?>">Aides</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link waves-effect  <?= MemberSessionManager::isPay()?'blue-text':''?>" href="<?= Yii::getAlias("@member.payer")?>" >Payer</a>
                        </li>
                    </ul>
                </div>
                    </ul>

                    <!-- Right -->
                    <ul class="navbar-nav nav-flex-icons">
                        <li class="nav-item mr-auto">
                            <a class="nav-link waves-effect" href="<?= Yii::getAlias("@member.profil") ?>">
                                <img src="<?= \app\managers\FileManager::loadAvatar($this->params['user']) ?>" class="profile-icon" alt="<?= $this->params['member']->username ?>">
                                <span><?= $this->params['member']->username ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="<?= Yii::getAlias('@member.disconnection')?>" method="post" id="disconnection-form">
                                <input type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>"/>
                            </form>
                            <a class="nav-link waves-effect" href="#" onclick="event.preventDefault(); document.getElementById('disconnection-form')>
                                <i class="fas fa-sign-out-alt" data-toggle="modal" data-target="#btn-disconnect"></i> Déconnexion
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </nav>
        <!-- Navbar -->

        <!-- Sidebar -->
        <div class="sidebar-fixed position-fixed">
            <div class="text-center">
                <a class="logo-wrapper waves-effect" href="<?= Yii::getAlias("@member.home") ?>">
                    <img src="<?= Yii::getAlias("@web")."/img/icon.png"?>" class="img-fluid" alt="ENSP">
                </a>
            </div>


            <div class="side-wrapper">
                <div class="side-menu">
                    <a href="<?= Yii::getAlias("@member.home") ?>" class="list-group-item list-group-item-action <?= MemberSessionManager::isHome()?'active':''?> waves-effect">
                        <i class="fas fa-chart-pie mr-3"></i>Tableau de bord
                    </a>
                    <a href="<?= Yii::getAlias("@member.members") ?>" class="list-group-item list-group-item-action <?= MemberSessionManager::isMembers()?'active':''?> waves-effect">
                        <i class="fas fa-users mr-3"></i>Membres
                    </a>
                    <a href="/member/payments" class="list-group-item list-group-item-action <?= MemberSessionManager::isPayments()?'active':''?> waves-effect">
                        <i class="fas fa-money-bill-wave mr-3"></i>Mes Paiements
                    </a>
                    <a href="<?= Yii::getAlias("@member.typesaide") ?>" class="list-group-item list-group-item-action <?= MemberSessionManager::isHelps()?'active':''?> waves-effect">
                        <i class="fas fa-hand-holding-heart mr-3"></i>Type d'aides
                    </a>

                    <a href="<?= Yii::getAlias("@member.tontine_types") ?>" class="list-group-item list-group-item-action <?= MemberSessionManager::isTontine()?'active':''?> waves-effect">
                            <i class="fas fa-coins mr-3"></i>Les Tontines
                    </a>

                    
                    <a href="<?= Yii::getAlias("@member.exercises") ?>" class="list-group-item list-group-item-action <?= MemberSessionManager::isExercices()?'active':''?> waves-effect">
                        <i class="fas far fa-calendar mr-3"></i>Détails exercices
                    </a>
                    <a href="<?= Yii::getAlias("@member.sessions") ?>" class="list-group-item list-group-item-action <?= MemberSessionManager::isSessions()?'active':''?> waves-effect">
                        <i class="fas fa-table mr-3"></i>Sessions
                    </a>
                    <a href="<?= Yii::getAlias("@web").'/chat' ?>" class="list-group-item list-group-item-action <?= MemberSessionManager::isChat()?'active':''?> waves-effect">
                        <i class="fas fa-comments mr-3"></i>Chat
                    </a>
                    <form action="<?= Yii::getAlias('@member.disconnection')?>" method="post" id="disconnection-form">
                        <input type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>"/>
                    </form>
                    <a class="nav-link waves-effect" href="#" data-toggle="modal" data-target="#btn-disconnect" onclick="event.preventDefault(); document.getElementById('disconnection-form')>
                        <i class="fas fa-sign-out-alt" ></i> Déconnexion</a>
                    <!-- Add the Contact Admin button here -->
                    <div class="contact_admin" style="background-color: #f5f5f5;">
                        <!-- <a href="mailto:root@root.root" class="btn btn-primary btn-sm">Contacter Admin</a> -->
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=root@root.root" target="_blank" class="list-group-item list-group-item-action waves-effect">
                            <i class="fas fa-envelope mr-3"></i>Contacter Admin
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal  fade" id="btn-disconnect" tabindex="-1" role="dialog"
                 aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">

                            <p class="text-center">Êtes-vous sûr(e) de vouloir vous déconnecter?
                            </p>

                            <div class="form-group text-center">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Non</button>
                                <button class="btn btn-success" onclick="$('#disconnection-form').submit()">oui</button>

                            </div>
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

    <?php include Yii::getAlias("@app") . "/includes/scripts.php"; ?>

    <!-- Initializations -->
    <script type="text/javascript">
        // Animations initialization
        new WOW().init();

    </script>




    <?php if (isset($this->blocks['script'])): ?>
        <?= $this->blocks['script'] ?>
    <?php endif; ?>

    <?php $this->endBody(); ?>
    </body>

    </html>
<?php $this->endPage(); ?>