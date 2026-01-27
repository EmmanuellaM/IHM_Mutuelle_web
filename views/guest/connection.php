<?php 

use yii\bootstrap\Html;

$this->beginBlock('title'); ?>
Connexion
<?php $this->endBlock('title'); ?>

<style>
.login-section {
    padding: 4rem 0;
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    min-height: calc(100vh - 76px);
}

.role-card {
    background: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}

.role-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.role-card .card-img-top {
    height: 200px;
    object-fit: cover;
    transition: all 0.3s ease;
}

.role-card:hover .card-img-top {
    transform: scale(1.05);
}

.role-card .card-body {
    padding: 2rem;
}

.role-card .card-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 1rem;
}

.role-card .card-text {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.6;
    height: auto;
    margin-bottom: 1.5rem;
}

.role-card .btn-connect {
    background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
    border: none;
    border-radius: 50px;
    padding: 0.8rem 2rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.role-card .btn-connect:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 0, 0.3);
}

/* Modal Styles */
.login-modal .modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
}

.login-modal .modal-header {
    background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
    border-radius: 15px 15px 0 0;
    padding: 1.5rem;
}

.login-modal .modal-title {
    color: white;
    font-weight: 600;
}

.login-modal .close {
    color: white;
    opacity: 0.8;
    transition: all 0.2s ease;
}

.login-modal .close:hover {
    opacity: 1;
}

.login-modal .modal-body {
    padding: 2rem;
}

.login-modal .form-group {
    position: relative;
    margin-bottom: 1.5rem;
}

.login-modal .form-control {
    padding: 1.2rem 1rem 1.2rem 3rem;
    border-radius: 8px;
    border: 2px solid #e9ecef;
    font-size: 1rem;
    transition: all 0.2s ease;
}

.login-modal .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 0, 0.25);
}

.login-modal .prefix {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

.login-modal .toggle-password {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: none;
    color: #6c757d;
    cursor: pointer;
    transition: all 0.2s ease;
}

.login-modal .toggle-password:hover {
    color: var(--primary-color);
}

.login-modal .reset-password {
    text-align: right;
    margin-bottom: 1rem;
}

.login-modal .reset-password a {
    color: var(--primary-color);
    text-decoration: none;
    transition: all 0.2s ease;
}

.login-modal .reset-password a:hover {
    color: var(--primary-dark);
}

.login-modal .custom-checkbox .custom-control-label::before {
    border-radius: 4px;
    border: 2px solid #e9ecef;
}

.login-modal .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.login-modal .btn-submit {
    background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
    border: none;
    border-radius: 50px;
    padding: 0.8rem 2.5rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.login-modal .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 0, 0.3);
}

.alert {
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    border: none;
}

.alert-danger {
    background-color: #fee2e2;
    color: #dc2626;
}

.alert-success {
    background-color: #dcfce7;
    color: #16a34a;
}
</style>

<div class="login-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 mb-4">
                <!-- Card for Administrator -->
                <div class="card role-card">
                    <div class="card-img-wrapper">
                        <img class="card-img-top" src="/img/admin_connection.jpg" alt="Administrateur">
                    </div>
                    <div class="card-body text-center">
                        <h4 class="card-title">Administrateur</h4>
                        <p class="card-text">Les administrateurs ont le droit d'enregistrer des entrées, et des sorties d'argent.</p>
                        <button data-toggle="modal" data-target="#modalAdministrator" class="btn btn-connect">
                            <i class="fas fa-user-shield mr-2"></i>Connexion
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <!-- Card for Member -->
                <div class="card role-card">
                    <div class="card-img-wrapper">
                        <img class="card-img-top" src="/img/member_connection.jpg" alt="Membre">
                    </div>
                    <div class="card-body text-center">
                        <h4 class="card-title">Membre</h4>
                        <p class="card-text">Les membres peuvent voir les informations sur leurs comptes ainsi que les informations générales de la mutuelle.</p>
                        <button data-toggle="modal" data-target="#modalMember" class="btn btn-connect">
                            <i class="fas fa-user mr-2"></i>Connexion
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Member -->
<div class="modal fade login-modal" id="modalMember" tabindex="-1" role="dialog" aria-labelledby="modalMemberLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="member-form" class="modal-content" action="<?= Yii::getAlias("@guest.member_form") ?>" method="post">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>" />
            <div class="modal-header">
                <h4 class="modal-title">Connexion - Membre</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" id="member-error" style="display: none;">
                    <i class="fas fa-exclamation-circle mr-2"></i>Le nom d'utilisateur ou le mot de passe est incorrect
                </div>
                <div class="alert alert-success" id="member-success" style="display: none;">
                    <i class="fas fa-check-circle mr-2"></i>Connexion réussie. Redirection...
                </div>
                
                <div class="form-group">
                    <i class="fas fa-user prefix"></i>
                    <input type="text" required id="member-username" class="form-control" name="username" placeholder="Votre pseudo">
                </div>

                <div class="form-group">
                    <i class="fas fa-key prefix"></i>
                    <input type="password" required id="member-password" class="form-control" name="password" placeholder="Votre mot de passe">
                    <button type="button" class="toggle-password" id="toggle-password-member">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="reset-password">
                    Réinitialiser <?= Html::a('mot de passe', ['/guest/reset_password']) ?>?
                </div>

                <div class="custom-control custom-checkbox mb-4">
                    <input type="checkbox" class="custom-control-input" name="rememberMe" id="member-rememberMe">
                    <label class="custom-control-label" for="member-rememberMe">Se souvenir de moi</label>
                </div>

                <div class="text-center">
                    <button class="btn btn-submit" type="submit">
                        <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal for Administrator -->
