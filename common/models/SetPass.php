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
class SetPass extends User
{
    public $password2;
    public $password1;
    /**
     * @inheritdoc
     */
    
    
    public function rules()
    {
        return [            
            [['password1','password2'], 'required'],
            [['password1','password2'], 'string', 'min' => 8, 'max' => 16],
            [['password1','password2'], 'match', 'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@\-.#$%]).{6,16}$/','message' => 'Character are allowed here are number 0-9, alphabet a-z, Capital letter A-Z and Special character (!@#$%-.)'],
            ['password2', 'compare', 'compareAttribute' => 'password1','message' => 'Confirm Password not matched'],
            ['password1', 'setModelPass'],
            [['Id','password_reset_token','updatedBy'],'safe'],
        ];
    }
    
    
    public function setModelPass() {
        $this->password = $this->password1;
    }
    
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password1' => Yii::t('app', 'Password'),
            'password2' => Yii::t('app', 'Confirm Password'),
        ];
    }
}
