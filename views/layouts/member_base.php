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
            :root {
                --sidebar-width: 250px;
                --header-height: 60px;
                --primary: #4e73df;
                --primary-dark: #224abe;
                --secondary: #858796;
                --light: #f8f9fc;
                --white: #fff;
            }

            body {
                background: #f5f7fa;
            }

            /* Navbar Styles */
            .navbar {
                background: var(--white);
                box-shadow: 0 2px 15px rgba(0,0,0,0.05);
                padding: 0 1.5rem;
                height: var(--header-height);
            }

            .navbar-brand {
                display: flex;
                align-items: center;
                padding: 0.5rem 0;
            }

            .nav-link {
                color: var(--secondary);
                padding: 0.75rem 1.25rem;
                border-radius: 50px;
                font-weight: 600;
                transition: all 0.3s ease;
                margin: 0 0.3rem;
                font-size: 1rem;
            }

            .nav-link:hover {
                color: var(--primary);
                background: rgba(78, 115, 223, 0.1);
            }

            .nav-link.active {
                color: var(--white);
                background: linear-gradient(45deg, var(--primary) 0%, var(--primary-dark) 100%);
            }

            .nav-link i {
                font-size: 1.2rem;
                margin-right: 0.75rem;
                width: 25px;
                text-align: center;
            }

            .dropdown-menu {
                border: none;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                border-radius: 15px;
                padding: 0.5rem;
                min-width: 200px;
            }

            .dropdown-item {
                padding: 0.75rem 1rem;
                color: var(--secondary);
                font-weight: 500;
                border-radius: 10px;
                display: flex;
                align-items: center;
                transition: all 0.3s ease;
            }

            .dropdown-item:hover {
                color: var(--primary);
                background: rgba(78, 115, 223, 0.1);
            }

            .dropdown-item i {
                margin-right: 0.75rem;
                font-size: 1rem;
                color: var(--primary);
            }

            /* Sidebar Styles */
            .admin-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: var(--sidebar-width);
                height: 100vh;
                background: linear-gradient(45deg, var(--primary) 0%, var(--primary-dark) 100%);
                color: var(--white);
                z-index: 1040;
                padding: 1rem;
                overflow-y: auto;
            }

            .admin-sidebar .logo-wrapper {
                padding: 1.5rem 1rem;
                text-align: center;
                margin-bottom: 1rem;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }

            .admin-sidebar .logo-title {
                color: white;
                font-size: 1.8rem;
                font-weight: 800;
                letter-spacing: 2px;
                text-transform: uppercase;
                text-decoration: none;
            }

            .admin-sidebar .menu-section {
                margin-bottom: 2rem;
            }

            .admin-sidebar .menu-title {
                color: rgba(255,255,255,0.6);
                font-size: 0.8rem;
                text-transform: uppercase;
                letter-spacing: 1px;
                margin-bottom: 0.5rem;
                padding: 0 1rem;
            }

            .admin-sidebar .menu-item {
                display: flex;
                align-items: center;
                padding: 0.75rem 1rem;
                color: rgba(255,255,255,0.8);
                text-decoration: none;
                border-radius: 10px;
                transition: all 0.3s ease;
                margin-bottom: 0.25rem;
                font-weight: 500;
                font-size: 1rem;
            }

            .admin-sidebar .menu-item:hover {
                color: var(--white);
                background: rgba(255,255,255,0.1);
                transform: translateX(5px);
            }

            .admin-sidebar .menu-item.active {
                color: var(--white);
                background: rgba(255,255,255,0.2);
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            }

            .admin-sidebar .menu-item i {
                font-size: 1.2rem;
                margin-right: 1rem;
                width: 20px;
                text-align: center;
            }

            /* Main Content Adjustment */
            .main-content {
                margin-left: var(--sidebar-width);
                padding: 2rem;
                padding-top: calc(2rem + var(--header-height));
            }

            /* Mobile Responsive */
            @media (max-width: 992px) {
                .admin-sidebar {
                    transform: translateX(-100%);
                    transition: transform 0.3s ease;
                }

                .admin-sidebar.show {
                    transform: translateX(0);
                }

                .main-content {
                    margin-left: 0;
                }
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
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="mobile-toggle d-lg-none" onclick="document.querySelector('.admin-sidebar').classList.toggle('show')">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand waves-effect" href="<?= Yii::getAlias("@member.home") ?>">
                    <img src="/img/icon.png" alt="ENSP" style="width: 40px; height: 40px; margin-right: 10px;">
            
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link <?= MemberSessionManager::isHome() ? 'active' : '' ?>" href="<?= Yii::getAlias('@member.home') ?>">
                                <i class="fas fa-home"></i> Accueil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= MemberSessionManager::isEpargnes() ? 'active' : '' ?>" href="<?= Yii::getAlias('@member.epargnes') ?>">
                                <i class="fas fa-piggy-bank"></i> Mes épargnes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= MemberSessionManager::isEmprunts() ? 'active' : '' ?>" href="<?= Yii::getAlias('@member.emprunts') ?>">
                                <i class="fas fa-hand-holding-usd"></i> Mes emprunts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= MemberSessionManager::isContributions() ? 'active' : '' ?>" href="<?= Yii::getAlias('@member.contributions') ?>">
                                <i class="fas fa-donate"></i> Mes contributions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= MemberSessionManager::isMembers() ? 'active' : '' ?>" href="<?= Yii::getAlias('@member.members') ?>">
                                <i class="fas fa-users"></i> Membres
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= MemberSessionManager::isAides() ? 'active' : '' ?>" href="<?= Yii::getAlias('@member.helps') ?>">
                                <i class="fas fa-hands-helping"></i> Aides
                            </a>
                        </li>
                    </ul>

                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user"></i> <?= $this->params['member']->user()->name . ' ' . $this->params['member']->user()->first_name ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?= Yii::getAlias('@member.profil') ?>">
                                    <i class="fas fa-user-cog"></i> Mon Profil
                                </a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#btn-disconnect">
                                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <style>
            :root {
                --primary: #4e73df;
                --primary-dark: #2e59d9;
                --white: #fff;
            }

                .navbar {
                    background: var(--white);
                    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
                    padding: 0 1.5rem;
                    height: var(--header-height);
                    padding-left: calc(var(--sidebar-width) + 1rem);
                }

                @media (max-width: 992px) {
                    .navbar {
                        padding-left: 1rem; /* Réinitialiser le padding sur mobile */
                    }
                    
                    .admin-sidebar {
                        transform: translateX(-100%);
                        transition: transform 0.3s ease;
                    }

                    .admin-sidebar.show {
                        transform: translateX(0);
                    }
                    
                    .mobile-toggle {
                        display: block !important;
                    }
                }
                
                .mobile-toggle {
                    display: none;
                    background: none;
                    border: none;
                    color: var(--secondary);
                    font-size: 1.5rem;
                    padding: 0.5rem;
                    margin-right: 0.5rem;
                }

                .navbar-brand {
                    display: flex;
                    align-items: center;
                    padding: 0.5rem 0;
                    color: #2c3e50;
                    font-weight: bold;
                    gap: 10px;
                }

            .navbar-brand img {
                height: 40px;
                width: 40px;
            }

            .nav-link {
                color: #2c3e50 !important;
                margin: 0 0.5rem;
                padding: 0.5rem 1rem;
                border-radius: 4px;
                transition: all 0.3s ease;
            }

            .nav-link:hover {
                color: var(--primary) !important;
            }

            .nav-link.active {
                background-color: var(--primary);
                color: var(--white) !important;
            }

            .nav-link i {
                margin-right: 0.5rem;
            }

            .dropdown-menu {
                background-color: var(--white);
            }

            .dropdown-item {
                color: #2c3e50 !important;
            }

            .dropdown-item:hover {
                background-color: rgba(78, 115, 223, 0.1);
            }

            .navbar-toggler {
                border: none;
                padding: 0.5rem;
            }

            .navbar-toggler-icon {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(44, 62, 80, 0.5)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            }
        </style>
        <!-- Navbar -->

        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="logo-wrapper">
                <a href="/member/home" class="logo-title">
                    MUTUELLE
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-title">Navigation</div>
                <a href="/member/types-aide" class="menu-item <?= MemberSessionManager::isTypesAide()?'active':''?>">
                    <i class="fas fa-hand-holding-heart"></i>Type d'aides
                </a>
                <a href="/member/tontines" class="menu-item <?= MemberSessionManager::isTontine()?'active':''?>">
                    <i class="fas fa-coins"></i>Les Tontines
                </a>
                <a href="/member/exercises" class="menu-item <?= MemberSessionManager::isExercices()?'active':''?>">
                    <i class="fas fa-calendar"></i>Détails exercices
                </a>
                <a href="/member/sessions" class="menu-item <?= MemberSessionManager::isSessions()?'active':''?>">
                    <i class="fas fa-table"></i>Sessions
                </a>
                <a href="/chat" class="menu-item <?= MemberSessionManager::isChat()?'active':''?>">
                    <i class="fas fa-comments"></i>Chat
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-title">Gestion Financière</div>
                <a href="/member/pay" class="menu-item <?= MemberSessionManager::isPay()?'active':''?>">
                    <i class="fas fa-money-bill-wave"></i>Payer
                </a>
                <a href="/member/dette" class="menu-item <?= MemberSessionManager::isDette()?'active':''?>">
                    <i class="fas fa-wallet"></i>Ma dette
                </a>
                <a href="/member/payments" class="menu-item <?= MemberSessionManager::isPayments()?'active':''?>">
                    <i class="fas fa-credit-card"></i>Mes paiements
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-title">Compte</div>
                <a class="menu-item" href="#" data-toggle="modal" data-target="#btn-disconnect" data-bs-toggle="modal" data-bs-target="#btn-disconnect" id="sidebar-disconnect-btn" style="z-index:3000; position:relative;">
                    <i class="fas fa-sign-out-alt"></i>Déconnexion
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-title">Contact</div>
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=root@root.root" target="_blank" class="menu-item">
                    <i class="fas fa-envelope"></i>Contacter Admin
                </a>
            </div>

        </div>

        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show white-block" role="alert" style="margin:20px 40px 0 40px;">
                <?= Yii::$app->session->getFlash('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        <?php endif; ?>
        <!-- Main Content -->
        <div class="main-content">
            <?= $content ?>
        </div>

        <?php include Yii::getAlias("@app") . "/includes/scripts.php"; ?>
        <style>
        /* Ne surcharge pas le z-index du modal, laisse Bootstrap gérer */
        .admin-sidebar {
            z-index: 3000 !important;
        }
        #sidebar-disconnect-btn {
            z-index: 3300 !important;
            position: relative;
        }
        </style>
        <script>
        // Fix JS pour forcer l'ouverture du modal et la visibilité
        document.addEventListener('DOMContentLoaded', function () {
            var btn = document.getElementById('sidebar-disconnect-btn');
            if(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    var modal = document.getElementById('btn-disconnect');
                    // Scroll en haut pour éviter qu’un overflow cache le modal
                    window.scrollTo({top:0, behavior:'smooth'});
                    setTimeout(function() {
                        // Supprime tout backdrop en trop
                        var backdrops = document.querySelectorAll('.modal-backdrop');
                        if (backdrops.length > 1) {
                            for (var i = 1; i < backdrops.length; i++) backdrops[i].remove();
                        }
                    }, 500);
                    if (window.bootstrap && bootstrap.Modal) {
                        var modalInstance = bootstrap.Modal.getOrCreateInstance(modal);
                        modalInstance.show();
                    } else if (window.$ && $(modal).modal) {
                        $(modal).modal('show');
                    }
                });
            }
        });
        </script>

        <!-- Initializations -->
        <script type="text/javascript">
            // Animations initialization
            new WOW().init();
        </script>

        <?php if (isset($this->blocks['script'])): ?>
            <?= $this->blocks['script'] ?>
        <?php endif; ?>

            <!-- Modal et formulaire de déconnexion placés juste avant la fin du body -->
    <form action="<?= Yii::getAlias('@web') . '/member/deconnexion' ?>" method="post" id="disconnection-form">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>" />
    </form>
    <div class="modal fade" id="btn-disconnect" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Confirmation de déconnexion</h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-center">Êtes-vous sûr(e) de vouloir vous déconnecter?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Non</button>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('disconnection-form').submit()">Oui</button>
                </div>
            </div>
        </div>
    </div>
<?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage(); ?>