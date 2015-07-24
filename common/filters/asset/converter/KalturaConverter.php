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

class KalturaConverter extends AssetProcess implements UploaderInterface{    
    use UploadTrait;    
    public $width = 650;
    public $hieght = 360;
    
    public $videoId;

    public $channeluri = 'http://www.kaltura.com/api_v3/index.php?service=media&action=get';


    /**
     * @inheritdoc
     */
    public function convert($model, $data=[],$path=''){        
        
        if (($data['source'] == 'kaltura') && ($data['video_code'] !== null)) {
            $this->videoId = $data['video_code'];
            $hash = md5 ('Kaltura'.$data['video_code']);
            /*
            if($armodel = $this->getModel($model,$hash)){
                return $armodel;
            }//*/
            
            $xmloutput = $this->getDataFromChannel($this->channeluri, true, [
                        'entryId' => $this->videoId,
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
                    
                    $model = $this->getModel($model,'',$data['media_id'],'tc_est_video');

                    $model->filename = $this->videoId;
                    $model->path = $content['dataUrl'];
                    $model->uri = 'http://cdn.kaltura.com/p/811441/thumbnail/entry_id/'.$this->videoId.'/width/'.$this->width.'/height/'.$this->hieght.'/type/1/quality/X';
                    $model->description = $data['media_name'];
                    $model->mimetype = $this->getMime($content['mediaType']);
                    $model->source = 'Kaltura';
                    $model->embedcode = '';
                    $model->mediahash = $hash;
                    //$model->status = 1;
                    $model->remoteId = $data['media_id'];
                    $model->table = "tc_est_video";
                    $model->metainfo = \yii\helpers\Json::encode([
                        'width' => $content['width'],
                        'height' => $content['height'],
                        'duration' => $content['duration'],
                        'data' => $content['dataUrl'],
                        'thumbnailUrl' => $content['thumbnailUrl'],
                    ]);
                    
                    $model->createdBy = $data['createdBy'];
                    $model->updatedBy = $data['createdBy'];
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