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

class OtherConverter extends AssetProcess implements UploaderInterface{    
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
    public function convert($model, $media_data=[],$path=''){        
        $hash = md5($data['embedcode']);
        if($armodel = $this->getModel($model,$hash)){
            return $armodel;
        }
        
        $media_doc = [
                    'filename'      => $media_data['video_code'],
                    'path'          => $media_data['data_url'],
                    'uri'           => $media_data['thumb_url'],
                    'description'   => $media_data['media_name'],
                    'mimetype'      => 'video/*',
                    'source'        => 'Other',
                    'embedcode'     => $media_data['embedcode'],
                    'mediahash'     => $hash,
                    'metainfo'      => '',
                    'createdBy'     => $media_data['createdBy'],
                    'updatedBy'     => $media_data['createdBy'],
                    'createdOn'     => $media_data['insertdate'],
                    'updatedOn'     => $media_data['insertdate'],
                    'status'        => $media_data['status'],
                    'ip'            => $media_data['IP'],
                    'remoteId'      => $media_data['media_id'],
                    'table'         => 'tc_est_video',
                ];

        $model = $this->getModel($model,'',$media_doc['remoteId'],$media_doc['table']);
                
        $model->load($media_doc,'');
        $model->save(false);
        return $model;
    }
}