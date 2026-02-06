<?php

use app\assets\AdminAsset;
use app\managers\AdministratorSessionManager;
use yii\helpers\Html;

AdminAsset::register($this);
$this->title = "Mutuelle - ENSPY";
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

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