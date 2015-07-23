<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\adminUi\widget\Callout;

/**
 * @var yii\web\View $this
 * @var common\models\User $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['validationUrl' => ['validate']]); ?>

    <?php if(!$model->isNewRecord){ ?>
    <?= $form->field($model, 'status')->dropDownList($model->getStatuses(),[]) ?>    
    <?php }else{ ?>
    <?= $form->field($model, 'status')->hiddenInput(['value' => 1])->label('') ?>
    <?php } ?>

    <?php //<?= $form->field($model, 'role')->textInput() ?>

    <?= $form->field($model, 'username',['enableAjaxValidation'=>true])->textInput(['maxlength' => 16]) ?>
    
    <?php Callout::begin([
        'options' => [
            'class' => 'callout-info',
            'header' => 'Password Logic',
        ]
    ])?>
    <p>Character are allowed here are ..</p>
    <ul>
        <li>Number 0-9</li>
        <li>Alphabet a-z</li>
        <li>Capital letter A-Z</li>
        <li>Special character (!@#$%-.)</li>
    </ul>
    <?php Callout::end()?>
    
    <?= $form->field($model, 'password1')->passwordInput(['maxlength' => 16]) ?>
    
    <?= $form->field($model, 'password2')->passwordInput(['maxlength' => 16]) ?>

    <?= $form->field($model, 'email',['enableAjaxValidation'=>true])->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'lastname')->textInput(['maxlength' => 45]) ?>
    
    <?= $form->field($model, 'phone')->textInput(['maxlength' => 13]) ?>

    <?= $form->field($model, 'groupId')->dropDownList(ArrayHelper::merge(['Select'=>'Select'], ArrayHelper::map(\common\models\Group::find()
                             ->where(['status' => '1'])->all(),'Id','name'))) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::button('Back', ['class' =>'btn btn-default','onclick' => 'history.go(-1);return true;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
