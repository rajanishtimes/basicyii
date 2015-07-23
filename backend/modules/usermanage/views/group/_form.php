<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\group $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 25]) ?>

    <?= $form->field($model, 'parentId')->dropDownList($model->getGroups(),[]) ?>    
    <?php if(!$model->isNewRecord){ ?>
    <?= $form->field($model, 'status')->dropDownList($model->getStatuses(),[]) ?>    
    <?php }else{ ?>
    <?= $form->field($model, 'status')->hiddenInput(['value' => 1])->label('') ?>
    <?php } ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::button('Back', ['class' =>'btn btn-default','onclick' => 'history.go(-1);return true;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
