<?php

/* 
 * @author Mukesh Soni <mukesh.soni@timesinternet.com>
 * @country India
 */

namespace yii\adminUi\widget;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class SearchForm extends Widget
{
    public $form;
    
    public $view;
    /**
     * @var ActiveRecord ar model
     */
    public $model;
    
    public $fields=[];
    public $data;
    public $js;
    public $css;
    public $config = [];
    /**
     * Initializes the widget.
     */
    public function init()
    {
        $default_url = '/'.Yii::$app->controller->uniqueId.'/index';
        $config = ['form_title'=>'Search','action'=>$default_url,'default_open'=>false,'filter_label'=>'Filter By:'];
        $this->config = array_merge($config,$this->config);
        /*
        $this->js = [
            '../assets/js/cropper.min.js'
        ];
        $this->css = [
            '../assets/css/cropper.min.css'
        ];*/
        parent::init();        
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $view = $this->getView();
        
        /*$view->registerJsFile('../assets/js/cropper.min.js');
        $view->registerCss('../assets/css/cropper.min.css');
        $this->render('crop_box');*/
        return $this->render('_search_frm',['config'=>$this->config,'fields'=>$this->fields,'model'=>$this->model,'view'=>$this->view]);
    }
}
