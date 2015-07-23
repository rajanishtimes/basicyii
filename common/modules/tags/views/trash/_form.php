<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\tags */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tags-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'createdBy')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'updatedBy')->textInput(['maxlength' => 10]) ?>

    <?= $form->field($model, 'createdOn')->textInput() ?>

    <?= $form->field($model, 'updatedOn')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'issystem')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
