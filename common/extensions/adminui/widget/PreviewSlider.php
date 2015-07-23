<?php

/* 
 * @author Anshu <anshu@timesinternet.com>
 * @country India
 */

namespace yii\adminUi\widget;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class PreviewSlider extends Widget
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
        $config = ['title'=>'widget'];
        $this->config = array_merge($config,$this->config);
        
		
        parent::init();        
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $view = $this->getView();
        return $this->render('_preview',['config'=>$this->config,'fields'=>$this->fields,'model'=>$this->model,'view'=>$this->view]);
    }
}
