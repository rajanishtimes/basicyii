<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\adminUi\widget\Callout;

/**
 * @var yii\web\View $this
 * @var common\models\User $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    
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

    <div class="form-group">
        <?= Html::submitButton('Update Password', ['class' => 'btn btn-primary']) ?>
        <?= Html::button('Back', ['class' =>'btn btn-default','onclick' => 'history.go(-1);return true;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
