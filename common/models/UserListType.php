<?php

namespace common\models;

use Yii;
use yii\helpers\Json;
use common\component\AppActiveRecord;

/**
 * This is the model class for table "tc_user_list_type".
 *
 * @property integer $Id
 * @property integer $userType
 * @property integer $userId
 * @property string $reason
 * @property string $createdOn
 * @property string $updatedOn
 * @property integer $createdBy
 * @property integer $updatedBy
 * @property integer $STATUS
 * @property string $ip
 */
class UserListType extends AppActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tc_user_list_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userType', 'reason', 'userId'], 'required'],
            [['userType', 'userId', 'createdBy', 'updatedBy', 'status'], 'integer'],
            [['userId'], 'unique'],
            //[['reason'], 'string'],
            [['reason'], 'string', 'min'=>5,'max' => 200], 
            [['createdOn', 'updatedOn'], 'safe'],
            [['ip'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'userType' => 'User Type',
            'userId' => 'User ID',
            'userTypeData'=>'User Type',
            'reason' => 'Reason',
            'createdOn' => 'Created On',
            'updatedOn' => 'Updated On',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'status' => 'Status',
            'ip' => 'Ip',
        ];
    }
    
   
     public function fields() {        
        return [
            'Id',
            'userType',
            'userTypeData',
            'userId',
            'reason',
            'createdOn',
            'updatedOn',
            'createdBy',
            'updatedBy',
            'status',
            'ip',
        ];
    }
    
    
    
    
    public function getUserTypeData()
    {
        $type='';
        
        if($this->userType==1)
        {
            $type='Black Listed User';
            
        }
        
        if($this->userType==2)
        {
            $type='White Listed User';
            
        }
        return $type;
    }      
    
    
}
