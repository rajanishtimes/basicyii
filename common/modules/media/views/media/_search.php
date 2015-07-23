<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Searchasset */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="asset-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'Id') ?>

    <?= $form->field($model, 'filename') ?>

    <?= $form->field($model, 'path') ?>

    <?= $form->field($model, 'uri') ?>

    <?= $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'mimetype') ?>

    <?php // echo $form->field($model, 'source') ?>

    <?php // echo $form->field($model, 'embedcode') ?>

    <?php // echo $form->field($model, 'mediahash') ?>

    <?php // echo $form->field($model, 'metainfo') ?>

    <?php // echo $form->field($model, 'createdBy') ?>

    <?php // echo $form->field($model, 'updatedBy') ?>

    <?php // echo $form->field($model, 'createdOn') ?>

    <?php // echo $form->field($model, 'modifiedOn') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'ip') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
