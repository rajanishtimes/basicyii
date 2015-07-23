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
class WorkflowButtonsView extends Widget
{
    
    public $view;
    /**
     * @var ActiveRecord ar model
     */
    public $model;
    
    
    public $unpublishbtn = true;
    public $unpublishbtntxt = 'UnPublish';
    
    public $deletebtn = true;
    public $editbtn = true;
    public $state = true;
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
        $modal = [];
        if($this->editbtn && \yii::$app->user->can('update')){
            echo Html::a('Edit', ['update', 'id' => $this->model->id], ['class' => 'btn btn-primary']);        
        }
        
        
        if($this->state && $this->model->state != AppActiveRecord::STATUS_DRAFT){
            
            //echo $this->model->state;

            if($this->model->state != AppActiveRecord::STATUS_PUBLISH && $this->model->state != AppActiveRecord::STATUS_SOURCED && \yii::$app->user->can('publish')){
                echo '&nbsp;&nbsp;';
                echo Html::a('Publish', ['publish', 'id' => $this->model->id],[
                    'class' => 'btn btn-success',
                    'data-method' => 'post',
                    'title' => Yii::t('yii', 'Publish'),
                    'data-pjax' => '0',
                    ]) ;
            }elseif(($this->model->state != AppActiveRecord::STATUS_PUBLISHREADY && $this->model->state != AppActiveRecord::STATUS_SOURCED && $this->model->state != AppActiveRecord::STATUS_PUBLISH) && \yii::$app->user->can('sendtopublish')){
                echo '&nbsp;&nbsp;';
                echo Html::a('Send to Publisher', ['sendtopublish', 'id' => $this->model->id],[
                    'class' => 'btn btn-success',
                    'data-method' => 'post',
                    'title' => Yii::t('yii', 'Send to Publisher'),
                    'data-pjax' => '0',
                    ]) ;
            }
            
            
            if($this->unpublishbtn && $this->model->state == AppActiveRecord::STATUS_PUBLISH && \yii::$app->user->can('unpublish')){
                echo '&nbsp;&nbsp;';
                $uppublishedmodal = 'modal-unpub';
                $modal[] = [
                                'id'=>$uppublishedmodal,
                                'header' => '<h4>Unpublish '.strtolower($this->model->ClassSortNeme()).': '.$this->model->name.'</h4>',
                            ];
                echo Html::button($this->unpublishbtntxt,[
                    'class' => 'btn btn-warning',                    
                    'title' => Yii::t('yii',$this->unpublishbtntxt),
                    'data-toggle' =>  'modal',
                    'data-target' => '#'.$uppublishedmodal,
                    'data-remote' =>  Url::toRoute(['unpublish', 'id' => $this->model->id])
                    ]);
            }
            
            if($this->closebtn && $this->model->state == AppActiveRecord::STATUS_PUBLISH && $this->model->status == AppActiveRecord::STATUS_PUBLISH && \yii::$app->user->can('updatestatus')){
                echo '&nbsp;&nbsp;';
                $uppublishedmodal = 'modal-updatestatus';
                $modal[] = [
                                'id'=>$uppublishedmodal,
                                'header' => '<h4>'.$this->closebtntxt.' '.strtolower($this->model->ClassSortNeme()).': '.$this->model->name.'</h4>',
                            ];
                echo Html::button($this->closebtntxt,[
                    'class' => 'btn btn-warning',
                    'title' => Yii::t('yii', $this->closebtntxt),
                    'data-toggle' =>  'modal',
                    'data-target' => '#'.$uppublishedmodal,
                    'data-remote' =>  Url::toRoute(['updatestatus', 'id' => $this->model->id])
                    ]);
            }
            
            if($this->deletebtn && \yii::$app->user->can('delete')){
                echo '&nbsp;&nbsp;';
                
                /*
                echo Html::a('Delete', ['delete', 'id' =>$this->model->Id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]);
                //*/
                
                $deletemodal = 'modal-trash';
                $modal[] = [
                                'id'=>$deletemodal,
                                'header' => '<h4>Delete '.strtolower($this->model->ClassSortNeme()).': '.$this->model->name.'</h4>',
                            ];
                echo Html::button($this->deletebtntxt,[
                    'class' => 'btn btn-danger',
                    'title' => Yii::t('yii', 'Unpublish'),
                    'data-toggle' =>  'modal',
                    'data-target' => '#'.$deletemodal,
                    'data-remote' =>  Url::toRoute(['delete', 'id' => $this->model->id])
                    ]);
            }
            
        
        }else{
			if($this->deletebtn){
            echo '&nbsp;&nbsp;';
            echo Html::a('Delete', ['delete', 'id' =>$this->model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]);
			}
        }
        
        echo '&nbsp;&nbsp;';
        echo Html::button('Back', ['class' =>'btn btn-default','onclick' => 'history.go(-1);return true;']);
        
        echo "\n" . Html::endTag('div');
        
        foreach($modal as $bs_modal){
            echo Modal::widget($bs_modal);
        }
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        Html::addCssClass($this->options, 'form-group');        
    }
}
