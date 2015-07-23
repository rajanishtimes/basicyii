<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tc_user_group_map".
 *
 * @property integer $userId
 * @property integer $groupId
 *
 * @property Group $group
 * @property User $user
 */
class UserGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_group_map}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'groupId'], 'required'],
            [['userId', 'groupId'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('app', 'ID'),
            'userId' => Yii::t('app', 'User ID'),
            'groupId' => Yii::t('app', 'Group ID'),
            'GroupName' => Yii::t('app', 'Group Name'),
        ];
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
        return $this->hasOne(User::className(), ['Id' => 'userId']);
    }
    
    public function getGroupName() {
        return $this->group->name;
    }
}
