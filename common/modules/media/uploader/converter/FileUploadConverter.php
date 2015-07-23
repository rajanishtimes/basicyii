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
use yii\helpers\FileHelper;
use common\models\Asset;

use cdn_service\WhImage;

class FileUploadConverter extends UploadMethods implements UploaderInterface{    
    use UploadTrait;
    public $uploadvar = 'file';
    
    public $uploadPath = '@media';
    
    public $uri = '/';  
    
    /**
     * @inheritdoc
     */
    public function convert($model, $data=[],$path=''){        
        //to check single file upload or not.
        \yii::trace('running file upload');
        
        if($path){
            $path = '/'.$path.'/'.date('Y').'/'.date('M');
        }else{
            $path = '/'.date('Y').'/'.date('M');
        }
        
        $newfilename = Asset::getNewFileName($_FILES[$this->uploadvar]['name']);
        $hashPath = WhImage::getPath($newfilename);
        $uploadPath = Yii::getAlias($this->uploadPath).'/'.$hashPath;
        
        \yii::trace($uploadPath);
        FileHelper::createDirectory($uploadPath, 0777);
        $uri = $path;  
        
        if (isset($_FILES[$this->uploadvar]) && isset($_FILES[$this->uploadvar]['name'])) {
            $file = UploadedFile::getInstanceByName($this->uploadvar);
            
            if ($file->saveAs($uploadPath . '/' . $newfilename)) {
                //Now save file data to database
                $model = $this->getModel($model);
                
                $model->filename = $file->name;
                //$model->path = $this->uploadPath .$path. '/' . $newfilename;
                
                $model->path = $this->uploadPath.'/' .$hashPath.$newfilename;
                $model->uri = $uri . '/' . $newfilename;
                $model->description = '';
                $model->mimetype = $file->type;
                $model->source = 'upload';
                $model->mediahash = md5_file ( $uploadPath . '/' . $newfilename);
		$model->status = 1;
                
                try{
                    $exifReader = new ExifReader;
                    
                    $exifReader->file = $model->path;
                    $data = $exifReader->getExifData(array(
                        array('FILE', array('SectionsFound')),
                        'COMPUTED',
                        'THUMBNAIL',
                        'GPS',
                        array('IFD0', array('UndefinedTag:0xC4A5')),
                        array('EXIF', array('MakerNote')),
                    ));
                    $data = \yii\helpers\Json::encode($data);
                    $model->metainfo = $data;
                }  catch (Exception $e){                    
                }

                $model->save();
                
                return $model;
            }
        }
    }
}