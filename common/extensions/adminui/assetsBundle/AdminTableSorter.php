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
class AdminTableSorter extends AssetBundle
{
    public $sourcePath = '@common/extensions/adminui/assets/';
    public $css = [               
        'css/tablesorter/blue/style.css',
    ];
    
    public $js  = [
            'js/tablesorter/tablesorter.js',
            'js/tablesorter/pager.js',
    ];    
     public $depends = [
            'yii\web\JqueryAsset',
    ];     
}