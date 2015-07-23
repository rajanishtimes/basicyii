<?php
namespace common\models;

use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use common\models\Permission;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\metadata\component\MetaData;


trait PermissionTrait {
    private $permissionDP;
    
    private $usergroupsDP;


    protected $permission_rules = []; // ruleName => rule
    
    /*
     * @return Object|yii\data\ActiveDataProvider data provided for all permission tables
     */
    public function getAllRules() {
        if($this->permissionDP){
            return $this->permissionDP;
        }else{
            if($this->hasAttribute('groupId')){
                $Parentgroups = $this->getParentGroups($this->groupId);
                $usergroups = $this->getUsersGroupArray();
                foreach($usergroups as $group){
                    $Parentgroups = array_merge($Parentgroups,$this->getParentGroups($group));
                }
                //print_r($usergroups);
                $Parentgroups = array_unique($Parentgroups);
                return $this->permissionDP = $this->permissiondataprovider($Parentgroups,$userId=$this->Id);
            }else{
                $Parentgroups = $this->getParentGroups();
                //\yii::trace(print_r($Parentgroups,true), __METHOD__);
                return $this->permissionDP = $this->permissiondataprovider($Parentgroups);
            }
        }
    }
    
    /*
     * @param integer $groupId group Id of User or it will select current group object Primary key
     * @return array of groups.
     */
    private function getParentGroups($groupId = '') {
        $groups = $_dataMap =  [
                    ];
        
        if(!$groupId){
            $groupId = $this->Id;
        }
        
        $_dataMap = ArrayHelper::map(Group::find()
                             ->where(['status' => '1'])
                             ->andWhere('Id <='.$groupId)
                             ->all(),'Id','parentId');
        
        
        $groups[] = $key = $groupId;
        
        while($key!=0){
            if(isset($_dataMap[$key]) && $_dataMap[$key]!=0){
                $groups[] = $key = $_dataMap[$key];
            }else{
                $key = 0;
            }
        };
        return $groups;
    }
    
    /*
     * @param array $groups list of groups
     * @param integer $userId user Id of User
     * @return Object|yii\data\ActiveDataProvider data provided for all permission tables
     */
    private function permissiondataprovider($groups,$userId = 0) {
        if($userId){
            return new ActiveDataProvider([
                'query' => Permission::find()
                                ->where(['groupId' =>$groups,'status'=>1])
                                ->orWhere(['userId' =>$userId]),
                'pagination' => [
                                    'pageSize' => -1,   //for no limit
                                ],
            ]);
        }else{
            return new ActiveDataProvider([
                'query' => Permission::find()->where(['groupId' =>$groups,'status'=>1]),
                'pagination' => [
                    'pageSize' => -1,   //for no limit
                ],
            ]);
        }
    }
    
    public function getRules(){
        $adp = $this->getAllRules();
        $this->permission_rules = []; 
        $this->permission_rules['*##*##*'] = 0;
        
        foreach($adp->getModels() as $rec){
            $key = $rec->module.'##'.$rec->controller.'##'.$rec->action;
            $this->permission_rules[$key] = $rec->type;            
        }
    }
    
    public function getPermissions(){
        $adp = $this->getAllRules();
        $this->permission_rules = []; 
        $this->permission_rules['*##*##*'] = 0;
        
        foreach($adp->getModels() as $rec){
            $key = $rec->module.'##'.$rec->controller.'##'.$rec->action;
            $this->permission_rules[$key] = $rec->type;            
        }
        //\yii::trace(print_r($this->permission_rules,true), __METHOD__);
        return $this->getPermissionList();
    }
    
    private function getPermissionList(){        
        $meta = new MetaData();
        $allModule = $meta->getRouteMap();
        unset($meta);     
        Yii::beginProfile(__CLASS__.__METHOD__);
        $Ar_dataModel = [];
        //\yii::trace('Module Permission:'.__LINE__.'check rules'.print_r($this->permission_rules,true),__METHOD__);
        foreach($allModule as $moduleName=>$controllerMap){
            if($this->checkAccess($moduleName)){  
                //\yii::trace('Module Permission:'.__LINE__.'check rules permission for '.$moduleName,__METHOD__);
                //\yii::trace('Module Permission:'.__LINE__.'check rules permission for '.print_r($controllerMap,true),__METHOD__);
                foreach($controllerMap as $controllerName=>$actionMap){                    
                    if($this->checkAccess($moduleName,$controllerName)){
                        //\yii::trace('Module Permission:'.__LINE__.'check rules permission for '.$moduleName.' and '.$controllerName,__METHOD__);
                        $map=[];
                        foreach($actionMap as $action){
                            if($this->checkAccess($moduleName,$controllerName,$action)){
                                //\yii::trace('Module Permission:'.__LINE__.'check rules permission for '.$moduleName.' and '.$controllerName.' and '.$action,__METHOD__);
                               $map[]=  ucfirst($action);
                            }
                        }
                        if(count($map)){
                            $Ar_dataModel[] = [
                                                ucfirst($moduleName),
                                                ucfirst($controllerName),
                                                implode(', ',$map),
                                                ]; 
                        }
                    }else{
                        //No Controller Access
                        \yii::warning('Module Permission:'.__LINE__.'check rules no permission for '.$moduleName.'|'.$controllerName.'|',__METHOD__);
                        continue;
                    }
                }
            }else{
                //No module Access then continue
                \yii::warning('Module Permission:'.__LINE__.'check rules no permission for '.$moduleName,__METHOD__);
                continue;
            }
        }
        \yii::warning('Module Permission:'.__LINE__.'check rules no permission for '.print_r($Ar_dataModel,true),__METHOD__);
        Yii::endProfile(__CLASS__.__METHOD__);
        return new ArrayDataProvider([
                    'allModels'=>$Ar_dataModel,
                    'pagination' => false,                    
                ]);
    }
    
