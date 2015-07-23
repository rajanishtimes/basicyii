<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use Yii\UrlAsset\component\UrlAsset;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppUrlAsset extends UrlAsset
{    
    public $url = [
        [
            'dashboard'=>[
                'label' => 'Dashboard', 
                'url' => ['/site/index'],
                'linkOptions'=>[
                    'class' => 'fa fa-dashboard',
                ]
            ],
        ]
    ];
    
    public $module = ['site'=>'dashboard'];
}