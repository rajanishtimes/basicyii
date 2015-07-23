<?php

/* 
 * Coder: Mithun Mandal
 * Contact: 01206636679/8527580960
 * Project: Timescity CMS Using Yii2
 */

namespace common\models;

trait MediaTagTrait {
    
    /**
     * @return array
     */
    public function getImages() {        
        $data = $this->imageTags;
        $image = [];
       foreach($data as $model){           
           $image[] = $model->assets;
       }
       return $image;
    }
    
    /**
     * @return array
     */
    public function getImagesId() {        
        $data = $this->imageTags;
        $image = [];
       foreach($data as $model){           
           $image[] = $model->assets->Id;
       }
       return $image;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideo() {
        $data = $this->videoTags;
        $image = [];
       foreach($data as $model){           
           $image[] = $model->assets;
       }
       return $image;
    }
     
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideoTags() {        
        return $this->hasMany(Tags::className(), ['id' => 'tagId'])->via('tagmap')->where(['name'=>$this->ClassSortNeme().'_video_'.$this->id]);
    }
   
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageTags() {        
        return $this->hasMany(Tags::className(), ['id' => 'tagId'])->via('tagmap')->where(['name'=>$this->ClassSortNeme().'_image_'.$this->id]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags() {
        return $this->hasMany(Tags::className(), ['Id' => 'tagId'])->via('tagmap')->where(['issystem'=>0]);
    }
    
    /**
     * @return string
     */
    public function getTagstr() {
        $data = [];
        foreach($this->tags as $tag){
            $data[] = $tag->name;
        }
        return implode(',',$data);
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsTags() {
        return $this->hasMany(Tags::className(), ['Id' => 'tagId'])->via('tagmap')->where('issystem != '.Tags::TYPE_SYSTEM);
    }
    
    /**
     * @return string
     */
    public function getCMSTagstr() {
        $data = [];
        foreach($this->cmsTags as $tag){
            $data[] = $tag->name;
        }
        return implode(',',$data);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagAssetmap()
    {
        return $this->hasMany(Tagassetmap::className(), ['tagId' => 'tagId'])->via('tagmap');
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagmap()
    {
        return $this->hasMany(Tagmap::className(), ['entityId' => 'id','entityType' => 'entity_type']);
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents() {
        return $this->hasMany(EventLite::className(), ['id' => 'event_id'])->via('eventVenueMap');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventVenueMap()
    {
        return $this->hasMany(EventVenueMap::className(), ['venue_id' => 'id']);
    }
    
    /**
     * @return string
     */
    public function getCityname() {
        return $this->city->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }
    
    /**
     * @return string
     */
    public function getLocalityname() {
        return $this->locality->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocality()
    {
        return $this->hasOne(Locality::className(), ['id' => 'locality_id']);
    }
    
    /**
     * @return string
     */
    public function getSourcename() {
        return $this->source->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne(Source::className(), ['id' => 'source_id']);
    }
    
    /**
     * @return string
     */
    public function getZonename() {
        return $this->zone->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZone()
    {
        return $this->hasOne(Zone::className(), ['id' => 'zone_id']);
    }
    
    /**
     * @return string
     */
    public function getHotelname() {
        return $this->hotel->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHotel()
    {
        return $this->hasOne(Hotels::className(), ['id' => 'hotel_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAffiliate() {        
        return $this->hasMany(Affiliate::className(), ['id' => 'affiliate_id'])->via('affiliatemap');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAffiliatemap() {        
        return $this->hasMany(AffiliateContent::className(), ['entity_id' => 'id','entity_type' => 'entity_type']);
    }

    /*
     * @return \common\models\Asset object or false;
     */
    public function getCoverImage(){
        $imgs = $this->images;
        if(!empty($imgs)){
            foreach($imgs[0] as $img){
                if($img->is_cover == 1){
                    return $img;
                }
            }
        }
        return false;        
    }
    
    public function checkAllCropped($img = []){
        $return = true;
        foreach($img as $i){
            if($i['is_cropped'] == 0){
                $return = false;
                break;
            }
        }
        return $return;
    }
    
    
    /*
     * Generate thumbs for 
     */
    public function ThumbGenerate(){
       $return = ['success'=>false,'message'=>'','data'=>'']; 
       $Imgs = $this->images;
        if(!empty($Imgs[0])){
            $UrlArray = [];
            foreach($Imgs[0] as $a){
                $UrlArray[] =  $a->uri;
            }       
            $ThumbGenerateUrl = \Yii::$app->params['imagepreviewurl'].'/generate_thumb.php';
            $curl = new \common\helpers\Curl();
            $curl->post($ThumbGenerateUrl, array(
                'urls' => $UrlArray,
                'source'=>'cms'
            ));
            if ($curl->error) {
                \Yii::warning('Error: ' . $curl->error_code . ': ' . $curl->error_message,'ImgThumb');
                $return['message'] = $curl->error_message;
            }
            else {
                \Yii::info($curl->response,'ImgThumb');
                $return['data'] = $curl->response;
                if(empty($curl->response->error)){
                    $return['success'] = true;
                    $return['message'] = $curl->response->message;
                }
                else{
                    $return['message'] = implode(', ', $curl->response->error);
                    
                }              
            }
            $curl->close();
        }        
        else{
            $return['message']= 'no image uploaded in this enitity';
        }
        return $return;
   }

}