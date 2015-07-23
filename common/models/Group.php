<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use common\component\AppActiveRecord;
/**
 * This is the model class for table "tc_group".
 *
 * @property string $Id
 * @property string $name
 * @property string $parentId
 * @property string $status
 * @property string $createdon
 */
class Group extends AppActiveRecord
{
    use PermissionTrait;
    
    /**
     * @return array|[ID=>Name]. 
     */
    public function getGroups(){
        $groups = $_dataMap =  [
                        '0' => 'root',
                    ];        
        
            $argroups = $this->find()
                             ->where(['status' => '1'])
                             ->orderBy('parentId')
                             ->all();            
        
        
        foreach ($argroups as $group) {
            if($this->Id == $group->Id){
                continue;
            }
            $groups[$group->Id] = $group->name; 
        }
        return $groups;
    }
    
    public function getCreateTime() {        
        return date('D, jS M Y \a\t g:ia',  strtotime($this->updated_on));
    }
    
    public function getStatusName() {
        switch($this->status){
            case '1': $status = 'Enable'; break;
            case '0': $status = 'Disable'; break;
        }
        return $status;
    }
    
    public function getParent() {
        return $this->hasOne(self::classname(), 
               ['Id' => 'parentId'])->from(self::tableName() . ' AS parent');
    }
    
    public function getParentGroup() {
        if($this->parent->name){
            return $this->parent->name;
        }else{
            return 'Root';
        }
        
    }
        
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'updated_by','parentId'], 'integer'],
            [['name'], 'required'],
            ['name', 'common\validators\AlphanumericValidator'],
            [['status'], 'string'],
            [['created_on', 'updated_on','created_on', 'updated_on'], 'safe'],
            [['name'], 'string', 'max' => 25]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'Group Id',
            'name' => 'Name',
            'parentId' => 'Parent Group',
            'status' => 'Status',
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'ParentGroup' => Yii::t('app', 'Parent Group'),
            'StatusName' => Yii::t('app', 'Status'),
            'CreateTime' => Yii::t('app', 'Created Time'),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function fields() {
        return [
            'Id',
            'name',
            'parentId',
            'ParentGroup',
            'status'
        ];
    }
    
    public function getMapedUsers()
    {
        return $this->hasMany(UserGroup::className(), ['groupId' => 'Id']);
    }
}
