<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;
use yii;
use yii\web\NotFoundHttpException;

trait AssetTagTrait {
    /**
     * 
     * @param string $tag Tagname 
     * @return \common\models\Tags
     */
    public static function loadTagmodel($tag,$issystem = false,$runValidation = true) {
        $tag = trim($tag);
        if (($model = Tags::findOne(['name' => $tag])) !== null) {            
        }else{
            //$model = new tagTraiagTrail();
            
            $model = new Tags();
            $model->name = $tag;
            if($issystem)
                $model->issystem = 1;
            $model->save($runValidation);
        }
        return $model;
    }
    
    
    /**
     * 
     * @param integer $entityId entity Id of related
     * @param integer $entityType Entity Type of related object
     * @param ActiveRecord $tagmodel the Tag model to be linked with the current one.
     * @return ActiveRecord Tagmap Model 
     */
    public function getTagmapModel($entityId,$entityType,$tagmodel){
        if (($model = Tagmap::findOne(['entityId' => $entityId,'entityType' => $entityType,'tagId' => $tagmodel->Id])) !== null) {
                return $model;
        } else {
            return new Tagmap();
        }
    }

        /**
     * Save Tag with related Entities
     * 
     * @param integer $entityId entity Id of related
     * @param integer $entityType Entity Type of related object
     * @param ActiveRecord $tagModel the Tag model to be linked with the current one.
     */
    public function saveTagEntity($entityId,$entityType,$tagModel,$runValidation=true) {
        $model = $this->getTagmapModel($entityId, $entityType, $tagModel);
        $model->entityId = $entityId;
        $model->entityType = $entityType;
        //$model->save($runValidation);
        $tagModel->link('tagentitymaps',$model);
    }
    
    /**
     * Save Asset with Tag
     * 
     * @param ActiveRecordInterface $assetModel the Asset model to be linked with the current one.
     * @param ActiveRecordInterface $tagModel the Tag model to be linked with the current one.
     */
    public static function saveAssetTag($assetModel,$tagModel) {
        $model = self::loadAssetTag($assetModel->Id, $tagModel->Id);
        if($assetModel){
            $assetModel->link('tagassetmaps',$model);
        }
        if($tagModel){
            $tagModel->link('tagassetmaps',$model);
        }
    }
    
    
    public static function loadAssetTag($assetId,$tagId){
        if (($model = Tagassetmap::findOne(['assetId'=>$assetId,'tagId'=>$tagId])) !== null) {
            return $model;
        }else{
            return new Tagassetmap();
        }
    }


    /**
     * Save Asset with Tag
     * 
     * @param Integer $assetId the Asset Id.
     * @return ActiveRecord Asset Model
     */
    public function loadAsset($assetId) {
        if (($model = Asset::findOne($assetId)) !== null) {
            return $model;
        } else {
            //throw new NotFoundHttpException('The requested page does not exist.');
            yii::warning('asset id= '.$assetId.' not found');
        }
    }
    
    /**
     * 
     * @param integer $entityId  Entity Id
     * @param integer $entityType Entity Type Id
     * @param string $tag Media tag
     */
    public function removeAsset($entityId,$entityType,$tag){
        $tag = Tags::findOne(['name'=>$tag]);        
        if($tag){
            \yii::trace('delete Media Tags Ids:'.$tag->Id);
            Tagmap::deleteAll(['entityId' => $entityId,'entityType'=>$entityType,'tagId' => $tag->Id]);
            Tagassetmap::deleteAll(['tagId' => $tag->Id]);
        }
    }

        /**
     * 
     * @param Array $assetDataar Key value pair for data-key and asset-tag
     * @param Array $data Data populated from request or array.
     */
    public function saveAsset($assetDataar,$data,$runValidation=true) {
        foreach($assetDataar as $assetkey => $tagforasset){
            $this->removeAsset($this->id, $this->entity_type, $tagforasset);
            if(count($data[$assetkey])>0){
                $tagModel = $this->loadTagmodel($tagforasset, true,$runValidation);
                $this->saveTagEntity($this->id, $this->entity_type, $tagModel,$runValidation);
                Yii::info('Data for Asset', __METHOD__);
                Yii::info($data[$assetkey], __METHOD__);
                foreach($data[$assetkey] as $assetId){
                    $assetmodel = $this->loadAsset($assetId);
                    $this->saveAssetTag($assetmodel, $tagModel);
                }
            }
        }
    }
    
