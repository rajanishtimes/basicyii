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
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_on
 * @property string $updated_on
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
            [['user_id', 'created_by', 'updated_by', 'status'], 'integer'],
            [['login_time', 'created_on', 'updated_on','name','username'], 'safe'],
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
            'user_id' => Yii::t('app', 'User ID'),
            'login_time' => Yii::t('app', 'Login Time'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
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
