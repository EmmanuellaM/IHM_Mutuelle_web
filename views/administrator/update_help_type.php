<?php $this->beginBlock('title') ?>
Type d'aide
<?php $this->endBlock()?>

<?php $this->beginBlock('style')?>
<?php $this->endBlock()?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-12 white-block">

            <?php if (Yii::$app->session->hasFlash('success')): ?>
                <div class="alert alert-success">
                    <?= Yii::$app->session->getFlash('success') ?>
                </div>
            <?php endif; ?>

            <?php
            $form1 = \yii\widgets\ActiveForm::begin([
                'method' => 'post',
                'id' => 'form1',
                'options' => ['enctype' => 'multipart/form-data', 'class' => 'col-md-5 mb-2 white-block mr-md-2'],
                'action' => '@administrator.apply_help_type_update',
                'errorCssClass' => 'text-secondary',
            ]);
            ?>

            <?= $form1->field($model, 'id')->hiddenInput()->label(false) ?>
            <?= $form1->field($model, 'title')->label("Titre de l'aide")->input('text', ['required' => 'required']) ?>
            <?= $form1->field($model, 'amount')->label("Montant")->input('number', ['min' => 0, 'required' => 'required']) ?>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Modifier</button>
            </div>

            <?php \yii\widgets\ActiveForm::end() ?>

            <?php
            $form2 = \yii\widgets\ActiveForm::begin([
                'method' => 'post',
                'id' => 'form2',
                'action' => '@administrator.delete_help_type',
                'errorCssClass' => 'text-secondary',
            ]);
            ?>

            <?= $form2->field($model, 'id')->hiddenInput()->label(false) ?>

            <?php \yii\widgets\ActiveForm::end() ?>

            <div class="col-12 text-right">
                <button class="btn btn-danger m-0 p-2" data-toggle="modal" data-target="#modal">Supprimer</button>

                <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <p class="text-center">Êtes-vous sûr(e) de vouloir supprimer ce type d'aide?</p>
                                <div class="form-group text-center">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Non</button>
                                    <button class="btn btn-primary" onclick="$('#form2').submit()">Oui</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>