<?php
/**
 * @author: Mithun Mandal
 * @created: 01/08/2014 6:03
 * @file: Dropzone
 */

namespace common\modules\media\widgets;

use common\modules\media\assetsbundle\MediaAsset;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\base\ErrorException;
use yii\adminUi\widget\Modal;
use yii\web\JsExpression;
use common\helpers\UtilityHelper;

class MediaUpload extends \yii\base\Widget
{
    
    const CHECK_MIN_DIMENSION = 1;
    
    const CHECK_MAX_DIMENSION = 2;
    
    const CHECK_RATIO_DIMENSION = 3;

    public $data;
    
    public $previewurl;
    
    public $detailview;
    
    public $maxFilesize = 7;
    
    public $maxFiles = 99;

    public $url;
    
    public $path;

    public $options = [];
    
    public $clientEvents = [];
    
    public $acceptedFiles = 'image/*';
    
    public $inputvar = "media[]";
    
    public $rejectmessage = '';
    
    public $checkmindimension = false;
    
    public $checkmindimensiontype = false;
    
    public $Width = 400;
    
    public $Height = 300;
    
    public $thumbWidth = 100;
    public $thumbHeight = 100;
    
    private $ratio = 1;


    public function init()
    {
        $this->maxFilesize = (int) ini_get('upload_max_filesize');
        if (!isset($this->url)) {
            if($this->path){
                $this->url = \Yii::$app->urlManager->createUrl(['/media/media/upload', 'path' => $this->path]);
            }else{
                $this->url = \Yii::$app->urlManager->createUrl('/media/media/upload');
            }
        }
        
        if(!isset($this->detailview)){
            $this->detailview = \Yii::$app->urlManager->createAbsoluteUrl('/media/media/view');
        }
        
        /*
        if(){
            
        }
        //*/
        
        if(!isset($this->clientEvents['removedfile'])){
            $this->clientEvents['removedfile'] = "function(file){
                                                    $.post(\"".\Yii::$app->urlManager->createUrl('/media/media/delete')."?id=\"+file.id,
                                                        {id:file.id,'".Yii::$app->request->csrfParam."':'".Yii::$app->request->getCsrfToken() ."'},
                                                        function(data){}
                                                    );                                                    
                                                  }";
        }
        
