<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;

/**
 * @var yii\web\View $this
 * @var common\models\Permission $model
 * @var yii\widgets\ActiveForm $form
 */
//echo $userId;
?>
<div class="permission-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'status',['template'=>'{input}','options'=>['class'=>'']])->hiddenInput(['value' => 1])->label('') ?>
    
    <?= $form->field($model, 'userId',['template'=>'{input}','options'=>['class'=>'']])->hiddenInput(['value' => $userId])->label('') ?>
    
    <?= $form->field($model, 'type')->radioList([1=>'Grant',0=>'Restrict'],[
                                                'class'=>'btn-group',
                                                'data-toggle'=>'buttons',
                                                'item'  => function($index, $label, $name, $checked, $value){
                                                                $class = ['btn'];
                                                                if($value==1){
                                                                    $class[] = 'btn-success';
                                                                }else{
                                                                    $class[] = 'btn-danger';
                                                                }
                                                                $inputoption = [
                                                                    'type'  => 'radio',
                                                                    'name'  => $name,
                                                                    'value' => $value,
                                                                    'class' => 'noicheck',
                                                                ];
                                                                if($checked){
                                                                    $class[] = 'active';
                                                                    $inputoption['checked'] = true;
                                                                }
                                                                $labeloption = ['class'=> implode(' ', $class)];
        
                                                    return Html::tag('div',Html::tag('input','',$inputoption).' '.$label,$labeloption);
                                                },
                                                'itemOptions'=>[
                                                    'container'=>[
                                                        'class'=>'btn btn-primary'
                                                        ],
                                                    'class'=>'noicheck'
                                                    ]
                                    ]) ?>

    <?= $form->field($model, 'module')->dropDownList(ArrayHelper::merge([''=>'Select Module'], $modulesmap->getModuleList()), ['id'=>'module']) ?>

    
    
    <?= $form->field($model, 'controller')->widget(DepDrop::classname(), [
                                                                            'options'=>['id'=>'controller'],
                                                                            'pluginOptions'=>[
                                                                                'depends'=>['module'],
                                                                                'placeholder'=>'Select Controller',
                                                                                'url'=>Url::to(['/metadata/default/controllers'])
                                                                            ]
                                                                          ])?>
    

    
    <?= $form->field($model, 'action')->widget(DepDrop::classname(), [
                                                                            'options'=>['id'=>'actions'],
                                                                            'pluginOptions'=>[
                                                                                'depends'=>['module','controller'],
                                                                                'placeholder'=>'Select Action',
                                                                                'url'=>Url::to(['/metadata/default/actions'])
                                                                            ]
                                                                          ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
