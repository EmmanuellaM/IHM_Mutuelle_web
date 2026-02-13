<?php
$this->beginBlock('style')
?>
<style>
    body {
        font-family: 'sans-serif';
    }
    .title {
        margin-bottom: 10px;
        text-align: center;
        color: dodgerblue;
        font-size: 2rem;
    }
</style>
<?php $this->endBlock() ?>
<?php
    $monthNames = [
            '01' => 'Janvier',
            '02' => 'Février',
            '03' => 'Mars',
            '04' => 'Avril',
            '05' => 'Mai',
            '06' => 'Juin',
            '07' => 'Juillet',
            '08' => 'Août',
            '09' => 'Septembre',
            '10' => 'Octobre',
            '11' => 'Novembre',
            '12' => 'Décembre',
        ];

        $monthNumber = Yii::$app->formatter->asDate($session->date, 'MM');
        ?>
                    
<h1 class="title">Modification de la session courante</h1>
<div>
    Bonjour Mr/Mme <?= $user->first_name." ".$user->name ?>, Nous vous informons que la date de la session en cours a été modifié,
    à la mutuelle de l'Ecole Nationale Supérieure Polytechnique.
    Il s'agit de la Session du mois de <?= $monthNames[$monthNumber] ?>. Elle est nouvellement fixée au
    <?= Yii::$app->formatter->asDate($session->date, 'd')?> <?= $monthNames[$monthNumber] ?>
    de l'exercice actuel.
</div>