        if(!isset($this->clientEvents['sending'])){
            $this->clientEvents['sending'] = "function(file, xhr, formData){
                                                formData.append('".Yii::$app->request->csrfParam."','".Yii::$app->request->getCsrfToken() ."')
                                              }";
        }
        
        if(!isset($this->clientEvents['success'])){
            $this->clientEvents['success'] = "function(file,responseText,error){
                                                console.log(responseText);
                                                file.caption = responseText.description; 
                                                file.mediahash = responseText.mediahash;
                                                file.id = responseText.Id;
                                                if (file.previewElement) {
                                                    _ref = file.previewElement.querySelectorAll(\"[data-dz-id]\");
                                                    //console.log(_ref);
                                                    if (typeof image_array != 'undefined'){
							image_array.push(responseText.uri);
                                                    }
                                                    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                                                      node = _ref[_i];
                                                      node.value = responseText.Id;
                                                    }
                                                    return file.previewElement.classList.add(\"dz-success\");
                                                }
                                             }";
        }
        
        
        if($this->checkmindimension){            
            switch ($this->checkmindimensiontype){
                case self::CHECK_MIN_DIMENSION : 
                                                $this->clientEvents['init'] = "function() {
                                                    // Register for the thumbnail callback.
                                                    // When the thumbnail is created the image dimensions are set.
                                                    this.on(\"thumbnail\", function(file) {
                                                      // Do the dimension checks you want to do
                                                      if (file.width < ".$this->Width." || file.height < ".$this->Height.") {
                                                        file.rejectDimensions()
                                                      }
                                                      else {
                                                        file.acceptDimensions();
                                                      }
                                                    });
                                                  }";
                                                $this->rejectmessage = ($this->rejectmessage!='') ? $this->rejectmessage : "Please upload Bigger dimension (".$this->Width."x".$this->Height.")";
                                                break;
                case self::CHECK_MAX_DIMENSION : 
                                                $this->clientEvents['init'] = "function() {
                                                    // Register for the thumbnail callback.
                                                    // When the thumbnail is created the image dimensions are set.
                                                    this.on(\"thumbnail\", function(file) {
                                                      // Do the dimension checks you want to do
                                                      if (file.width > ".$this->Width." || file.height > ".$this->Height.") {
                                                        file.rejectDimensions()
                                                      }
                                                      else {
                                                        file.acceptDimensions();
                                                      }
                                                    });
                                                  }";
                                                $this->rejectmessage = ($this->rejectmessage!='') ? $this->rejectmessage : "Please upload Smaller dimension (".$this->Width."x".$this->Height.")";
                                                break;
                                            
                case self::CHECK_RATIO_DIMENSION :
                                
                                                $this->ratio = $this->Width/$this->Height;
                                                $this->clientEvents['init'] = "function() {
                                                    // Register for the thumbnail callback.
                                                    // When the thumbnail is created the image dimensions are set.
                                                    this.on(\"thumbnail\", function(file) {
                                                      // Do the dimension checks you want to do
                                                      ratio = file.width/file.height;
                                                      if (ratio != ".$this->ratio.") {
                                                        file.rejectDimensions()
                                                      }
                                                      else {
                                                        file.acceptDimensions();
                                                      }
                                                    });
                                                  }";
                                                $this->rejectmessage = ($this->rejectmessage!='') ? $this->rejectmessage : "Please upload equel dimension (".$this->ratio.":1)";
                                                break;
            }
            
            $this->options['accept'] = new JsExpression('function(file, done) {
                                        file.acceptDimensions = done;
                                        file.rejectDimensions = function() { done("'.$this->rejectmessage.'"); };
                                      }');
        }

        parent::init();
    }

    public function run()
    {
        echo Html::beginTag('div', ['class' => 'dropzone', 'id' => $this->id]);
        echo Html::endTag('div');
        /*
        echo Modal::widget([
            'options' =>[
                'id' => 'dropzone-modal',
            ],
            'closeButton' => [
                'tag' => 'button',
                'label' => '&times;'
            ],
        ]);
        //*/
        $this->registerClientScript();
    }

    public function registerClientScript()
    {
        
        $view = $this->getView();
        try{
        MediaAsset::register($view);
        }catch(ErrorException $e)
        {
            echo $e->getMessage();
            
        }
        $options = ArrayHelper::merge([
            'url'               => $this->url,
            'parallelUploads'   => true,
            'inputparam'        => $this->inputvar,
            'maxFilesize'       => $this->maxFilesize,
            'maxFiles'          => $this->maxFiles,
            'acceptedFiles'     => $this->acceptedFiles,
            'detailviewurl'     => $this->detailview,
        ], $this->options);

        $options = Json::encode($options);
        $js = [];
        $js[] = "Dropzone.autoDiscover = false;";
        $js[] = "var $this->id = new Dropzone('div#$this->id',{$options});";
        if (!empty($this->clientEvents)) {
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = ";$this->id.on('$event', $handler);";;
            }
        }
        
        $count = 0;
        if(isset($this->data) && is_array($this->data)){
            foreach($this->data as $tagassetModels){
                $count += count($tagassetModels);
                foreach($tagassetModels as $assetModel){
                    $data = $assetModel->getattributes();
                    //print_r($assetModel->getattributes());
                    $filesize = @filesize(Yii::getAlias($data['path']));
                    if(!$filesize) $filesize = 0; 
                    $js[] = 'var mockFile = {id:"'.$data['Id'].'", name: "'.$data['filename'].'", size: '.$filesize.',is_cropped:'.$data['is_cropped'].',is_cover:'.$data['is_cover'].'};';
                    // Call the default addedfile event handler
                    $js[] = $this->id.'.emit("addedfile", mockFile);';
                    if(UtilityHelper::isInternalUrl($data['uri'])){
                        $js[] = $this->id.'.emit("thumbnail", mockFile,"'.$this->previewurl.$data['uri'].'?w='.$this->thumbWidth.'&h='.$this->thumbHeight.'");';
                    }
                    else{
                        $js[] = $this->id.'.emit("thumbnail", mockFile,"'.$data['uri'].'");';
                    }
                }
            }
            $js[] = 'var existingFileCount = '.$count.';';
        }
        //$js[] = $this->id.'.options.maxFiles = myDropzone.options.maxFiles - existingFileCount;';
        $view->registerJs(implode("\n", $js));
    }
} 