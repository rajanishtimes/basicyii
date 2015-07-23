<?php

/* 
 * Coder: Mithun Mandal
 * Contact: 01206636679/8527580960
 * Project: Timescity CMS Using Yii2
 */

namespace common\component;

use yii\base\InvalidCallException;
use yii\base\InvalidParamException;
use Yii;


class PermissionManager extends \yii\rbac\PhpManager{
    /*
     * adding common functions for access controll;
     */
    use \common\models\PermissionTrait;
    
    /**
     *
     * @var Array accessrules to store all called access list. 
     */
    private $accessrules = [];

    public function init()
    {
        
    }

        /**
     * @inheritdoc
     */
    public function checkAccess($userId, $permissionName, $params = [])
    {
        $user = Yii::$app->getUser();
        $userIdentity = $user->getIdentity();
        
        if(isset($this->accessrules[$permissionName])){
            return $this->accessrules[$permissionName];
        }
        
        if($userIdentity !== null){
            $userIdentity->getRules();
            
            $permission_data = $this->checkpermission($permissionName);
            //\yii::trace(print_r($permission_data,true));
            $this->accessrules[$permissionName] = $userIdentity->checkAccess($permission_data['module'],$permission_data['controller'],$permission_data['action']);
            //\yii::trace('ACL name:'.$permissionName);
            //\yii::trace('ACL passed:'.(int) $this->accessrules[$permissionName]);
            return $this->accessrules[$permissionName];
        }
        return false;
    }
    
    
    /**
     * example:
     * $permissionName can be 
     * action
     * controller/action
     * module/controller/action
     * @param string $permissionName Permisssion Name
     * @return array Array of formated Data
     */
    protected function checkpermission($permissionName) {        
        $return = [];
        $pm_list = explode('/',$permissionName);        
        if(is_array($pm_list)){
            $count = count($pm_list);
            switch($count){
                case 1:     
                            if($permissionName == \Yii::$app->controller->module->id){
                                $return = ['module' => \Yii::$app->controller->module->id];
                            }else{                            
                                $return = ['module' => \Yii::$app->controller->module->id,'controller'=> \Yii::$app->controller->id,'action'=> $permissionName];
                            }
                            break;
                case 2:
                            $return = ['module' => \Yii::$app->controller->module->id,'controller'=> $pm_list[0],'action'=> $pm_list[1]];
                            break;
                case 3:
                            $controller = $pm_list[1];
                            $module = $pm_list[0];
                            $action = $pm_list[2];
                            $return = ['module' => $pm_list[0],'controller'=> $pm_list[1],'action'=> $pm_list[2]];
                            break;
            }     
            //\yii::trace(print_r($return,true));
        }else{
            $controller = \Yii::$app->controller;
            $module = $controller->module->id;
            $action = $permissionName;
            $return = ['module' => $module,'controller'=> $controller,'action'=> $action,];
        }
        return $return;
    }
}