<?php

use yii\helpers\Html;

$this->title = "Mutuelle - ENSPY"
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <?php include Yii::getAlias("@app") . "/includes/links.php"; ?>

        <title>
            <?php if (isset($this->blocks['title'])): ?>
                <?= $this->blocks['title'] ?>
            <?php else: ?>
                <?= Html::encode($this->title) ?>
            <?php endif; ?>
        </title>

        <!-- Add modern fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?= Yii::getAlias("@web") . "/css/guest.css" ?>">

        <?php if (isset($this->blocks['style'])): ?>
            <?= $this->blocks['title'] ?>
        <?php endif; ?>

        <style>
            :root {
                --primary-color: #2a5298;
                --primary-dark: #1e3c72;
                --text-light: #ffffff;
                --bg-light: #f8f9fa;
            }

            body {
                font-family: 'Poppins', sans-serif;
            }

            .modern-navbar {
                background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
                padding: 1rem 2rem;
                box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            }

            .modern-navbar .navbar-brand {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .modern-navbar .navbar-brand img {
                height: 40px;
                width: auto;
                transition: transform 0.3s ease;
            }

            .modern-navbar .navbar-brand:hover img {
                transform: scale(1.05);
            }

            .modern-navbar .nav-link {
                color: var(--text-light) !important;
                font-weight: 500;
                padding: 0.5rem 1rem;
                margin: 0 0.2rem;
                border-radius: 5px;
                transition: all 0.3s ease;
            }

            .modern-navbar .nav-link:hover,
            .modern-navbar .nav-item.active .nav-link {
                background: rgba(255,255,255,0.1);
                transform: translateY(-1px);
            }

            .modern-navbar .dropdown-toggle {
                background: transparent;
                border: 1px solid rgba(255,255,255,0.2);
                color: var(--text-light);
                padding: 0.5rem 1rem;
                border-radius: 5px;
                transition: all 0.3s ease;
            }

            .modern-navbar .dropdown-toggle:hover {
                background: rgba(255,255,255,0.1);
                border-color: rgba(255,255,255,0.3);
            }

            .modern-navbar .dropdown-menu {
                border: none;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                border-radius: 8px;
                margin-top: 0.5rem;
            }

            .modern-navbar .dropdown-item {
                padding: 0.7rem 1.2rem;
                font-weight: 500;
                transition: all 0.2s ease;
            }

            .modern-navbar .dropdown-item:hover {
                background: var(--bg-light);
                color: var(--primary-color);
            }

            @media (max-width: 768px) {
                .modern-navbar {
                    padding: 0.8rem 1rem;
                }
                
                .modern-navbar .navbar-collapse {
                    background: white;
                    margin: 1rem -1rem -0.8rem;
                    padding: 1rem;
                    border-radius: 8px;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                }

                .modern-navbar .nav-link {
                    color: #333 !important;
                }

                .modern-navbar .nav-link:hover,
                .modern-navbar .nav-item.active .nav-link {
                    background: var(--bg-light);
                    color: var(--primary-color) !important;
                }
            }

            /* --- Loading Overlay Styles --- */
            #loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
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

    <nav class="navbar navbar-expand-lg modern-navbar">
        <a href="<?= Yii::getAlias("@guest.welcome") ?>" class="navbar-brand">
            <img src="<?= Yii::getAlias("@web") . "/img/icon.png" ?>" alt="ensp">
            <span class="d-none d-md-inline text-white">ENSPY</span>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item <?= Yii::$app->controller->action->id == "accueil"?"active" : "" ?>">
                    <a class="nav-link" href="<?= Yii::getAlias("@guest.welcome") ?>">
                        <i class="fas fa-home mr-1"></i>Accueil
                    </a>
                </li>
                <li class="nav-item <?= Yii::$app->controller->action->id != "accueil"? "active" : "" ?>">
                    <a class="nav-link" href="<?= Yii::getAlias("@guest.connection") ?>">
                        <i class="fas fa-sign-in-alt mr-1"></i>Connexion
                    </a>
                </li>
                <li class="nav-item ml-2">
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-globe mr-1"></i>Français
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#"><i class="fas fa-flag-usa mr-2"></i>Anglais</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <?= $content ?>

    <?php include Yii::getAlias("@app") . "/includes/scripts.php"; ?>
    <?php if (isset($this->blocks['script'])): ?>
        <?= $this->blocks['script'] ?>
    <?php endif; ?>

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