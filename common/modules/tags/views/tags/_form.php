<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\tags */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tags-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php echo Html::activeHiddenInput($model,'issystem');?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => 45]) ?>

    <?php //$form->field($model, 'createdBy')->textInput(['maxlength' => 10]) ?>

    <?php //$form->field($model, 'updatedBy')->textInput(['maxlength' => 10]) ?>

    <?php //$form->field($model, 'createdOn')->textInput() ?>

    <?php //$form->field($model, 'updatedOn')->textInput() ?>

    <?php //$form->field($model, 'status')->textInput() ?>

    <?php //$form->field($model, 'ip')->textInput(['maxlength' => 45]) ?>

    <?php //$form->field($model, 'issystem')->textInput() ?>
    <?=$form->field($model, 'issystem')->dropDownList(\common\models\Tags::$TYPE);?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
