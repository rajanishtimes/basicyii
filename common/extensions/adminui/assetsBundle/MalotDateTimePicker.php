<?php
/**
 * @link http://www.malot.fr/bootstrap-datetimepicker/demo.php?utm_source=siteweb&utm_medium=demo&utm_campaign=Site%2BWeb
 */

namespace yii\adminUi\assetsBundle;
use yii;
use yii\web\AssetBundle;
use yii\web\View;
use yii\helpers\Url;

/**
 * Asset bundle for the Twitter bootstrap css files.
 *
 * @author mukesh
 * @since 2.0
 */
class MalotDateTimePicker extends AssetBundle
{
    public $sourcePath = '@common/extensions/adminui/assets/';
    public $css = [               
        'css/malot_datetime/bootstrap-datetimepicker.css',
    ];
    
    public $js  = [
            'js/malot_datetime/bootstrap-datetimepicker.js',
    ];    
     public $depends = [
            'yii\adminUi\assetsBundle\AdminUiAsset',
            'yii\adminUi\assetsBundle\BootstrapLoaderAsset'
         
    ];        
    
}