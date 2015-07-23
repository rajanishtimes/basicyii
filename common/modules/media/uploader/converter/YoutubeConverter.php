<?php

/* 
 * Coder: Mithun Mandal
 * Contact: 01206636679/8527580960
 * Project: Timescity CMS Using Yii2
 */

namespace common\modules\media\uploader\converter;

use yii;
use common\modules\media\uploader\UploadMethods;
use common\modules\media\uploader\UploaderInterface;
use common\modules\media\uploader\UploadTrait;
use common\modules\media\components\ExifReader;
use yii\web\UploadedFile;
use yii\base\Exception;

class YoutubeConverter extends UploadMethods implements UploaderInterface{    
    use UploadTrait;
    public $videoId = 'videoId';
    public $source = 'source';
    public $embed = 'embed';
    public $url = 'videourl';
    public $width = 650;
    public $hieght = 360;


    public $channeluri = 'https://www.youtube.com/watch?v=';




    /**
     * @inheritdoc
     */
    public function convert($model, $data=[],$path=''){        
        $request = \Yii::$app->request;
        if ((($request->getBodyParam($this->source) == 'youtube') && ($request->getBodyParam($this->videoId) !== null))
                ||
        ($request->getBodyParam($this->url) !== null && strpos($request->getBodyParam($this->url), 'youtube')!== false)
                ||
        ($request->getBodyParam($this->embed) !== null && strpos($request->getBodyParam($this->embed), 'youtube')!== false)
                ) {
           
            if($request->getBodyParam($this->videoId) !== null){
                $videoId = $request->getBodyParam($this->videoId);
                $videosource = $this->channeluri.$request->getBodyParam($this->videoId);
            }
            
            if($request->getBodyParam($this->embed) !== null){
                $codes = $this->GetVideoCode('',$request->getBodyParam($this->embed), 'youtube');
                $videoId = $codes['video_code'];
                $videosource = $this->channeluri.$codes['video_code'];
            }
            
            if($request->getBodyParam($this->url) !== null){
                if(preg_match_all("/youtube.com\/watch\?v=([a-zA-Z0-9_\-\|\.]+)/i", $request->getBodyParam($this->url), $url_array)){
                    yii::trace(print_r($url_array,true));
                    $videoId = $url_array[1][0];
                    $videosource = $this->channeluri.$url_array[1][0];
                }
            }
            
            $hash = md5('Youtube'.$videoId);
            if($armodel = $this->getModel($model,$hash)){
                return $armodel;
            }
            
            yii::trace("youtube source video:".$videosource);
            
            $doc = $this->getDataFromChannel($videosource);
            
            yii::trace("youtube source video result:".$doc);
            
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
                
                $model = $this->getModel($model);

                $model->filename = $videoId;
                $model->path = $videosource;
                $model->uri = $content['url'];
                $model->description = $content['title'];
                $model->mimetype = 'video/*';
                $model->source = 'YouTube';
                $model->embedcode = urlencode(($request->getBodyParam($this->embed)) ? $request->getBodyParam($this->embed) : '<iframe width="560" height="315" src="//www.youtube.com/embed/'.$videoId.'" frameborder="0" allowfullscreen></iframe>');
                $model->mediahash = $hash;
                $model->status = 1;
                $model->metainfo = \yii\helpers\Json::encode([
                    'width' => $content['width'],
                    'height' => $content['height'],
                    'thumbnailUrl' => $content['image'],
                ]);

                $model->save();

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