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
<h1 class="title">Accusé de reception des fonds </h1>
<?php
$target = $help->member()->user();
$unit = $help->unit_amount;
?>
<div>
    Bonjour <?= $user->first_name." ".$user->name ?>, Nous vous informons que nous avons bien reçue les fonds pour l'aide sollictée en faveur de <?= $target->first_name." ".$target->name ?>
    <br>
    En effet, votre collègue <?= $target->first_name." ".$target->name ?>
   Cette somme a été reçue le  <b><?= (new DateTime)->format("d-m-Y") ?></b>
</div>