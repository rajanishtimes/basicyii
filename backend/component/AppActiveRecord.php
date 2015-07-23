<?php

namespace backend\component;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;


class AppActiveRecord extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return array(
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_on', 'updated_on'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_on',
                ],
                'value' => new Expression('NOW()'),
            ],
            'user' => [
                'class' => 'yii\behaviors\BlameableBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_by', 'updated_by'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_by',
                ],
            ],
            'ip' => [
                'class' => 'yii\behaviors\AttributeBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['ip'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'ip',
                ],
                'value' => function($event){
                    return Yii::$app->request->userIP;
                },
            ],
        );
    }
    
    public function softdelete() {
        $this->status = '0';
        $this->update();
    }
    
    public function restore() {
        $this->status = '1';
        $this->update();
    }    
    
    public function getStatus(){
        return [
            '1' => 'Enable',
            '0' => 'Disable',
        ];
    }
    
    public function getStatusName() {
        switch($this->status){
            case '1': $status = 'Enable'; break;
            case '0': $status = 'Disable'; break;
        }
        return $status;
    }
    
    public function getCreateTime() {        
        return date('D, jS M Y \a\t g:ia',  strtotime($this->created_on));
    }
    
    public function getUpdateTime() {        
        return date('D, jS M Y \a\t g:ia',  strtotime($this->updated_on));
    }  
}
