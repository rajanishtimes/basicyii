<?php

namespace common\models;

use Yii;
use common\component\AppActiveRecord;

/**
 * This is the model class for table "tc_tags".
 *
 * @property string $Id
 * @property string $name
 * @property string $description
 * @property integer $createdBy
 * @property integer $updatedBy
 * @property string $createdOn
 * @property string $updatedOn
 * @property integer $status
 * @property string $ip
 * @property integer $issystem
 *
 * @property Tagassetmap[] $tagassetmaps
 */
class Tags extends AppActiveRecord
{
    
    const TYPE_EDITOR = 0;
    const TYPE_SYSTEM = 1;
    const TYPE_INTERNAL = 2;
    
    public static $TYPE = [
        self::TYPE_EDITOR => 'Editor Tag',
        self::TYPE_INTERNAL => 'Internal Tag [For CMS only]'
    ];
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tags}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            ['name', 'filter', 'filter' => 'trim', 'skipOnArray' => true],
            [['description'], 'string'],
            [['created_by', 'updated_by', 'status', 'issystem'], 'integer'],
            [['created_on', 'updated_on','created_by', 'updated_by'], 'safe'],
            [['name'], 'string', 'max' => 250],
            [['ip'], 'string', 'max' => 15],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'status' => 'Status',
            'ip' => 'Ip',
            'issystem' => 'Type',
            'assets' => 'Media Asset',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function fields() {
        return [
            'Id',
            'name',
            'status',
            'issystem',
            'description',
            'assets'
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssets() {
        return $this->hasMany(Asset::className(), ['Id' => 'assetId'])->via('tagassetmaps')->orderBy('sort_order asc, created_on desc');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagassetmaps()
    {
        return $this->hasMany(Tagassetmap::className(), ['tagId' => 'Id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagentitymaps()
    {
        return $this->hasMany(Tagmap::className(), ['tagId' => 'Id']);
    }
    
    
     public function search($data)
    {
        
       $name=$data['name'];
       
       $affId=0;
       if($name!='')
        {
            $model = self::find()->andWhere(['name'=>$name])->limit(1)->all();
            
            if($model){
               
                 $affId=(int)$model[0]->Id;
                 return $affId;
            }
            else{
                return $affId;
            }
        
        }
        else
        {
            Yii::info(__METHOD__.'Full Data not provided', 'api');
            $response = Yii::$app->getResponse();
            $response->setStatusCode(402);
        }
         
    } 
    
    /**
     * 
     * return tag name by type
     * 
     * @param integer $type
     * @return string
     */
    public static function getTypeName($type){
        switch ($type){
            case Tags::TYPE_EDITOR :
                return "Editor Tag";
            case Tags::TYPE_INTERNAL:
                return "Internal Tag";
            case Tags::TYPE_SYSTEM:
                return "System Tag";
            default :
                return "(unknown)";
        }
    }
}
