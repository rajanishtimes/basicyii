<?php

/* 
 * @author Mithun Mandal <mithun12000@gmail.com>
 * @project AdminUi
 * @projecturl https://github.com/mithun12000/adminUI
 * @country India
 */

namespace yii\adminUi\widget;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use common\component\AppActiveRecord;

/**
 * Callout renders an callout bootstrap element.
 *
 * For example,
 * 
 *
 * ```php
 * WorkflowButtons::widget([
 *     'options' => [
 *         'class' => 'alert-warning',
 *     ],
 *     'view' => $this,
 *     'model' => $model,
 * ]);
 * 
 * ```
 *
 * 
 * @author Mithun Mandal <mithun12000@gmail.com>
 * @since 2.0
 */
class WorkflowButtons extends Widget
{
    public $form;


    public $view;
    /**
     * @var ActiveRecord ar model
     */
    public $model;
    
    private $params=[];
    
    public $unpublishbtn = true;
    public $unpublishbtntxt = 'UnPublish';
    
    public $deletebtn = true;
    public $deletebtntxt = 'Delete';
    
    public $closebtn = true;
    public $closebtntxt = 'Cancel';    
    
    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        $this->initOptions();

        echo Html::beginTag('div', $this->options) . "\n";
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        //echo $this->model->state;
        //for new record creation
        $modal = [];
        $_state = $this->model->state;
        if($this->model->isNewRecord || $_state == AppActiveRecord::STATUS_DRAFT){
            if(\yii::$app->user->can('saveasdraft')){
                echo '&nbsp;&nbsp;';
                AjaxSubmitButton::begin([
                    'label' => 'Save as Draft',
                    'options' => [
                        'id' => 'saveasdraft-btn',
                        'class' => 'btn btn-lg btn-warning',
                        'type'  => 'button',
                    ],
                    'ajaxOptions' => [
                        'type'  =>'POST',
                        'url'   => Url::toRoute(array_merge(['saveasdraft'],$this->params)),
                        /*'cache' => false,*/
                        'success' => new \yii\web\JsExpression('function(data){                        
                            if(data.status){
                                location.href = "'.Url::toRoute(array_merge(['index'])).'";
                            }
                         }'),
                    ],
                ]);
                AjaxSubmitButton::end();
                $this->view->registerJs("$( '#saveasdraft-btn' ).click(function() {
                $(this).addClass('disabled');
            });");
            }
        }
        
        if(\yii::$app->user->can('create') || \yii::$app->user->can('update')){
            echo '&nbsp;&nbsp;';
            echo Html::submitButton('Save', [
                'class' => 'btn btn-lg btn-info aftervalidate'
                ]);
        }
        
        if(
                !$this->model->isNewRecord && $_state != AppActiveRecord::STATUS_DRAFT && $_state != AppActiveRecord::STATUS_SOURCED
                && 
                ($_state != AppActiveRecord::STATUS_PUBLISH || $_state != AppActiveRecord::STATUS_PUBLISHREADY))
            {
                //not in draft, not new, not publish, not ready to ready, can be draft update or sourced
                
                if($_state != AppActiveRecord::STATUS_PUBLISH &&  \yii::$app->user->can('publish'))
                {                
                    //not in draft, not new, not publish, not ready to ready
                    echo '&nbsp;&nbsp;';
                    echo Html::submitButton('Publish', [
                        'class' => 'btn btn-lg changeaction btn-success aftervalidate',
                        'data-url'=> Url::toRoute(array_merge(['publish'],$this->params)),
                        ]) ;
                }
                elseif (
                        ($_state != AppActiveRecord::STATUS_PUBLISHREADY && $_state != AppActiveRecord::STATUS_PUBLISH) 
                        && \yii::$app->user->can('sendtopublish')) 
                {
                    echo '&nbsp;&nbsp;';
                    echo Html::submitButton('Send to Publisher', [
                        'class' => 'btn btn-lg changeaction btn-success aftervalidate',
                        'data-url'=> Url::toRoute(array_merge(['sendtopublish'],$this->params)),
                        ]) ;
                }
                
            }
        if($this->deletebtn && \yii::$app->user->can('delete') && !$this->model->isNewRecord){
                echo '&nbsp;&nbsp;';
                $deletemodal = 'modal-trash';
                $modal[] = [
                                'id'=>$deletemodal,
                                'header' => '<h4>Delete '.strtolower($this->model->ClassSortNeme()).': '.$this->model->name.'</h4>',
                            ];
                echo Html::button($this->deletebtntxt,[
                    'class' => 'btn btn-danger btn-lg',
                    'title' => Yii::t('yii', 'Unpublish'),
                    'data-toggle' =>  'modal',
                    'data-target' => '#'.$deletemodal,
                    'data-remote' =>  Url::toRoute(['delete', 'id' => $this->model->id])
                    ]);
        }
        
        if($this->unpublishbtn && $this->model->state == AppActiveRecord::STATUS_PUBLISH && \yii::$app->user->can('unpublish')){
            echo '&nbsp;&nbsp;';
            $uppublishedmodal = 'modal-unpub';
            $modal[] = [
                            'id'=>$uppublishedmodal,
                            'header' => '<h4>Unpublish '.strtolower($this->model->ClassSortNeme()).': '.$this->model->name.'</h4>',
                        ];
            echo Html::button($this->unpublishbtntxt,[
                'class' => 'btn btn-warning btn-lg',                    
                'title' => Yii::t('yii',$this->unpublishbtntxt),
                'data-toggle' =>  'modal',
                'data-target' => '#'.$uppublishedmodal,
                'data-remote' =>  Url::toRoute(['unpublish', 'id' => $this->model->id])
                ]);
        }
            

        echo '&nbsp;&nbsp;';
        echo Html::resetButton('Reset', ['class' =>'btn btn-lg btn-default']);
        echo '&nbsp;&nbsp;';
        echo Html::resetButton('Back', ['class' =>'btn btn-lg btn-default','onclick' => 'history.go(-1);return true;']);
        
        echo "\n" . Html::endTag('div');
        
        foreach($modal as $bs_modal){
            echo Modal::widget($bs_modal);
        }
        
        $this->view->registerJs("
                    var _frm = $('.changeaction').first().closest('form');
                    var _frmdata = null,alreadySubmitted = false;
                    if(_frm != null && _frm !=undefined){
                        _frmdata = _frm.serialize();
                    }
        ");
        
        $this->view->registerJs("$('.changeaction').click(function(e) {
                    e.preventDefault();
                    if(typeof $(this).attr('data-url') != 'undefined' && $(this).attr('data-url') != ''){
                        btnType = $(this).text();
                        frm = $(this).parents('form');
                        if(btnType == 'Publish'){
                            if(_frmdata != null && _frmdata != frm.serialize()){
                                alert('Please save changes before going to publish it');
                                return false;
                            }
                        }
                        if(alreadySubmitted === false){
                            $(this).parents('form').attr('action',$(this).attr('data-url')).submit();
                            aalreadySubmitted = true;
                        }
                    }
                });");
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        Html::addCssClass($this->options, 'form-group');
        if(!$this->model->isNewRecord){
            $this->params['id'] = $this->model->id;
        }
    }
}
