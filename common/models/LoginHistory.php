<?php

namespace common\models;

use Yii;
use common\component\AppActiveRecord;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "{{%login_history}}".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $login_time
 * @property integer $createdBy
 * @property integer $updatedBy
 * @property string $createdOn
 * @property string $updatedOn
 * @property string $ip
 * @property integer $status
 */
class LoginHistory extends AppActiveRecord
{
    public $name;
    public $username;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%login_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'createdBy', 'updatedBy', 'status'], 'integer'],
            [['login_time', 'createdOn', 'updatedOn','name','username'], 'safe'],
            [['ip'], 'string', 'max' => 25]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userId' => Yii::t('app', 'User ID'),
            'loginTime' => Yii::t('app', 'Login Time'),
            'createdBy' => Yii::t('app', 'Created By'),
            'updatedBy' => Yii::t('app', 'Updated By'),
            'createdOn' => Yii::t('app', 'Created On'),
            'updatedOn' => Yii::t('app', 'Updated On'),
            'ip' => Yii::t('app', 'Ip'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
    
    
    public function fields() {
        return [
            'id',
            'login_time',
            'user_id'
        ];
    }
    
    
    /**
     * Return user calss object
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    /**
     * Used to get login histroy record for dashboard
     *  
     * @author Mukesh
     * @return \yii\data\ActiveDataProvider Object
     */
    public function getDashboardData(){
        $yesterday = date('Y-m-d',strtotime('-2 day'));
        $query =  self::find()->joinWith('user')->where('user_id !=""')->andWhere(['>','login_time',$yesterday]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'attributes'=>[
                        'name'=>[
                            'asc'=>['tc_user.firstname'=>SORT_ASC,'tc_user.lastname'=>SORT_ASC],
                            'desc'=>['tc_user.firstname'=>SORT_DESC,'tc_user.lastname'=>SORT_DESC],
                        ],
                        'username'=>[
                            'asc'=>['tc_user.username'=>SORT_ASC],
                            'desc'=>['tc_user.username'=>SORT_DESC],
                        ],
                        'ip',
                        'user_id',
                        'login_time'=>[
                            'asc'=>['login_time'=>SORT_ASC],
                            'desc'=>['login_time'=>SORT_DESC],
                            'default' => SORT_DESC,
                        ]
                            
                ],
                'defaultOrder'=>['login_time'=>SORT_DESC],
             ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $dataProvider;
    }
    
}
