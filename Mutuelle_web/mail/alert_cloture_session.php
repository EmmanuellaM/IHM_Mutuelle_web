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
    .amount {
        font-size: 1.2rem;
        color: orangered;
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
<h1 class="title">Fin de la session du <?= Yii::$app->formatter->asDate($session->date, 'd')?> <?= $monthNames[$monthNumber] ?><?=$session->number()?></h1>
<div>
    Bonjour Mr/Mme <?= $user->first_name." ".$user->name ?>, Nous vous informons que la session du <?= Yii::$app->formatter->asDate($session->date, 'd')?> <?= $monthNames[$monthNumber] ?> de l'exercice actuel,
    à la mutuelle de l'Ecole Nationale Supérieure Polytechnique, vient de cloturer.
    <br>
    <?php if ( ($borrowing=$member->activeBorrowing()) ):
        $refundedAmount = $borrowing->refundedAmount();
        $intendedAmount = $borrowing->intendedAmount();
        $a = $intendedAmount - $refundedAmount;
        ?>
    Votre dette actuelle à la mutuelle est de  <span class="amount"><?= $a?$a:0 ?> XAF</span>
    <?php endif;?>
</div>