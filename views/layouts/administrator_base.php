<?php

use app\managers\AdministratorSessionManager;
use yii\helpers\Html;
$this->title = "Mutuelle - ENSPY";
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <?php $this->head() ?>
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
        .admin-navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            height: var(--header-height);
            background: var(--white);
            padding: 0 1.5rem;
            z-index: 1030;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .admin-navbar .navbar-brand {
            display: flex;
            align-items: center;
            padding: 0.5rem 0;
        }

        .admin-navbar .navbar-brand img {
            height: 50px;
            width: auto;
            margin-right: 1rem;
        }

        .admin-navbar .nav-link {
            color: var(--secondary);
            padding: 0.75rem 1.25rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 0 0.3rem;
            font-size: 1rem;
            position: relative;
        }

        .admin-navbar .nav-link:hover {
            color: var(--primary);
            background: rgba(78, 115, 223, 0.1);
        }

        .admin-navbar .nav-link.active {
            color: var(--white);
            background: linear-gradient(45deg, var(--primary) 0%, var(--primary-dark) 100%);
        }

        .admin-navbar .nav-link i {
            font-size: 1.2rem;
            margin-right: 0.75rem;
            width: 25px;
            text-align: center;
        }

        .admin-navbar .profile-menu {
            display: flex;
            align-items: center;
            color: var(--secondary);
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .admin-navbar .profile-menu:hover {
            color: var(--primary);
            background: rgba(78, 115, 223, 0.1);
        }

        .admin-navbar .profile-menu img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--primary);
            margin-right: 0.75rem;
            object-fit: cover;
        }

        .admin-navbar .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 15px;
            padding: 0.5rem;
            min-width: 200px;
        }

        .admin-navbar .dropdown-item {
            padding: 0.75rem 1rem;
            color: var(--secondary);
            font-weight: 500;
            border-radius: 10px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .admin-navbar .dropdown-item:hover {
            color: var(--primary);
            background: rgba(78, 115, 223, 0.1);
        }

        .admin-navbar .dropdown-item i {
            margin-right: 0.75rem;
            font-size: 1rem;
            color: var(--primary);
        }

        /* Sidebar Styles */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            z-index: 1040;
            padding-top: 1rem;
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
            display: block;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            font-family: 'Inter', sans-serif;
        }

        .admin-sidebar .logo-title:hover {
            color: white;
            text-decoration: none;
        }

        .admin-sidebar .menu-section {
            padding: 0.5rem 1rem;
        }

        .admin-sidebar .menu-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: rgba(255,255,255,0.8);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 1.5rem 0 0.75rem 1rem;
        }

        .admin-sidebar .menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            border-radius: 50px;
            margin: 0.3rem 0;
            transition: all 0.3s ease;
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
            margin-right: 0.75rem;
            width: 25px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: calc(var(--header-height) + 2rem) 2rem 2rem 2rem;
            min-height: 100vh;
        }

        /* Scrollbar Styles */
        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }

        /* Modal overrides to ensure modals appear above sidebar/navbar */
        .modal-backdrop,
        .modal-backdrop.show { z-index: 1050 !important; }
        .modal.fade .modal-dialog { transform: none !important; }
        .modal.show { z-index: 1060 !important; }
        .modal-dialog { z-index: 1061 !important; }

        /* Prevent white-block hover moves when a modal is open */
        body.modal-open .white-block:hover {
            transform: none !important;
            box-shadow: none !important;
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

            .admin-navbar {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
                background: none;
                border: none;
                color: var(--secondary);
                font-size: 1.5rem;
                padding: 0.5rem;
                margin-right: 0.5rem;
            }

            .mobile-toggle:hover {
                color: var(--primary);
            }

            .navbar-nav {
                flex-wrap: nowrap;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                background: white;
                padding: 0.5rem;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            
            .admin-navbar .nav-link {
                white-space: nowrap;
            }
        }

        /* --- Loading Overlay Styles --- */
        html {
            scrollbar-gutter: stable;
        }
        
        body.loading-active {
            overflow: hidden;
        }
        
        #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
            color: white;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            overflow: hidden;
        }

        .loading-content {
            text-align: center;
            user-select: none;
        }

        .loading-text {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: -0.025em;
            background: linear-gradient(to right, #ffffff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .loading-dots {
            font-size: 2.5rem;
            margin-left: 0.5rem;
        }

        .dot {
            display: inline-block;
            animation: glow 1.5s infinite;
            opacity: 0.2;
        }

        .dot:nth-child(2) { animation-delay: 0.2s; }
        .dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes glow {
            0%, 100% {
                opacity: 0.2;
                transform: scale(1);
            }
            50% {
                opacity: 1;
                transform: scale(1.3);
                color: #3b82f6;
                text-shadow: 0 0 20px rgba(59, 130, 246, 0.6);
            }
        }
    </style>


    <?php if (isset($this->blocks['style'])) : ?>
        <?= $this->blocks['style'] ?>
    <?php endif; ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <!-- Loading Overlay -->
    <div id="loading-overlay">
        <div class="loading-content">
            <span class="loading-text">Mutuelle Web</span>
            <span class="loading-dots">
                <span class="dot">.</span>
                <span class="dot">.</span>
                <span class="dot">.</span>
            </span>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="logo-wrapper">
            <a href="<?= Yii::getAlias("@administrator.home") ?>" class="logo-title">
                MUTUELLE
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-title">Menu Principal</div>
            <a href="<?= Yii::getAlias("@administrator.home") ?>" class="menu-item <?= AdministratorSessionManager::isHome() ? 'active' : '' ?>">
                <i class="fas fa-chart-pie"></i>Tableau de bord
            </a>
            <a href="<?= Yii::getAlias("@administrator.members") ?>" class="menu-item <?= AdministratorSessionManager::isMembers() ? 'active' : '' ?>">
                <i class="fas fa-users"></i>Membres
            </a>
            <a href="<?= Yii::getAlias("@administrator.administrators") ?>" class="menu-item <?= AdministratorSessionManager::isAdministrators() ? 'active' : '' ?>">
                <i class="fas fa-robot"></i>Administrateurs
            </a>
            <a href="<?= Yii::getAlias("@administrator.help_types") ?>" class="menu-item <?= AdministratorSessionManager::isHelps() ? 'active' : '' ?>">
                <i class="fas fa-hand-holding-heart"></i>Type d'aides
            </a>
            <a href="<?= Yii::getAlias("@administrator.agape") ?>" class="menu-item <?= AdministratorSessionManager::isAgape() ? 'active' : '' ?>">
                <i class="fas fa-hand-holding-heart"></i>Agape
            </a>
            <a href="<?= Yii::getAlias("@administrator.tontine_types") ?>" class="menu-item <?= AdministratorSessionManager::isTontine() ? 'active' : '' ?>">
                <i class="fas fa-coins"></i>Types de Tontines
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-title">Communication</div>
            <a href="<?= Yii::getAlias("@chat") ?>" class="menu-item <?= AdministratorSessionManager::isChat() ? 'active' : '' ?>">
                <i class="fas fa-comments"></i>Chat
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-title">Paramètres</div>
            <a href="<?= Yii::getAlias("@administrator.profile") ?>" class="menu-item <?= AdministratorSessionManager::isProfile() ? 'active' : '' ?>">
                <i class="fas fa-user"></i>Profil
            </a>
            <a href="<?= Yii::getAlias("@administrator.settings") ?>" class="menu-item <?= AdministratorSessionManager::isSettings() ? 'active' : '' ?>">
                <i class="fas fa-cogs"></i>Configurations
            </a>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="admin-navbar">
        <div class="d-flex align-items-center justify-content-between w-100">
            <div class="d-flex align-items-center">
                <button class="mobile-toggle d-lg-none" onclick="document.querySelector('.admin-sidebar').classList.toggle('show')">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand d-none d-lg-flex" href="<?= Yii::getAlias("@administrator.home") ?>">
                    <img src="<?= Yii::getAlias("@web") . "/img/icon.png" ?>" alt="ENSP">
                    <!-- <span class="font-weight-bold">Tableau de bord</span> -->
                </a>
                <ul class="navbar-nav d-flex flex-row">
                    <li class="nav-item">
                        <a class="nav-link <?= AdministratorSessionManager::isHeadHome() ? 'active' : '' ?>" href="<?= Yii::getAlias("@administrator.home") ?>">
                            <i class="fas fa-home"></i>Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= AdministratorSessionManager::isHeadExercise() ? 'active' : '' ?>" href="<?= Yii::getAlias("@administrator.exercises") ?>">
                            <i class="fas fa-dumbbell"></i>Exercices
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= AdministratorSessionManager::isHeadSession() ? 'active' : '' ?>" href="<?= Yii::getAlias("@administrator.sessions") ?>">
                            <i class="fas fa-calendar-alt"></i>Sessions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= AdministratorSessionManager::isHeadSaving() ? 'active' : '' ?>" href="<?= Yii::getAlias("@administrator.savings") ?>">
                            <i class="fas fa-piggy-bank"></i>Epargnes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= AdministratorSessionManager::isHeadBorrowing() ? 'active' : '' ?>" href="<?= Yii::getAlias("@administrator.borrowings") ?>">
                            <i class="fas fa-hand-holding-usd"></i>Emprunts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= AdministratorSessionManager::isHeadRefund() ? 'active' : '' ?>" href="<?= Yii::getAlias("@administrator.refunds") ?>">
                            <i class="fas fa-undo-alt"></i>Remboursements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= AdministratorSessionManager::isHeadTontine() ? 'active' : '' ?>" href="<?= Yii::getAlias("@administrator.tontines") ?>">
                            <i class="fas fa-money-bill-wave"></i>Tontine
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= AdministratorSessionManager::isHeadExerciseDebt() ? 'active' : '' ?>" href="<?= Yii::getAlias("@administrator.exercise_debts") ?>">
                            <i class="fas fa-file-invoice-dollar"></i>Dettes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= AdministratorSessionManager::isHeadHelp() ? 'active' : '' ?>" href="<?= Yii::getAlias("@administrator.helps") ?>">
                            <i class="fas fa-hands-helping"></i>Aides
                        </a>
                    </li>
                </ul>
            </div>

            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a class="profile-menu" href="#" id="profileDropdown" role="button" data-toggle="dropdown">
                        <img src="<?= \app\managers\FileManager::loadAvatar($this->params['user']) ?>" alt="<?= $this->params['administrator']->username ?>">
                        <span class="d-none d-md-inline"><?= $this->params['administrator']->username ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="<?= Yii::getAlias("@administrator.profile") ?>">
                            <i class="fas fa-user"></i>Profil
                        </a>
                        <a class="dropdown-item" href="<?= Yii::getAlias("@administrator.settings") ?>">
                            <i class="fas fa-cogs"></i>Configurations
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('disconnection-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>Déconnexion
                        </a>
                    </div>
                </div>
                <form action="<?= Yii::getAlias('@administrator.disconnection') ?>" method="post" id="disconnection-form" style="display:none;">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>" />
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <!-- Main Content -->
    <div class="main-content">
        <?= \app\widgets\Alert::widget() ?>
        <?= $content ?>
    </div>

    <?php include Yii::getAlias("@app") . "/includes/scripts.php"; ?>
    <?php if (isset($this->blocks['script'])) : ?>
        <?= $this->blocks['script'] ?>
    <?php endif; ?>
    <script>
        // Auto-wrap tables for responsiveness
        $(document).ready(function() {
            $('table.table').each(function() {
                if (!$(this).parent().hasClass('table-responsive')) {
                    $(this).wrap('<div class="table-responsive"></div>');
                }
            });
        });
    </script>
    <!-- Append modals to body to fix z-index issues -->
    <!-- <script>
    $(document).on('show.bs.modal', '.modal', function() {
        // $(this).appendTo('body'); // Disabled to prevent layout shifting
    });
    </script> -->
    <script>
    $(document).ready(function() {
        // Hide overlay on load (just in case)
        $('#loading-overlay').fadeOut(100);
        $('body').removeClass('loading-active');

        // Function to show loading overlay
        function showLoadingOverlay() {
            $('body').addClass('loading-active');
            $('#loading-overlay').css('display', 'flex').hide().fadeIn(300);
        }

        // Show overlay on form submission
        $('form').on('submit', function() {
            if (!$(this).hasClass('no-loader')) {
                // If the form has native validation and it's invalid, don't show overlay
                if (this.checkValidity && !this.checkValidity()) {
                    return;
                }
                showLoadingOverlay();
            }
        });

        // Show overlay on link clicks (excluding relative hashes, modals, and target blank)
        $('a').on('click', function() {
            var href = $(this).attr('href');
            var target = $(this).attr('target');
            
            if (href && href !== '#' && !href.startsWith('javascript:') && !href.startsWith('#') && 
                !$(this).data('toggle') && !$(this).data('dismiss') && target !== '_blank') {
                showLoadingOverlay();
            }
        });

        // Hide overlay if the page is shown from cache (back button)
        window.onpageshow = function(event) {
            if (event.persisted) {
                $('#loading-overlay').fadeOut(200);
                $('body').removeClass('loading-active');
            }
        };

        // Safety: Hide overlay after 15 seconds (something went wrong)
        setTimeout(function() {
            if ($('#loading-overlay').is(':visible')) {
                $('#loading-overlay').fadeOut(500);
                $('body').removeClass('loading-active');
            }
        }, 15000);
    });
    </script>
    <?php $this->endBody(); ?>

</body>

</html>
<?php $this->endPage(); ?>