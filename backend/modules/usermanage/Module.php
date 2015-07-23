<?php
namespace backend\modules\usermanage;
use yii\web\Controller;
use yii\base\Event;
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\usermanage\controllers';
    
    public $defaultRoute = 'group';
    
    public function init()
    {
        parent::init();
        
        Event::on(Controller::className(), Controller::EVENT_BEFORE_ACTION, function ($event) {
            $event->sender->view->params['pagelabel'] = 'User Management System';
        });
    }
}