    public function checkAccess($module,$controller='',$actions='') {
        if($module && $controller && $actions){
            return $this->checkActionAccess($module,$controller,$actions);
        }else if($module && $controller){
            return $this->checkControllerAccess($module,$controller);
        }else{
            return $this->checkModuleAccess($module);
        }
        return false;
    }
    
    private function checkActionAccess($module,$controller,$actions) {        
        $permission = false;
        if(isset($this->permission_rules['*##*##*']) ||
                isset($this->permission_rules[$module.'##*##*']) ||
                isset($this->permission_rules['*##'.$controller.'##*']) ||
                isset($this->permission_rules[$module.'##'.$controller.'##*']) ||
                isset($this->permission_rules['*##*##'.$actions])
                ){
            if($this->permission_rules['*##*##*']==1 || 
                    $this->permission_rules[$module.'##*##*'] == 1 ||
                    $this->permission_rules[$module.'##'.$controller.'##*'] == 1 ||
                    $this->permission_rules['*##'.$controller.'##*'] ==1 ||
                    $this->permission_rules['*##*##'.$actions] == 1
                    ) 
                $permission = true;
        }
        //\yii::trace('Module Permission for '.$module.'|'.$controller.'|'.$actions.':'.(int)$permission,__METHOD__);
        $controller_rule = [];
        $action_rule = [];
        foreach($this->permission_rules as $rules=>$status){
            list($moduleName,$controllerName,$actionName) = explode('##',$rules);            
            if(($moduleName==$module || $moduleName== '*') && ($controllerName == $controller || $controllerName == '*') && $actionName == $actions){
                if($status == 1){
                    $permission = true;
                }else{
                    $permission = false;
                }
            }elseif(($moduleName==$module || $moduleName== '*')&& ($controllerName == $controller || $controllerName == '*') && ($actionName == $actions || $actionName == '*')){
                if($status == 1){
                    $permission = true;
                }else{
                    $permission = false;
                }
            }elseif($moduleName==$module){
                // && ($controllerName == $controller || $controllerName == '*') && $actionName == $actions                
                if($status == 1){
                    //$permission = true;
                    $controller_rule[$controllerName] = 1;
                    $action_rule[$actionName] = 1;
                }else{
                    //$permission = false;
                    $controller_rule[$controllerName] = 0;
                    $action_rule[$actionName] = 0;
                }
            }
            \yii::trace('Module Permission for '.$module.'|'.$controller.'|'.$actions.':'.(int)$permission,__METHOD__);
        }
        //\yii::trace('Module Permission for '.$module.'|'.$controller.'|'.$actions.':'.var_dump($permission),__METHOD__);
        return $permission;
    }
    
    private function checkControllerAccess($module,$controller) {
        $permission = false;
        if(isset($this->permission_rules['*##*##*']) || 
        isset($this->permission_rules['*##'.$controller.'##*']) ||
        isset($this->permission_rules[$module.'##'.$controller.'##*']) ||
        isset($this->permission_rules[$module.'##*##*'])){
            
            if($this->permission_rules['*##*##*']==1 || 
                $this->permission_rules['*##'.$controller.'##*'] == 1 || 
                $this->permission_rules[$module.'##'.$controller.'##*'] == 1 ||
                $this->permission_rules[$module.'##*##*'] == 1
             ){ 
                $permission = true;
             }
        }
        //\yii::trace('Module Permission for '.$module.'\\'.$controller.':'.var_dump($permission),__METHOD__);
        foreach($this->permission_rules as $rules=>$status){
            list($moduleName,$controllerName,$actionName) = explode('##',$rules);
            if((($moduleName==$module && $controllerName == $controller)|| 
                ($moduleName==$module && $controllerName == "*")|| 
                    
               ($moduleName=='*' && $controllerName == $controller)|| 
               ($moduleName=='*' && $controllerName == '*'))){
                if($status == 1){
                    $permission = true;
                }
            }
        }
        //\yii::trace('Module Permission for '.$module.'\\'.$controller.':'.var_dump($permission),__METHOD__);
        return $permission;
    }
    
    private function checkModuleAccess($module) {
        $permission = false;
        if(isset($this->permission_rules['*##*##*']) || isset($this->permission_rules[$module.'##*##*'])){
            if($this->permission_rules['*##*##*']==1 || $this->permission_rules[$module.'##*##*'] == 1) $permission = true;
            //\yii::trace('Module Permission:'.__LINE__.'check'.$permission,'app.permissionTrait');
        }
        foreach($this->permission_rules as $rules=>$status){           
            list($moduleName,$controllerName,$actionName) = explode('##',$rules);
            if(($moduleName == $module || $moduleName == '*') && !$permission){
                $permission = true;
            }
            //\yii::trace('Module Permission:'.__LINE__.'check'.$permission,'app.permissionTrait');
        }
        //\yii::trace('Module Permission for '.$module.':'.(bool)$permission,'app.permissionTrait');
        return $permission;
    }
    
    public function getUsersGroupArray() {
        if(!$this->usergroupsDP){
            $this->getUsersGroup();
        }
        
        $groups = [];
        if($this->usergroupsDP->getCount()>0){
            foreach($this->usergroupsDP->getModels() as $model){
                $groups[] = $model->groupId;
            }
        }        
        return $groups;
    }
    
    public function getUsersGroup() {
        return $this->usergroupsDP = new ActiveDataProvider([
                'query' => UserGroup::find()->where(['userId' => $this->Id]),
            ]);        
    }
}