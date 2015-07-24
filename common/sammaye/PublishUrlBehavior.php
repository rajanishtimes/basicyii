<?php

namespace common\sammaye;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use common\component\AppActiveRecord;
use common\sammaye\PublishUrl;
use common\models\EntityType;

class PublishUrlBehavior extends Behavior
{
	public function events()
	{
		return [
			ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
                        AppActiveRecord::EVENT_AFTER_PUBLISH =>'afterPublish'
			
		];		
	}
        
        public function afterUpdate($event){
            $modelClass=get_class($event->sender);            
            switch($modelClass){
                case 'common\models\CriticsUser':
                    $this->savePublishUrl($event->sender->id, EntityType::CRITIC_USER_ENTITY_ID, $event->sender->url);
                    break;
                case 'common\models\CriticsReviews':
                    $this->savePublishUrl($event->sender->id, EntityType::REVIEW_ENTITY_ID, $event->sender->url);
                    break;
            }
            
        }
        
        public function afterPublish($event){
            $modelClass=get_class($event->sender);
            switch($modelClass){
                case 'common\models\Content':
                    $this->savePublishUrl($event->sender->id, EntityType::CONTENT_ENTITY_ID, $event->sender->url);
                    break;
                case 'common\models\Event':
                    $this->savePublishUrl($event->sender->id, EntityType::EVENT_ENTITY_ID, $event->sender->url);
                    break;
                case 'common\models\Venue':
                    $this->savePublishUrl($event->sender->id, EntityType::VENUE_ENTITY_ID, $event->sender->url);
                break;
            }
        }
	
        public function savePublishUrl($entityId,$entityType,$url){
            $model = PublishUrl::find()->where(['url'=>$url,'entity_id'=>$entityId,'entity_type'=>$entityType])->orderBy('id desc')->one();
            if($model){
                $model->updatedOn = date('Y-m-d H:i:s');
                if($model->save()){
                    $model->addUrlToCachePurge();
                    Yii::info("entity re-published with same url",'url-publish');
                }            
            }
            else{
                $model = new PublishUrl;
                $model->entity_id = $entityId;
                $model->entity_type = $entityType;
                $model->url = $url;
                $model->createdOn = date('Y-m-d H:i:s');
                if($model->save()){
                    $model->addOldUrlToCachePurge();
                    Yii::info("entity published new url",'url-publish');                    
                }            
            }
            
        }
        
        
        
	
}