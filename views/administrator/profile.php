<?php $this->beginBlock('title') ?>
Profil
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
    
    .edit-btn {
        background: linear-gradient(45deg, #2196F3, #00BCD4);
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 25px;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .edit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(33,150,243,0.3);
        background: linear-gradient(45deg, #1976D2, #0097A7);
        color: white;
    }
</style>
<?php $this->endBlock()?>

<div class="container mt-5 mb-5">
    <div class="profile-container">
        <div class="row">
            <div class="col-md-4 text-center">
                <div class="img-container">
                    <img src="<?= \app\managers\FileManager::loadAvatar($this->params['user'])?>" alt="Photo de profil">
                </div>
                <h2 class="admin-name text-capitalize"><?= $this->params['administrator']->username?></h2>
            </div>
            <div class="col-md-8">
                <div class="info-section">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-4">
                            <div class="info-label">Nom</div>
                            <div class="info-value"><?= $this->params['user']->name ?></div>
                        </div>
                        <div class="col-12 col-md-6 mb-4">
                            <div class="info-label">Prénom</div>
                            <div class="info-value"><?= $this->params['user']->first_name ?></div>
                        </div>
                        <div class="col-12 col-md-6 mb-4">
                            <div class="info-label">Téléphone</div>
                            <div class="info-value"><?= $this->params['user']->tel ?></div>
                        </div>
                        <div class="col-12 col-md-6 mb-4">
                            <div class="info-label">Email</div>
                            <div class="info-value"><?= $this->params['user']->email ?></div>
                        </div>
                        <div class="col-12 mb-4">
                            <div class="info-label">Adresse</div>
                            <div class="info-value"><?= $this->params['user']->address ?></div>
                        </div>
                        <div class="col-12 mb-4">
                            <div class="info-label">Date d'inscription</div>
                            <div class="info-value"><?= $this->params['user']->created_at ?></div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <a href="<?= Yii::getAlias("@administrator.update_profile") ?>" class="btn edit-btn">
                            <i class="fas fa-edit me-2"></i>Modifier le profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>