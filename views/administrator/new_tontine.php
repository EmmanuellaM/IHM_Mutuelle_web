<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 31/12/18
 * Time: 14:31
 */
$this->beginBlock('title') ?>
Nouvelle Tontine
<?php $this->endBlock() ?>
<?php $this->beginBlock('style') ?>
<style>
    :root {
        --primary-color: #2196F3;
        --primary-dark: #1976D2;
        --success-color: #4CAF50;
        --text-dark: #333;
        --text-light: #fff;
        --background-light: #f8f9fa;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        --transition-speed: 0.3s;
        --border-radius: 12px;
        --input-bg: #fff;
        --input-border: #e0e0e0;
    }

    body {
        background-color: var(--background-light);
        font-family: 'Roboto', 'Arial', sans-serif;
        line-height: 1.6;
    }

    .container {
        padding: 2rem 1rem;
    }

    .page-title {
        color: var(--text-dark);
        font-size: 2rem;
        font-weight: 600;
        text-align: center;
        margin-bottom: 2rem;
        position: relative;
        padding-bottom: 1rem;
    }

    .page-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
        border-radius: 2px;
    }

    #new-tontine-form {
        background-color: var(--text-light);
        padding: 2.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        margin: 0 auto;
        max-width: 800px;
        transition: transform var(--transition-speed);
    }

    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
        font-weight: 500;
        font-size: 1rem;
    }

    .form-control {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 2px solid var(--input-border);
        border-radius: var(--border-radius);
        background-color: var(--input-bg);
        transition: all var(--transition-speed);
        font-size: 1rem;
        color: var(--text-dark);
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(33, 150, 243, 0.1);
        outline: none;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1em;
        padding-right: 2.5rem;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .btn-primary {
        display: inline-block;
        padding: 1rem 2rem;
        font-size: 1rem;
        font-weight: 500;
        text-align: center;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-light);
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border: none;
        border-radius: 50px;
        cursor: pointer;
        transition: all var(--transition-speed);
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .form-section {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: var(--text-light);
        padding: 2rem;
        border-radius: var(--border-radius);
        margin-bottom: 2rem;
        text-align: center;
    }

    .form-section h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 500;
    }

    .form-section p {
        margin: 1rem 0 0;
        opacity: 0.9;
    }

    .error-message {
        background-color: #fff3f3;
        color: #d32f2f;
        padding: 1rem;
        border-radius: var(--border-radius);
        text-align: center;
        margin-bottom: 2rem;
    }

    .text-secondary {
        color: #d32f2f !important;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }

        #new-tontine-form {
            padding: 1.5rem;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .btn-primary {
            width: 100%;
        }
    }
</style>
<?php $this->endBlock() ?>

<div class="container">
    <div class="row justify-content-center">
        <?php if (count(\app\models\Member::find()->where(['active' => true])->all()) > 1): ?>
            <div class="col-12">
                <h3 class="page-title">Création d'une nouvelle tontine</h3>
            </div>
            <div class="col-12">
                <div class="form-section">
                    <h3>Informations importantes</h3>
                    <p>Veuillez remplir tous les champs requis pour créer une nouvelle tontine. Les membres actifs seront automatiquement notifiés.</p>
                </div>
                <?php
                $form = \yii\widgets\ActiveForm::begin([
                    'method' => 'post',
                    'errorCssClass' => 'text-secondary',
                    'action' => '@administrator.add_tontine',
                    'options' => ['id' => 'new-tontine-form']
                ]);

                $tontine_types = \app\models\TontineType::find()->where(['active' => true])->all();
                $members = \app\models\Member::find()->where(['active' => true])->all();

                $heps = [];
                foreach ($tontine_types as $tontine_type) {
                    $heps[$tontine_type->id] = $tontine_type->title . " - " . $tontine_type->amount . ' XAF';
                }

                $items = [];
                foreach ($members as $member) {
                    $user = \app\models\User::findOne($member->user_id);
                    $items[$member->id] = $user->name . " " . $user->first_name;
                }
                ?>
                <div class="form-group">
                    <label for="tontine_type_id">Type de la Tontine</label>
                    <?= $form->field($model, "tontine_type_id")->dropDownList($heps, [
                        'required' => 'required',
                        'class' => 'form-control',
                        'prompt' => 'Sélectionnez le type de tontine'
                    ])->label(false) ?>
                </div>
                <div class="form-group">
                    <label for="member_id">Membre concerné</label>
                    <?= $form->field($model, "member_id")->dropDownList($items, [
                        'required' => 'required',
                        'class' => 'form-control',
                        'prompt' => 'Sélectionnez un membre'
                    ])->label(false) ?>
                </div>
                <div class="form-group">
                    <label for="limit_date">Date limite de contribution</label>
                    <?= $form->field($model, "limit_date")->input("date", [
                        'required' => 'required',
                        'class' => 'form-control'
                    ])->label(false) ?>
                </div>
                <div class="form-group">
                    <label for="comments">Commentaires</label>
                    <?= $form->field($model, "comments")->textarea([
                        'required' => 'required',
                        'class' => 'form-control',
                        'placeholder' => 'Ajoutez des commentaires ou des détails supplémentaires...'
                    ])->label(false) ?>
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">
                        Créer la tontine
                    </button>
                </div>
                <?php
                \yii\widgets\ActiveForm::end();
                ?>
            </div>
        <?php else: ?>
            <div class="col-12">
                <div class="error-message">
                    <h3>Impossible de créer une tontine</h3>
                    <p>Il faut au moins 2 membres actifs pour créer une tontine.</p>
                    <div class="mt-4">
                        <a href="<?= Yii::getAlias("@administrator.new_member") ?>" class="btn btn-primary">
                            Ajouter un nouveau membre
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
