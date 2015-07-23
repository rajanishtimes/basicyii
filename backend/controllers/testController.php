<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\component\BaseController;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class TestController extends BaseController
{
   
    public function actionsTest()
    {
        
        die('test');
        //$this->render('test');
    }

   
}
