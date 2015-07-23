<?php

namespace backend\models;

use Yii;

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
class UserListType extends \yii\db\ActiveRecord
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
            [['userType', 'reason', 'createdOn', 'createdBy', 'updatedBy', 'STATUS', 'ip'], 'required'],
            [['userType', 'userId', 'createdBy', 'updatedBy', 'STATUS'], 'integer'],
            [['reason'], 'string'],
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
            'reason' => 'Reason',
            'createdOn' => 'Created On',
            'updatedOn' => 'Updated On',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'STATUS' => 'Status',
            'ip' => 'Ip',
        ];
    }
}
