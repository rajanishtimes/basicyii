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

class YoutubeConverter extends AssetProcess implements UploaderInterface{    
    use UploadTrait;
    public $videoId = 'videoId';
    public $source = 'source';
    public $embed = 'embed';
    public $url = 'videourl';
    public $width = 650;
    public $hieght = 360;


    public $channeluri = 'http://www.youtube.com/watch?v=';




    /**
     * @inheritdoc
     */
    public function convert($model, $data=[],$path=''){
        if ($data['embedcode'] !== null && strpos($data['embedcode'], 'youtube')!== false) {
           
            if($data['embedcode'] !== null){
                $codes = $this->GetVideoCode('', urldecode($data['embedcode']) , 'youtube');
                $videoId = $codes['video_code'];
                $videosource = $this->channeluri.$codes['video_code'];
            }
            
            $hash = md5('Youtube'.$videoId);
            /*
            if($armodel = $this->getModel($model,$hash)){
                yii::trace("youtube source video:".print_r($armodel,true));
                return $armodel;
            }//*/
            
            yii::trace("youtube source video:".$videosource);
            
            $doc = $this->getDataFromChannel($videosource);
            
            if($doc){
                preg_match_all('/name="(.*)" content="(.*)"/', $doc, $matches);
                yii::trace($matches);
                $content = [];
                $tag = ['title','description',"twitter:url","twitter:image","twitter:player:width", "twitter:player:height"];
                $tags = [
                            'title' =>'title',
                            'description' => 'description',
                            "twitter:url" => 'url',
                            "twitter:image" => 'image',
                            "twitter:player:width" => 'width', 
                            "twitter:player:height" => 'height'
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
                $model->mimetype = 'video/*';
                $model->source = 'YouTube';
                $model->embedcode = ($data['embedcode']) ? urldecode($data['embedcode']) : '<iframe width="560" height="315" src="//www.youtube.com/embed/'.$videoId.'" frameborder="0" allowfullscreen></iframe>';
                $model->mediahash = $hash;
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
    
    public function getMime($type) {
        $mime = '';
        switch ($type){
            case 1: $mime = 'video/*'; break;
            case 2: $mime = 'image/*'; break;
            case 5: $mime = 'audio/*'; break;
            default : $mime = 'video/*'; break;        
        }
        return $mime;
    }
}