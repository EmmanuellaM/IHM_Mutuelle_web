<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Session;

/* @var $this yii\web\View */
/* @var $model app\models\Agape3 */
/* @var $sessions app\models\Session[] */

$this->title = 'Create Agape3';
$this->params['breadcrumbs'][] = ['label' => 'Agape3s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="<?= Yii::getAlias('@web/css/admin-styles.css') ?>">
<style>
</style>
<?php $this->endBlock() ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="section-title">Mise à jour</h1>
        </div>
    </div>
</div>

<div class="agape3-create">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'session_id')->dropDownList(
        Session::find()->select(['date', 'id'])->indexBy('id')->column(),
        ['prompt' => 'Select Session']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
