<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 31/12/18
 * Time: 14:31
 */

use app\models\Contribution;
use app\models\Member;

$this->beginBlock('title') ?>
    Aides
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
            --secondary-color: #757575;
            --purple-color: #9C27B0;
        }

        body {
            background-color: var(--background-light);
            font-family: 'Roboto', 'Arial', sans-serif;
            line-height: 1.6;
        }

        .container {
            padding: 2rem 1rem;
        }

        .white-block {
            background: var(--text-light);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .img-container {
            display: inline-block;
            width: 200px;
            height: 200px;
            position: relative;
            margin-bottom: 1.5rem;
            transition: transform var(--transition-speed);
        }

        .img-container:hover {
            transform: scale(1.05);
        }

        .img-container img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            box-shadow: var(--card-shadow);
            object-fit: cover;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .text-secondary {
            color: var(--secondary-color) !important;
        }

        .objective {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 1rem 0;
            color: var(--primary-color);
        }

        .contributed {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 1rem 0;
            color: var(--secondary-color);
        }

        .comments {
            background: rgba(0, 0, 0, 0.05);
            color: var(--text-dark);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin: 1.5rem 0;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .contribution {
            border-radius: var(--border-radius);
            border: 2px solid var(--purple-color);
            color: var(--purple-color);
            background-color: rgba(156, 39, 176, 0.1);
            padding: 1rem;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            transition: all var(--transition-speed);
            display: flex;
            align-items: center;
        }

        .contribution:hover {
            transform: translateY(-3px);
            box-shadow: var(--card-shadow);
        }

        .contribution img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 1rem;
            border: 2px solid var(--purple-color);
        }

        .table {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            margin: 2rem 0;
        }

        .table thead {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--text-light);
        }

        .table th, .table td {
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: background-color var(--transition-speed);
        }

        .table tbody tr:hover {
            background-color: rgba(33, 150, 243, 0.05);
        }

        .alert {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 2rem 0;
            text-align: center;
        }

        .member-name {
            font-size: 1.8rem;
            font-weight: 600;
            margin: 1rem 0;
            color: var(--primary-color);
        }

        .help-type {
            font-size: 1.4rem;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
        }

        .date-created {
            color: var(--secondary-color);
            font-size: 1rem;
            margin: 1rem 0;
        }

        .btn-add {
            position: fixed !important;
            bottom: 2rem;
            right: 2rem;
            z-index: 1000;
            padding: 1rem 2rem;
            border-radius: 50px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            color: var(--text-light);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all var(--transition-speed);
            box-shadow: 0 4px 20px rgba(33, 150, 243, 0.4);
        }

        .btn-add:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(33, 150, 243, 0.5);
        }

        .btn-add i {
            margin-right: 0.5rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem 0.5rem;
            }

            .img-container {
                width: 150px;
                height: 150px;
            }

            .objective, .contributed {
                font-size: 2rem;
            }

            .table {
                font-size: 0.9rem;
            }

            .contribution {
                margin: 0.5rem;
            }
        }
    </style>
