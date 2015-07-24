<?php

namespace common\models;

use Yii;
use common\component\AppActiveRecord;

/**
 * This is the model class for table "tc_permissions".
 *
 * @property integer $Id
 * @property integer $userId
 * @property integer $groupId
 * @property string $module
 * @property string $controller
 * @property string $action
 * @property string $createdOn
 * @property integer $createdBy
 * @property string $updatedOn
 * @property integer $updatedBy
 * @property integer $status
 * @property integer $type
 * @property Group $group
 * @property User $user
 */
class Permission extends AppActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%permissions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'type'], 'required'],
            [['Id', 'userId', 'groupId', 'createdBy', 'updatedBy', 'status', 'type'], 'integer'],
            [['createdOn', 'createdBy', 'updatedOn', 'updatedBy',], 'safe'],
            [['module', 'controller', 'action'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'Primary Key',
            'userId' => 'User Id from user table this will be used for user role',
            'groupId' => 'Group Id fro group permission.',
            'groupName' => 'Group Name',
            'userName' => 'User Name',
            'module' => 'Module Name',
            'controller' => 'Controller Name',
            'action' => 'Action Name',
            'createdOn' => 'Created On',
            'createdBy' => 'Created By',
            'updatedOn' => 'Updated On',
            'updatedBy' => 'Updated By',
            'status' => 'Status',
            'type' => 'Permission Type',
        ];
    }
    
    public function getGroupName() {        
        return $this->group->name;
    }
    
    public function getUserName() {        
        return $this->user->username;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['Id' => 'groupId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}
