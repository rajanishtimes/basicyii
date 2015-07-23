<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var common\models\User $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //<?= $form->field($model, 'role')->textInput() ?>

    <?= $form->field($model, 'username',['enableAjaxValidation'=>true])->textInput(['maxlength' => 16]) ?>

    <?= $form->field($model, 'email',['enableAjaxValidation'=>true])->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'lastname')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => 10]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::button('Back', ['class' =>'btn btn-default','onclick' => 'history.go(-1);return true;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
