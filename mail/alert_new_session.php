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
                    
<h1 class="title">Nouvelle session</h1>
<div>
    Bonjour <?= $user->first_name." ".$user->name ?>, Nous vous informons qu'une nouvelle session a débuté,
    à la mutuelle de l'Ecole Nationale Supérieure Polytechnique.
    Il s'agit de la Session du <?= Yii::$app->formatter->asDate($session->date, 'd')?> <?= $monthNames[$monthNumber] ?>
    de l'exercice actuel.
</div>