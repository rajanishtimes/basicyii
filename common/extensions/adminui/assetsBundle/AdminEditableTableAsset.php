<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\adminUi\assetsBundle;

use yii\web\AssetBundle;

/**
 * Asset bundle for megnific popup.
 *
 * @author Mukesh Soni<mukesh.soni@timesinternet.in>
 * @since 2.0
 */
class AdminEditableTableAsset extends AssetBundle
{
    public $sourcePath = '@common/extensions/adminui/assets/';
    public $css = [                
    ];
    
    public $js  = [
            'js/editabletable/lib.js',            
    ];
    
    public $depends = [
            'yii\web\JqueryAsset',
            'yii\adminUi\assetsBundle\AdminUiAsset',
    ];     
    
    public function init() {
        parent::init();
    }   
    
}