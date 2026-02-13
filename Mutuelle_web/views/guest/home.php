<div class="landing-page">
    <div class="container">
        <div class="row min-vh-100 align-items-center justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 text-center">
                <div class="welcome-box p-4 p-md-5">
                    <h1 class="display-4 mb-4 text-primary fw-bold">
                        Bienvenue à la Mutuelle<br>
                        <span class="text-secondary">des Enseignants de l'ENSPY</span>
                    </h1>
                    
                    <div class="d-grid gap-2 d-md-block">
                        <a href="<?= Yii::getAlias("@guest.connection") ?>" class="btn btn-primary btn-lg px-5 py-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Connexion
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.landing-page {
    background: url('/web/img/background.jpeg') no-repeat center center;
    background-size: cover;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.welcome-box {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.welcome-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: linear-gradient(45deg, #4e73df 0%, #224abe 100%);
    border: none;
    border-radius: 50px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
}

.display-4 {
    font-family: 'Poppins', sans-serif;
    line-height: 1.4;
}

.text-secondary {
    color: #6c757d !important;
}
</style>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<!-- Add Poppins font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
