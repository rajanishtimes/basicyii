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

class CropMedia extends Widget
{
    public $form;


    public $view;
    /**
     * @var ActiveRecord ar model
     */
    public $model;
    
    private $params=[];
    
    public $data;
    public $previewurl;
    public $path;
    
    public $js;
    public $css;
    
    /**
     * Initializes the widget.
     */
    public function init()
    {
        $this->js = [
            '../assets/js/cropper.min.js'
        ];
        $this->css = [
            '../assets/css/cropper.min.css'
        ];
        parent::init();        
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $view = $this->getView();
        $view->registerJsFile('../assets/js/cropper.min.js');
        $view->registerCss('../assets/css/cropper.min.css');
        $this->render('crop_box');
    }
}
