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
        
        Event::on(ActiveRecord::classname(),  AppActiveRecord::EVENT_AFTER_TRANCINSERT,function ($event){
            TcEventAction::EventActionAfterTrancInsert($event);
             
        });
        
        
        
        
        Event::on(ActiveRecord::classname(),  AppActiveRecord::EVENT_AFTER_TRANCUPDATE,function ($event){
            TcEventAction::EventActionAfterTrancUpdate($event);
             
        });
        
        Event::on(ActiveRecord::classname(),BaseActiveRecord::EVENT_AFTER_INSERT,function ($event){
            TcEventAction::EventActionCreate($event);
             
        }); 

        Event::on(ActiveRecord::classname(),BaseActiveRecord::EVENT_AFTER_UPDATE,function ($event){
            TcEventAction::EventActionUpdate($event);
        }); 
        
        Event::on(ActiveRecord::classname(),AppActiveRecord::EVENT_BEFORE_PUBLISH,function ($event){
            TcEventAction::EventActionBeforePublish($event);
             
        }); 
        
        Event::on(ActiveRecord::classname(),AppActiveRecord::EVENT_AFTER_PUBLISH,function ($event){
            TcEventAction::EventActionAfterPublish($event);
             
        }); 
        
        
        Event::on(AppActiveRecord::classname(),AppActiveRecord::EVENT_AFTER_SOFTDELETE,function ($event){
            TcEventAction::EventActionSoftDelete($event);
        });
   
       Event::on(AppActiveRecord::classname(),AppActiveRecord::EVENT_AFTER_UNPUBLISH,function ($event){
            TcEventAction::EventActionUnpublish($event);
       });
       
       
       //for set headers
       Event::on(\yii\web\Response::className(),  \yii\web\Response::EVENT_BEFORE_SEND,function($event){
            $headers = $event->sender->headers;
            $headers->add('X-Frame-Options', 'SAMEORIGIN');
       });
        
    }
}
