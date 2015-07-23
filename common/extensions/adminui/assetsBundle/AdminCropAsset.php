<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\adminUi\assetsBundle;
use yii;
use yii\web\AssetBundle;
use yii\web\View;
use yii\helpers\Url;

/**
 * Asset bundle for the Twitter bootstrap css files.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AdminCropAsset extends AssetBundle
{
    public $sourcePath = '@common/extensions/adminui/assets/';
    public $css = [               
        'css/cropper.css',
    ];
    
    public $publishOptions = [
        'forceCopy' => true,
    ];
    
    public $js  = [
            'js/cropper/cropper.js',
            'js/cropper/custom.js',
    ];    
     public $depends = [
            'yii\web\JqueryAsset',
            'yii\adminUi\assetsBundle\AdminUiAsset',
            'yii\adminUi\assetsBundle\AdminPopupAsset',
            'yii\adminUi\assetsBundle\JqueryUI',
            'common\modules\media\assetsbundle\MediaAsset',
            'yii\adminUi\assetsBundle\BootstrapLoaderAsset'
         
    ];     
     
    public static function register($view) {
        parent::register($view);
        $view->registerJs('var img_url = "'.Yii::$app->params['imagepreviewurl'].'";',View::POS_HEAD);
        $view->registerJs('var media_action = "'.Url::home().'media/media/'.'";',View::POS_HEAD);
    }
}