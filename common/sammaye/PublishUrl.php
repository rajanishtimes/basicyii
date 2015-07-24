<?php

namespace common\sammaye;

use Yii;

/**
 * This is the model class for table "tc_publish_urls".
 *
 * @property integer $id
 * @property integer $entity_id
 * @property integer $entity_type
 * @property string $url
 * @property string $cache_purge_on
 * @property string $createdOn
 * @property string $updatedOn
 */
class PublishUrl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tc_publish_urls';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_id', 'entity_type', 'url'], 'required'],
            [['entity_id', 'entity_type'], 'integer'],
            [['cache_purge_on', 'createdOn', 'updatedOn'], 'safe'],
            [['url'], 'string', 'max' => 1024],
            //['url','unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity_id' => 'Entity ID',
            'entity_type' => 'Entity Type',
            'url' => 'Url',
            'cache_purge_on' => 'Cache Purge On',
            'createdOn' => 'Created On',
            'updatedOn' => 'Updated On',
        ];
    }
    
    public function addOldUrlToCache(){
        
    }
    
    public function addUrlToCachePurge(){
        $PurgeModel = PurgeUrl::find()->where(['url'=>$this->url,'status'=>0])->one();
        if(!$PurgeModel){
            $PurgeModel = new PurgeUrl();
            $PurgeModel->url = $this->url;
            $PurgeModel->createdOn = date('Y-m-d H:i:s');
            $PurgeModel->save();
        }
        else{
            Yii::info('already added into cache purge','url-publish');
        }        
    }
    
    public function addOldUrlToCachePurge(){
        $old = PublishUrl::find()->where(['entity_id'=>$this->entity_id,'entity_type'=>$this->entity_type])->andWhere('id != '.$this->id)->orderBy('id desc')->one();
        if($old){
            $already = PurgeUrl::find()->where(['url'=>$old->url,'status'=>0])->one();
            if(!$already){
                $PurgeModel = new PurgeUrl();
                $PurgeModel->url = $old->url;
                $PurgeModel->createdOn = date('Y-m-d H:i:s');
                $PurgeModel->save();
            }
            else{
                Yii::info('old url already added into cache purge','url-publish');
            }
        }
        else{
            Yii::info('no old url found','url-publish');
        }        
    }    
}