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
class CreateUser extends User
{
    public $password1;
    public $password2;
    /**
     * @inheritdoc
     */
    
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
            [['groupId', 'reportTo', 'reportUserType'], 'integer','message'=>'Please select {attribute}'],
            
            // built-in "compare" validator that is used in "register" scenario only
            [['password1','password2'], 'required'],
            [['password1','password2'], 'string', 'min' => 8, 'max' => 16],
            [['password1','password2'], 'match', 'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@\-.#$%]).{6,16}$/','message' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@\-.#$%]).{6,16}$/','message' => 'Character are allowed here are number 0-9, alphabet a-z, Capital letter A-Z and Special character (!@#$%-.)'],
            ['password2', 'compare', 'compareAttribute' => 'password1','message' => 'Confirm Password not matched'],
            ['password1', 'setModelPass'],
            // an inline validator defined via the "authenticate()" method in the model class
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
            'groupId' => Yii::t('app', 'Group'),
            'reportTo' => Yii::t('app', 'Report To'),
            'reportUserType' => Yii::t('app', 'Report User Type'),
            'phone' => Yii::t('app', 'Mobile'),
            'password1' => Yii::t('app', 'Password'),
            'password2' => Yii::t('app', 'Confirm Password'),
        ];
    }
    
    public function setModelPass() {
        $this->password = $this->password1;
    }
}
