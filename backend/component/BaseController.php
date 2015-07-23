<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\component;

use Yii;
use yii\web\Controller;
use yii\metadata\Metadata as Metadata;


class BaseController extends Controller{
    public $sitemenu = [];
    public function init()
    {
        parent::init();
        //$meta = new Metadata();
        //print_r($meta->getAll());
    }
    
    public function registerMenu(){
        
    }
}
?>