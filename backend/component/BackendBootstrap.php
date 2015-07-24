<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace backend\component;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\web\Controller;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use common\component\AppActiveRecord;
use common\component\TcEventAction;
use common\models\LoginHistory;



class BackendBootstrap implements BootstrapInterface{
    public function bootstrap($app){
        
        
        
        /*
         * logging users login history
         */
        Event::on('\yii\web\User', \yii\web\User::EVENT_AFTER_LOGIN,function ($event){
//            Yii::info('in after login event handler','login');
//             $logHistory=new LoginHistory();
//            $logHistory->user_id=\Yii::$app->user->identity->id;
//            $logHistory->login_time=date("Y-m-d H:i:s");
//              $logHistory->save(false);
             
        });
        
        
        
    }
}
