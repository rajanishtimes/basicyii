<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

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
class UpdateUser extends User
{
    public $password2;
    /**
     * @inheritdoc
     */
    
    public function getPassword()
    {
        return '';
    }
    
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            //['role', 'default', 'value' => self::ROLE_USER],
            //['role', 'in', 'range' => [self::ROLE_USER]],

            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique'],
            ['username', 'string', 'min' => 2, 'max' => 16],
            ['username', 'common\validators\UsernameValidator'],

            ['email', 'filter', 'filter' => 'trim'],
            [['groupId','email'], 'required'],
            ['email', 'email','checkDNS'=>true],
            ['email', 'unique'],
            
            [['firstname', 'lastname'], 'string', 'max' => 45],
            [['phone'], 'number'],
            ['phone', 'common\validators\MobileValidator'],
            [['groupId', 'reportTo', 'reportUserType'], 'integer'],            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'role' => Yii::t('app', 'Role'),
            'status' => Yii::t('app', 'Status'),
            'createdOn' => Yii::t('app', 'Created On'),
            'updatedOn' => Yii::t('app', 'Updated On'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'firstname' => Yii::t('app', 'Firstname'),
            'lastname' => Yii::t('app', 'Lastname'),
            'groupId' => Yii::t('app', 'Group ID'),
            'reportTo' => Yii::t('app', 'Report To'),
            'reportUserType' => Yii::t('app', 'Report User Type'),
            'phone' => Yii::t('app', 'Mobile'),
        ];
    }
}
