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
 * Asset bundle for bootstrap loading dialog.
 * @link http://bootsnipp.com/snippets/featured/quotwaiting-forquot-modal-dialog
 * @author mukesh soni <mukeshsoni151@gmail.com>
 * @since 2.0
 */
class BootstrapLoaderAsset extends AssetBundle
{
    public $sourcePath = '@common/extensions/adminui/assets/';
    public $css = [               
    ];
    
    public $js  = [
            'js/loader.js',
    ];    
    public $depends = [
            'yii\web\JqueryAsset',
            'yii\adminUi\assetsBundle\AdminUiAsset',
    ];     
}