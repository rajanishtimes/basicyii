<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\adminUi\widget;

use Yii;
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\web\JsExpression;
use yii\helpers\Json;
use yii\adminUi\assetsBundle\AdminDataTableAsset;

/*
 * @author : Mueksh Soni
 * @description :
 * Used to genrate data tables
 */

class DataTable extends GridView
{
    public $tableDataClass = 'datatable';
    public $tableOptions   = ['class' => 'table table-striped table-bordered'];
    public $summary = '';
    
    public $datatable_options =[];
    
    /**
     * Initializes the grid view.
     * This method will initialize required property values and instantiate [[columns]] objects.
     */
    public function init()
    {
        $this->tableOptions['class'] .= ' '.$this->tableDataClass;
        parent::init();
        
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        parent::run();
        $view = $this->getView();
        AdminDataTableAsset::register($view);
        $id = $this->options['id'];
        $view->registerJs('var extensions ='.Json::encode($this->datatable_options).' ;',View::POS_READY);
        $view->registerJs('$.extend($.fn.dataTableExt.oStdClasses, extensions);',View::POS_READY);
        $view->registerJs('var '.$id.' = $(".'.$this->tableDataClass.'").dataTable();',View::POS_READY);
    }

}
