<?php $this->beginBlock('title') ?>
Détails Administrateur
<?php $this->endBlock()?>

<?php $this->beginBlock('style')?>
<style>
    .profile-container {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
        padding: 2rem;
        transition: all 0.3s ease;
    }
    
    .img-container {
        display: inline-block;
        width: 200px;
        height: 200px;
        padding: 5px;
        background: linear-gradient(45deg, #2196F3, #00BCD4);
        border-radius: 50%;
        margin-bottom: 1.5rem;
    }
    
    .img-container img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 4px solid #fff;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .img-container:hover img {
        transform: scale(1.05);
    }
    
    .admin-name {
        color: #2196F3;
        font-weight: 600;
        margin: 1rem 0;
    }
    
    .info-section {
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    .info-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 0.5rem;
    }
    
    .info-value {
        color: #2196F3;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .back-btn {
        background: #e0e0e0;
        color: #333;
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        display: inline-block;
    }

    .back-btn:hover {
        background: #d0d0d0;
        color: #000;
        text-decoration: none;
        transform: translateY(-2px);
    }
</style>
<?php $this->endBlock()?>

<div class="container mt-5 mb-5">
    <div class="profile-container">
        <div class="row">
            <div class="col-md-4 text-center">
                <div class="img-container">
                    <img src="<?= \app\managers\FileManager::loadAvatar($userModel)?>" alt="Photo de profil">
                </div>
                <h2 class="admin-name text-capitalize"><?= htmlspecialchars($adminModel->username) ?></h2>
                <?php if ($adminModel->root): ?>
                    <span class="badge badge-danger p-2 mb-3">Super Administrateur</span>
                <?php else: ?>
                    <span class="badge badge-info p-2 mb-3">Administrateur</span>
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <div class="info-section">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-4">
                            <div class="info-label">Nom</div>
                            <div class="info-value"><?= htmlspecialchars($userModel->name) ?></div>
                        </div>
                        <div class="col-12 col-md-6 mb-4">
                            <div class="info-label">Prénom</div>
                            <div class="info-value"><?= htmlspecialchars($userModel->first_name) ?></div>
                        </div>
                        <div class="col-12 col-md-6 mb-4">
                            <div class="info-label">Téléphone</div>
                            <div class="info-value"><?= htmlspecialchars($userModel->tel) ?></div>
                        </div>
                        <div class="col-12 col-md-6 mb-4">
                            <div class="info-label">Email</div>
                            <div class="info-value"><?= htmlspecialchars($userModel->email) ?></div>
                        </div>
                        <div class="col-12 mb-4">
                            <div class="info-label">Adresse</div>
                            <div class="info-value"><?= htmlspecialchars($userModel->address) ?></div>
                        </div>
                        <div class="col-12 mb-4">
                            <div class="info-label">Date d'inscription</div>
                            <div class="info-value"><?= $userModel->created_at ?></div>
                        </div>
                    </div>
                    <div class="text-right mt-4">
                        <a href="<?= \yii\helpers\Url::to(['administrator/administrateurs']) ?>" class="back-btn">
                            <i class="fas fa-arrow-left me-2"></i> Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
