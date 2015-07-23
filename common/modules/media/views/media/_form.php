<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\adminUi\widget\Collapse;
use yii\adminUi\widget\Box;
use yii\adminUi\widget\Column;
use yii\adminUi\widget\Row;
use yii\adminUi\widget\ButtonGroup;
use yii\adminUi\widget\Button;
use common\modules\media\widgets\MediaUpload;
use common\modules\media\widgets\EmbedUpload;

/* @var $this yii\web\View */
/* @var $model common\models\Asset */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="asset-form">
    <?php
    
    $mediaupload =  MediaUpload::widget([
        'options' => [
            'addRemoveLinks' => true,
        ]
    ]);
    
    $embedupload =  EmbedUpload::widget();
    
    
    Row::begin();
    Column::begin([
        'grid' => [
            [
                'type' => Column::TYPE_DESKTOP,
                'size' => Column::SIZE_FULL,
            ]
        ]
    ]);
    echo ButtonGroup::widget([
        'buttons' => [
          Button::widget([
              'label' => 'Media Upload',
              'options' => [
                            'class' => 'btn-primary btn-lg col-xs-6 col-sm-6 col-md-6 col-lg-6',
                            'data-toggle'=>"collapse",
                            'data-target'   => '#media-upload',
                            'data-parent'   =>"#media-upload-wrapper"
                            ],
              ]),
          Button::widget([
              'label' => 'Embed Media Upload',
              'options' => [
                            'class' => 'btn-info btn-lg col-xs-6 col-sm-6 col-md-6 col-lg-6',
                            'data-toggle'=>"collapse",
                            'data-target'   => '#embed-upload',
                            'data-parent'   =>"#media-upload-wrapper"
                            ],
              ]),
      ],
        'options' => ['class' => 'col-md-12']
    ]);
    Column::end();
    Row::end();
    
    Row::begin();
    Column::begin([
        'grid' => [
            [
                'type' => Column::TYPE_DESKTOP,
                'size' => Column::SIZE_FULL,
            ]
        ]
    ]);
    
    echo Collapse::widget([
        'box'=> false,
        'header' => false,
        'options' => ['id'=>'media-upload-wrapper'],
      'items' => [          
          [
              'content' => $mediaupload,              
              'contentOptions' => ['id'=>'media-upload'],
              
          ],
          [
              'content' => $embedupload,
              'contentOptions' => ['id'=>'embed-upload'],              
          ]
      ]
  ]);
    
    Column::end();
    Row::end();
    
    /*
    echo MediaUpload::widget([
        'options' => [
            'addRemoveLinks' => true,
        ]
    ]);
    //*/
    ?>
    <?php /*/?>
    
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Id')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'filename')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'path')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'uri')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'mimetype')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'source')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'embedcode')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'mediahash')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'metainfo')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'createdBy')->textInput() ?>

    <?= $form->field($model, 'updatedBy')->textInput() ?>

    <?= $form->field($model, 'createdOn')->textInput() ?>

    <?= $form->field($model, 'modifiedOn')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => 15]) ?>
    
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
     
     
    <?php //*/?>
</div>
