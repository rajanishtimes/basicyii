<?php

/* 
 * Coder: Mithun Mandal
 * Contact: 01206636679/8527580960
 * Project: Timescity CMS Using Yii2
 */

namespace common\filters\asset\converter;

use yii;
use common\filters\asset\AssetProcess;
use common\modules\media\uploader\UploaderInterface;
use common\modules\media\uploader\UploadTrait;
use common\modules\media\components\ExifReader;
use yii\web\UploadedFile;
use yii\base\Exception;

class GeobeatConverter extends AssetProcess implements UploaderInterface{    
    use UploadTrait;
    public $videoId = 'videoId';
    public $source = 'source';
    public $embed = 'embed';
    public $url = 'videourl';
    public $width = 650;
    public $hieght = 360;


    public $channeluri = 'http://www.geobeats.com/video/';




    /**
     * @inheritdoc
     */
    public function convert($model, $data=[],$path=''){
        if ($data['embedcode'] !== null && strpos($data['embedcode'], 'geobeats')!== false) {
           
            if($data['embedcode'] !== null){
                $codes = $this->GetVideoCode('', urldecode($data['embedcode']) , 'geobeats');
                $videoId = $codes['video_code'];
                $videosource = $this->channeluri.$codes['video_code'].'/'.md5(time());
            }
            
            $hash = md5('Geobeats'.$videoId);
            /*
            if($armodel = $this->getModel($model,$hash)){
                return $armodel;
            }//*/
            
            yii::trace("geobeats source video:".$videosource);
            
            $doc = $this->getDataFromChannel($videosource);
            
            if($doc){
                preg_match_all('/name="(.*)" content="(.*)"/', $doc, $matches);
                yii::trace($matches);
                $content = [];
                $tag = ['title','description',"og:url","og:image","og:video:width", "og:video:height","og:video:type"];
                $tags = [
                            'title' =>'title',
                            'description' => 'description',
                            "og:url" => 'url',
                            "og:image" => 'image',
                            "og:video:width" => 'width', 
                            "og:video:height" => 'height',
                            "og:video:type" => 'type',
                        ];
                foreach($matches[1] as $key => $metakey){
                    yii::trace("Key:".$metakey);
                    if(in_array($metakey, $tag)){
                        $content[$tags[$metakey]] = $matches[2][$key];
                    }
                }
                
                yii::trace(print_r($content,true));
                
                $model = $this->getModel($model,'',$data['media_id'],'tc_est_video');

                $model->filename = $videoId;
                $model->path = $videosource;
                $model->uri = $content['url'];
                $model->description = (($data['media_name']!='') ? $data['media_name'] : $content['title']);
                $model->mimetype = $content['type'];
                $model->source = 'YouTube';
                $model->embedcode = ($data['embedcode']) ? urldecode($data['embedcode']) : '<object width="400" height="339"><param name="movie" value="http://www.geobeats.com/videoclips/embed/'.$videoId.'" /></param> <param name="menu" value="false" /></param> <param name="quality" value="high" /></param> <param name="wmode" value="opaque" /></param> <embed src="http://www.geobeats.com/videoclips/embed/'.$videoId.'" width="400" height="339" menu= "false" quality= "high" wmode="opaque" type= "application/x-shockwave-flash"></embed></object>';
                $model->mediahash = $hash;
                //$model->status = 1;
                $model->remoteId = $data['media_id'];
                $model->table = "tc_est_video";
                $model->metainfo = \yii\helpers\Json::encode([
                    'width' => $content['width'],
                    'height' => $content['height'],
                    'thumbnailUrl' => $content['image'],
                ]);
                
                $model->createdBy = $data['created_by'];
                $model->updatedBy = $data['created_by'];
                $model->createdOn = $data['insertdate'];
                $model->updatedOn = $data['insertdate'];
                $model->ip = $data['IP'];
                $model->status = $data['status'];

                $model->save(false);

                return $model;
            }
        }
    }
}