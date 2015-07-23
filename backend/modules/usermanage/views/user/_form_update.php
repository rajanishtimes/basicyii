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

    <?php $form = ActiveForm::begin(['validationUrl' => ['validate','id'=>$model->Id]]); ?>

    <?php if(!$model->isNewRecord){ ?>
    <?= $form->field($model, 'status')->dropDownList($model->getStatuses(),[]) ?>    
    <?php }else{ ?>
    <?= $form->field($model, 'status')->hiddenInput(['value' => 1])->label('') ?>
    <?php } ?>

    <?php //<?= $form->field($model, 'role')->textInput() ?>

    <?= $form->field($model, 'username',['enableAjaxValidation'=>true])->textInput(['maxlength' => 16]) ?>

    <?= $form->field($model, 'email',['enableAjaxValidation'=>true])->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'lastname')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => 13]) ?>
    
    <?= $form->field($model, 'groupId')->dropDownList(ArrayHelper::merge(["Select"=>'Select'], ArrayHelper::map(\common\models\Group::find()
                             ->where(['status' => '1'])->all(),'Id','name'))) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::button('Back', ['class' =>'btn btn-default','onclick' => 'history.go(-1);return true;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
