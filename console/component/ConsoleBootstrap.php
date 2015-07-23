<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace console\component;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\web\Controller;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use common\component\AppActiveRecord;
use common\component\TcEventAction;
define(_ENV_, yii::$app->params['env']);



class ConsoleBootstrap implements BootstrapInterface{
    public function bootstrap($app){
        
        Yii::$classMap['taskmanager\TaskManager'] = Yii::getAlias('@taskmanager/TaskManager.php');
        Yii::$classMap['cdn_service\WhImage'] = Yii::getAlias('@cdnservice/WhImage.php');
        
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
        
    }
}