<?php $this->endBlock() ?>
<?php
$member = $help->member();
$user = $member->user();
$helpType = $help->helpType();
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <?php if (Yii::$app->session->hasFlash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= Yii::$app->session->getFlash('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php elseif (Yii::$app->session->hasFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= Yii::$app->session->getFlash('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="white-block">
                <div class="row mb-5">
                    <div class="col-md-4 text-center">
                        <h3 class="section-title">Membre</h3>
                        <div class="img-container">
                            <img src="<?= \app\managers\FileManager::loadAvatar($user,"512") ?>" alt="<?= $user->name." ".$user->first_name ?>">
                        </div>
                        <h2 class="member-name"><?= $user->name." ".$user->first_name ?></h2>
                    </div>
                    <div class="col-md-8">
                        <h4 class="help-type text-center"><?= $helpType->title ?></h4>
                        <div class="comments">
                            <?= $help->comments ?>
                        </div>
                        <h6 class="date-created">Créée le : <?= $help->created_at ?></h6>
                        <div class="text-center">
                            <p class="objective">Montant de l'aide : <?= $help->amount ?> XAF</p>
                            <h4 class="text-primary mb-4">Contribution individuelle : <?= $help->unit_amount ?> XAF / membre</h4>
                            <h4 class="text-secondary mb-2">Montant des contributions perçues : </h4>
                            <p class="contributed"><?= ($t=$help->getContributedAmount())?$t:0 ?> XAF</p>
                            <p class="objective">Déficit : <?= $help->deficit ?> XAF</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <h3 class="section-title">Détails des Contributions</h3>
                        <?php
                        $contributions = $help->contributions;
                        if (count($contributions)):
                        ?>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Membre</th>
                                    <th>Date</th>
                                    <th>Montant versé</th>
                                    <th>Administrateur</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($contributions as $index => $contribution): ?>
                                    <?php 
                                    $m = $contribution->getMember()->one();
                                    $u = $m->user();
                                    $administrator = $contribution->getAdministrator()->one();
                                    $adminUser = $administrator ? $administrator->user() : null;
                                    ?>
                                    <tr>
                                        <th scope="row"><?= $index + 1 ?></th>
                                        <td class="text-capitalize"><?= $u->name . " " . $u->first_name ?></td>
                                        <td class="text-primary"><?= (new DateTime($contribution->date))->format("d-m-Y")  ?></td>
                                        <td class="text-primary font-weight-bold"><?= $contribution->amount ?> XAF</td>
                                        <td class="text-capitalize"><?= $adminUser ? ($adminUser->name. ' '.$adminUser->first_name) : 'N/A' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-center">Aucune contribution enregistrée</p>
                        <?php endif; ?>

                        <?php if ($help->state): ?>
                            <h3 class="section-title">Membres n'ayant pas encore contribué</h3>
                            <div class="row">
                                <?php
                                foreach ($help->waitedContributions as $contribution):
                                    $member = $contribution->member;
                                    $user = $member->user;
                                ?>
                                <div class="col-md-3 col-sm-6">
                                    <div class="contribution">
                                        <img src="<?= \app\managers\FileManager::loadAvatar($user)?>" alt="<?= $user->name.' '.$user->first_name ?>">
                                        <span class="text-capitalize"><?= $user->name.' '.$user->first_name ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($help->state): ?>
    <a href="<?= Yii::getAlias("@administrator.new_contribution")."?q=".$help->id ?>" class="btn btn-add">
        <i class="fas fa-plus"></i>Nouvelle contribution
    </a>
<?php endif; ?>

<?php
$allContributions = Contribution::findAll([ 'help_id' => $help->id]);
$allMembers = Member::find()->where(['<>', 'id', $help->member_id])->all();
$allActiveMembers = Member::findAll(['active' => true]);
?>

<div class="col-12 white-block m-4">
    <table class="table table-hover">
        <thead class="blue-grey lighten-4">
        <tr>
            <th>#</th>
            <th>Membre</th>
            <th>statut</th>
            <th>Date</th>
            <th>montant versé</th>
            <th>montant restant</th>
            <th>action</th>
            <th>Administrateur</th>

        </tr>

        </thead>
        <tbody>
        <?php foreach ($allMembers as $index => $asMem): ?>
            <?php 
            $m = Contribution::find()->where(['member_id'=>$asMem->id,'help_id'=>$help->id])->one();
            if ($m) {
                $u = $asMem->user();  
                $asAdmin = $asMem->administrator();
                $asAdminUser = $asAdmin ? $asAdmin->user() : null;
                $mleft = $help->unit_amount - $m->amount;
                $mAmount = $m->amount;
                $mStatus = ($mleft>=0)? true: false;
                $params = [
                    'q'=>$help->id,
                    'm'=>$asMem->id,
                ];
            ?>
                <tr>
                    <th scope="row"><?= $index + 1 ?></th>
                    <td class="text-capitalize"><?= $u->name . " " . $u->first_name ?></td>
                    <td class="text-capitalize"><?= $asMem->active? 'actif' : 'non-actif' ?></td>
                    <td class="blue-text"><?= (new DateTime($m->date))->format("d-m-Y")  ?></td>
                    <td class="text-capitalize"><?= $mAmount ?></td>
                    <td class="text-capitalize"><?= $mStatus? $mleft : "already full" ?></td>
                    <td class="text-capitalize">
                        <a href="<?= Yii::getAlias("@administrator.new_contribution")."?q=".$help->id."&m=".$asMem->id."&".http_build_query($params) ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>contribuer</a>
                        <a href="<?= Yii::getAlias("@administrator.new_contribution")."?q=".$help->id."&m=".$asMem->id."&".http_build_query($params) ?>" class="btn btn-danger btn-sm">annuler</a>
                    </td>
                    <td class="text-capitalize"><?= $asAdminUser ? ($asAdminUser->name. ' '.$asAdminUser->first_name) : 'N/A' ?></td>
                </tr>
            <?php 
            }
            endforeach; 
            ?>
        </tbody>
    </table>

</div>
