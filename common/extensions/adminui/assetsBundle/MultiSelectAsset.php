<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\adminUi\assetsBundle;

use yii\web\AssetBundle;

/**
 * Bootstrap 2 theme for Bootstrap 3.
 *
 * @author Mukesh Soni <mukeshsoni151@gmail.com>
 * @since 2.0
 */
class MultiSelectAsset extends AssetBundle
{
    public $sourcePath = '@common/extensions/adminui/assets/';
    public $baseUrl = '@web';
    public $js  = [
            /*'js/multiselect/multiselect.js',
            'js/multiselect/multiselect.filter.js',*/
            'js/multiselect/bootstrap-multiselect.js'
    ];
    public $css = [
        /*'css/multiselect/multiselect.css',
        'css/multiselect/multiselect.filter.css',*/
        'css/multiselect/bootstrap-multiselect.css'
    ];
    public $depends = [
        'yii\adminUi\assetsBundle\AdminUiAsset',
        //'yii\adminUi\assetsBundle\JqueryUI'
    ];
}
