<?php

namespace common\models;

use Yii;
use common\component\AppActiveRecord;
use yii\behaviors\SluggableBehavior;
/**
 * This is the model class for table "tc_asset".
 *
 * @property string $Id
 * @property string $filename
 * @property string $path
 * @property string $uri
 * @property string $slug
 * @property string $description
 * @property string $mimetype
 * @property string $source
 * @property string $embedcode
 * @property string $mediahash
 * @property string $metainfo
 * @property integer $is_cropped
 * @property integer $is_cover
 * @property integer $sort_order
 * @property string $cropdata
 * @property integer $createdBy
 * @property integer $updatedBy
 * @property string $createdOn
 * @property string $modifiedOn
 * @property integer $status
 * @property string $ip
 * @property string $remoteId
 * @property string $table
 *
 * @property Tagassetmap[] $tagassetmaps
 */
class Asset extends AppActiveRecord
{
    const UPLOAD_BASE_DIR = '@media';
    //public static $uploadPath = '@media';
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%asset}}';
    }
    
    /*public function behaviors() {
        return array_merge([
            'slug'=>[
                'class' => SluggableBehavior::className(),
                'attribute' => 'filename',
                'ensureUnique'=>true,
                'slugAttribute' => 'slug'
            ]
        ],parent::behaviors());
    }*/

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['Id'], 'required'],
            [['Id', 'createdBy', 'updatedBy', 'status','remoteId','is_cover'], 'integer'],
            [['createdOn', 'updatedOn','table','cropdata','slug','is_cover'], 'safe'],
            [['filename','slug','path', 'uri'], 'string', 'max' => 512],
            [['cropdata'], 'string', 'max' => 200],
            [['embedcode'], 'string', 'max' => 255],
            [['description', 'metainfo'], 'string'],
            [['mimetype', 'source'], 'string', 'max' => 45],
            [['mediahash'], 'string', 'max' => 32],
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
            'filename' => 'Filename',
            'path' => 'Path',
            'uri' => 'Uri',
            'slug' => 'Slug',
            'description' => 'Description',
            'mimetype' => 'Mimetype',
            'source' => 'Source',
            'embedcode' => 'Embedcode',
            'mediahash' => 'Mediahash',
            'metainfo' => 'Metainfo',
            'is_cropped' => 'Is Cropped',
            'sort_order' => 'Sort Order',
            'is_cover' => 'Cover Image',
            'cropdata' => 'CropData',
            'createdBy' => 'Created By',
            'updatedBy' => 'Updated By',
            'createdOn' => 'Created On',
            'updatedOn' => 'Updated On',
            'status' => 'Status',
            'ip' => 'Ip',
            'remoteId' => 'Remote Table Id',
            'table' => 'Remote Table Table',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function fields() {
        return [
            'Id',
            //'filename',
            'uri',
            //'description',
            //'mimetype',
            'is_cropped',
            'is_cover',
            'cropdata',
            'sort_order',
            //'source',
            //'embedcode',
            //'is_cropped',
            //'metainfo',
            'status',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagassetmaps()
    {
        return $this->hasMany(Tagassetmap::className(), ['assetId' => 'Id']);
    }
    
    
    public function getTagmap(){
        return $this->hasMany(Tagmap::className(), ['tagId' => 'tagId'])->via('tagassetmaps');
    }
    
    public function getEntityType(){
        $tagmap = $this->tagmap;
        if(!empty($tagmap[0]['entityId'])){
            return $tagmap[0]['entityType'];
        }
        else{
            return FALSE;
        }
    }
    
    
    public static function getPath($path){
        if($path){
            $path = '/'.$path.'/'.date('Y').'/'.date('M');
        }else{
            $path = '/'.date('Y').'/'.date('M');
        }
        return $path;
    }
    
    /*
     * @author : Mukesh Soni
     * @description : will return path to storage.
     * @param $path entity path, as in event,content,venue,critic-user
     * @param $absolute where to return absolute path or alias associated path
     * @date : Apr 15, 2015
     */
    public static function getStoragePath($path,$absolute = true){
        $path = self::getPath($path);
        if($absolute){
            $uploadPath = Yii::getAlias(self::UPLOAD_BASE_DIR).$path;
        }
        else{
            $uploadPath = self::UPLOAD_BASE_DIR.$path;
        }
        return $uploadPath;
    }
    
    public static function getNewFileName($filename){
        $fileInfo = pathinfo($filename);
        $name = $fileInfo['filename'];
        $name = static::cleanName($name);
        $newName = time().'-'.$name.'.'.$fileInfo['extension'];
        return $newName;
    }
    
    public static function cleanName($name){
        $name = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $name);
	$name = strtolower(trim($name, '-'));
	$name = preg_replace("/[_|+ -]+/", '-', $name);
        return $name;
    }
}
