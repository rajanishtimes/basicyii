<?php
/**
 * @author: Mithun Mandal
 * @created: 01/08/2014 6:03
 * @file: Dropzone
 */

namespace common\modules\media\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\base\Widget;
use common\modules\media\assetsbundle\EmbedAsset;

class EmbedUpload extends Widget
{
    public $url;

    public $options = [];
    
    public $mediaOptions = [];
    
    public $data = [];


    public $inputvar = "media[]";


    public function init()
    {
        if (!isset($this->url)) {            
            $this->url = \Yii::$app->urlManager->createUrl('/media/media/upload');
        }
        parent::init();
    }

    public function run()
    {
        $content = $this->render('__form', array_merge(["url" => $this->url],$this->options));
        echo Html::tag('div', $content, array_merge(['id'=>  $this->id],$this->options));
        $this->registerAjaxScript();
    }
    
    protected function registerAjaxScript()
    {
        $view = $this->getView();
        EmbedAsset::register($view);
        
        
        $options = ArrayHelper::merge([
            'url' => $this->url,
            'preview_selector' => ".video-preview",
            'inputparam' => $this->inputvar,
        ], $this->mediaOptions);
        
        $clientVar = "embed_".  $this->id;
        
        $view->registerJs("var ".$clientVar." = new EmbedUploaded($('#".$this->id."'),".Json::encode($options).")");
        
        $count = 0;
        
        if(isset($this->data) && is_array($this->data)){
            foreach($this->data as $tagassetModels){                
                $data = \yii\helpers\Json::encode($tagassetModels);
            }
            
            $js[] = 'var existing_'.$this->id.' = '.$data;
            $js[] = $clientVar.'.addmedias(existing_'.$this->id.')';

            $view->registerJs(implode("\n", $js));
        }
    }
}