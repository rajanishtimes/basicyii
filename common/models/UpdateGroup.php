<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\UserGroup;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class UpdateGroup extends User
{
    private $groupMap;
    /**
     * @inheritdoc
     */
    
    
    public function rules()
    {
        return [
            [['groupMap','Id'], 'required'],
            ['groupMap', 'safe'],
        ];
    }
    
    
    public function setGroupMap($value){        
        $this->groupMap = $value;
    }




    public function getGroupMap() {
        if(isset($this->groupMap)){            
            return $this->groupMap;
        }
        
        $data = [];
        foreach($this->mapedGroups as $groups){
            $data[] = $groups->groupId;
        } 
        //print_r($data);
        return $data;
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [            
            'Id' => Yii::t('app', 'User ID'),
            'groupMap' => Yii::t('app', 'User\'s Group'),
        ];
    }
    
    public function saveAll() {        
        $__models = UserGroup::findAll(['userId' => $this->Id]);
            
        if(is_array($__models)){
            $groups = $this->groupMap;
            foreach($__models as $index=>$model){
                if(isset($groups[$index])){
                    $model->groupId = $groups[$index];
                    $flag = $model->save();
                    unset($groups[$index]);
                }else{
                    $model->delete();
                }
                if(!$flag) break;
            }

            foreach($groups as $groupId){ //new insert
                $model = new UserGroup;
                $model->groupId = $groupId;
                $model->userId = $this->Id;
                $flag = $model->save();
                if(!$flag) break;
            }
        }else{      //new insertion
            foreach($this->groupMap as $groupId){
                $model = new UserGroup;
                $model->groupId = $groupId;
                $model->userId = $this->Id;
                $flag = $model->save();

                if(!$flag) break;
            }
        }
        
        return $flag;
    }
}
