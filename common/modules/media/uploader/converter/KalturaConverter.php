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

class KalturaConverter extends UploadMethods implements UploaderInterface{    
    use UploadTrait;
    public $videoId = 'videoId';
    public $source = 'source';
    public $width = 650;
    public $hieght = 360;


    public $channeluri = 'http://www.kaltura.com/api_v3/index.php?service=media&action=get';


    /**
     * @inheritdoc
     */
    public function convert($model, $data=[],$path=''){        
        $request = \Yii::$app->request;
        yii::trace($request->getBodyParam($this->source));
        yii::trace($request->getBodyParam($this->videoId));
        if (($request->getBodyParam($this->source) == 'kaltura') && ($request->getBodyParam($this->videoId) !== null)) {
            
            $hash = md5 ('Kaltura'.$request->getBodyParam($this->videoId));
            if($armodel = $this->getModel($model,$hash)){
                return $armodel;
            }
            
            $xmloutput = $this->getDataFromChannel($this->channeluri, true, [
                        'entryId' => $request->getBodyParam($this->videoId),
                        'version' => '-1',
                    ]);
            
            yii::trace($xmloutput);
            
            $xmlobj = simplexml_load_string($xmloutput);
            yii::trace("XML:".print_r($xmlobj,true));
            if($xmlobj instanceof \SimpleXMLElement){
                if(isset($xmlobj->result->error->code)){
                    yii::trace("Error".$xmlobj->result->error->code);
                    return null;
                }else{
                    $content = (array) $xmlobj->result;
                    
                    yii::trace($content);
                    
                    $model = $this->getModel($model);

                    $model->filename = $request->getBodyParam($this->videoId);
                    $model->path = $content['dataUrl'];
                    $model->uri = 'http://cdn.kaltura.com/p/811441/thumbnail/entry_id/'.$this->videoId.'/width/'.$this->width.'/height/'.$this->hieght.'/type/1/quality/X';
                    $model->description = $content['name'];
                    $model->mimetype = $this->getMime($content['mediaType']);
                    $model->source = 'Kaltura';
                    $model->embedcode = '';
                    $model->mediahash = $hash;
                    $model->status = 1;
                    $model->metainfo = \yii\helpers\Json::encode([
                        'width' => $content['width'],
                        'height' => $content['height'],
                        'duration' => $content['duration'],
                        'thumbnailUrl' => $content['thumbnailUrl'],
                    ]);

                    $model->save();

                    return $model;
                }
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