    /**
     * 
     * @param integer $entityId
     * @param integer $entityType
     */
    public function removeTagEntity($entityId,$entityType){
        $tagsId = [];
        foreach($this->tags as $tag){
            $tagsId[] = $tag->Id;
        }
        \yii::trace('delete Tags Ids:'.print_r($tagsId,true));
        if(count($tagsId)){
            Tagmap::deleteAll(['entityId' => $entityId,'entityType'=>$entityType,'tagId' => $tagsId]);
        }
    }

    /**
     * 
     * @param mixed $tags  tags to be entered
     * @param boolean $runValidation 
     */
    public function saveTags($tags,$runValidation=true){
        $this->removeTagEntity($this->id, $this->entity_type);
        if(count($tags)>0){
            if(!is_array($tags)){
                $tags = explode(',',$tags);
            }
            foreach ($tags as $tag) {
                if($tag!=''){
                    $tagModel = $this->loadTagmodel($tag,false,$runValidation);
                    $this->saveTagEntity($this->id, $this->entity_type, $tagModel,$runValidation);
                }
            }
        }
    }
    
    
    public function saveVenueAttributes($attributes,$runValidation=true){
        $this->removeVenueAttribute($this->id);
        if(count($attributes)>0){
            foreach ($attributes as $key=>$val)
            {
                
                
                //\yii::trace('delete Attribute Id:'.$key);
                
                
                if($key=='contactName')
                {
                    if($val!='')
                    {
                        $modelAttribute=new AttributeVenue();
                        $modelAttribute->attribute_id=1;
                        $modelAttribute->venue_id=$this->id;
                        $modelAttribute->value=$val;
                        $modelAttribute->save($runValidation);
                        unset($modelAttribute);
                    }
                }
                elseif($key=='email')
                {
                    if($val!='')
                    {
                        $modelAttribute=new AttributeVenue();
                        $modelAttribute->attribute_id=2;
                        $modelAttribute->venue_id=$this->id;
                        $modelAttribute->value=$val;
                        $modelAttribute->save($runValidation);
                        unset($modelAttribute);
                    }
                }
                elseif($key=='phone')
                {
                    //Yii::info("doc  phone:".print_r($val,true));
                    for($i=0;$i<count($val);$i++)
                    {
                        if($val[$i]!='')
                        {
                            //\yii::trace('value Attribute Id:'.$val[$i]);
                            $modelAttribute=new AttributeVenue();
                            $modelAttribute->attribute_id=3;
                            $modelAttribute->venue_id=$this->id;
                            $modelAttribute->value=$val[$i];
                            $modelAttribute->save($runValidation);
                            unset($modelAttribute);
                        }
                    }
                }
                elseif($key=='mobile')
                {
                    for($i=0;$i<count($val);$i++)
                    {
                        if($val[$i]!='')
                        {
                            $modelAttribute=new AttributeVenue();
                            $modelAttribute->attribute_id=4;
                            $modelAttribute->venue_id=$this->id;
                            $modelAttribute->value=$val[$i];
                            $modelAttribute->save($runValidation);
                            unset($modelAttribute);
                        }
                    }
                }
                elseif($key == "cuisine"){
                    $field_order = 0;
                    $cuisineId = Attributes::getId(Attributes::ATTR_CUISINE);
                    for($i=0;$i<count($val);$i++){
                        if($val[$i]!=''){
                            $modelAttribute=new AttributeVenue();
                            $modelAttribute->attribute_id=$cuisineId;
                            $modelAttribute->venue_id=$this->id;
                            $modelAttribute->value=$val[$i];
                            $modelAttribute->field_order = $field_order;
                            $modelAttribute->save($runValidation);
                            unset($modelAttribute);
                        }
                        $field_order++;
                    }
                }
                elseif($key == "feature"){
                    $field_order = 0;
                    $featureId = Attributes::getId(Attributes::ATTR_FEATURE);
                    for($i=0;$i<count($val);$i++){
                        if($val[$i]!=''){
                            $modelAttribute=new AttributeVenue();
                            $modelAttribute->attribute_id=$featureId;
                            $modelAttribute->venue_id=$this->id;
                            $modelAttribute->value=$val[$i];
                            $modelAttribute->field_order = $field_order;
                            $modelAttribute->save($runValidation);
                            unset($modelAttribute);
                        }
                        $field_order++;
                    }
                }
                 
            }
        }
    }
    