<div class="modal fade login-modal" id="modalAdministrator" tabindex="-1" role="dialog" aria-labelledby="modalAdministratorLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="administrator-form" class="modal-content" method="post" action="/guest/administrator-form">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>" />
            <div class="modal-header">
                <h4 class="modal-title">Connexion - Administrateur</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" id="administrator-error" style="display: none;">
                    <i class="fas fa-exclamation-circle mr-2"></i>Le nom d'utilisateur ou le mot de passe est incorrect
                </div>
                <div class="alert alert-success" id="administrator-success" style="display: none;">
                    <i class="fas fa-check-circle mr-2"></i>Connexion réussie. Redirection...
                </div>

                <div class="form-group">
                    <i class="fas fa-user prefix"></i>
                    <input type="text" required id="administrator-username" class="form-control" name="username" placeholder="Votre pseudo">
                </div>

                <div class="form-group">
                    <i class="fas fa-key prefix"></i>
                    <input type="password" required id="administrator-password" class="form-control" name="password" placeholder="Votre mot de passe">
                    <button type="button" class="toggle-password" id="toggle-password-administrator">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="reset-password">
                    Réinitialiser <?= Html::a('mot de passe', ['/guest/reset_password']) ?>?
                </div>

                <div class="custom-control custom-checkbox mb-4">
                    <input type="checkbox" class="custom-control-input" name="rememberMe" id="administrator-rememberMe">
                    <label class="custom-control-label" for="administrator-rememberMe">Se souvenir de moi</label>
                </div>

                <div class="text-center">
                    <button class="btn btn-submit" type="submit">
                        <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
$this->registerJs(<<<JS
    // Toggle password visibility
    $('.toggle-password').on('click', function() {
        const passwordField = $(this).siblings('input[type="password"]');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    // Handle form submission for member
    $('#member-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const actionUrl = form.attr('action');

        $.ajax({
            type: 'POST',
            url: actionUrl,
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#member-success').fadeIn();
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 1000);
                } else {
                    var errorMsg = response.message ? response.message : "Le nom d'utilisateur ou le mot de passe est incorrect";
                    $('#member-error').html('<i class="fas fa-exclamation-circle mr-2"></i>' + errorMsg).fadeIn().delay(3000).fadeOut();
                }
            },
            error: function() {
                $('#member-error').fadeIn().delay(3000).fadeOut();
            }
        });
    });

    // Handle form submission for administrator - AJAX comme pour les membres
    $('#administrator-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const actionUrl = form.attr('action');

        $.ajax({
            type: 'POST',
            url: actionUrl,
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#administrator-success').fadeIn();
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 1000);
                } else {
                    var errorMsg = response.message ? response.message : "Le nom d'utilisateur ou le mot de passe est incorrect";
                    $('#administrator-error').html('<i class="fas fa-exclamation-circle mr-2"></i>' + errorMsg).fadeIn().delay(3000).fadeOut();
                }
            },
            error: function() {
                $('#administrator-error').fadeIn().delay(3000).fadeOut();
            }
        });
    });
JS
);
?>