     public function saveEventAttributes($attributes,$runValidation=true){
        $this->removeEventAttribute($this->id);
        if(count($attributes)>0){
            foreach ($attributes as $key=>$val)
            {
                
                
                //\yii::trace('delete Attribute Id:'.$key);
                
                
                if($key=='contactName')
                {
                    if($val!='')
                    {
                        $modelAttribute=new AttributeEvent();
                        $modelAttribute->attribute_id=1;
                        $modelAttribute->event_id=$this->id;
                        $modelAttribute->value=$val;
                        $modelAttribute->save($runValidation);
                        unset($modelAttribute);
                    }
                }
                elseif($key=='email')
                {
                    if($val!='')
                    {
                        $modelAttribute=new AttributeEvent();
                        $modelAttribute->attribute_id=2;
                        $modelAttribute->event_id=$this->id;
                        $modelAttribute->value=$val;
                        $modelAttribute->save($runValidation);
                        unset($modelAttribute);
                    }
                }
                elseif($key=='phone')
                {
                    //Yii::info("doc  phone:".print_r($val,true));
                    for($i=0;$i<count($val);$i++)
                    {
                        if($val[$i]!='')
                        {
                            //\yii::trace('value Attribute Id:'.$val[$i]);
                            $modelAttribute=new AttributeEvent();
                            $modelAttribute->attribute_id=3;
                            $modelAttribute->event_id=$this->id;
                            $modelAttribute->value=$val[$i];
                            $modelAttribute->save($runValidation);
                            unset($modelAttribute);
                        }
                    }
                }
                elseif($key=='mobile')
                {
                    for($i=0;$i<count($val);$i++)
                    {
                        if($val[$i]!='')
                        {
                            $modelAttribute=new AttributeEvent();
                            $modelAttribute->attribute_id=4;
                            $modelAttribute->event_id=$this->id;
                            $modelAttribute->value=$val[$i];
                            $modelAttribute->save($runValidation);
                            unset($modelAttribute);
                        }
                    }
                }
                 
            }
        }
    }
    
    
    
    public function saveEventTimeWindow($attributes,$runValidation=true){
        $this->removeTime($this->id);
        if(count($attributes)>0)
            {
         
                //\yii::trace('delete Attribute Id:'.$key);
                
                        $modelAttribute=new TimeWindow();
                        $modelAttribute->start_date=$attributes['start_date'];
                        $modelAttribute->end_date=$attributes['end_date'];
                        $modelAttribute->start_time=$attributes['start_time'];
                        $modelAttribute->end_time=$attributes['end_time'];
                        $modelAttribute->week_days=$attributes['week_days'];
                        $modelAttribute->event_id=$this->id;
                        $modelAttribute->save($runValidation);
                        unset($modelAttribute);
                    
                 
            }
        
    }
    
    
    
    
    
     public function removeVenueAttribute($entityId)
      {
            AttributeVenue::deleteAll(['venue_id' => $entityId]); 
       }
    
    
       
       public function removeEventAttribute($entityId)
      {
        
            AttributeEvent::deleteAll(['event_id' => $entityId]);
        
       }
       
       
      public function removeTime($entityId)
      {
        
            TimeWindow::deleteAll(['event_id' => $entityId]);
        
       }
       
       
       
        public function saveAffilation($affiliatedId,$affiliateEntityId,$runValidation=true){       
        if(($model = AffiliateContent::findOne(['affiliate_id'=>$affiliatedId,'affiliate_entity_id'=>$affiliateEntityId,'entity_id'=> $this->id,'entity_type'=> $this->entity_type])) ===null){
            $model = new AffiliateContent();
        }
        
        $model->affiliate_id = $affiliatedId;
        $model->affiliate_entity_id = $affiliateEntityId;
        $model->entity_id = $this->id;
        $model->entity_type = $this->entity_type;
        
        $model->save();
    }
       
       
    public function getUpdatedByUser() {
        return $this->userupdated->username;
    }
    
    public function getCreatedByUser() {
        return $this->usercreated->username;
    }
    
    public function getUsercreated(){
        return $this->hasOne(User::className(), ['Id' => 'created_by']);
    }
    
    public function getUserupdated(){
        return $this->hasOne(User::className(), ['Id' => 'updated_by']);
    }
}