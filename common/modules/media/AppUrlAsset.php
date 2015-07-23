<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


namespace common\modules\media;

use Yii\UrlAsset\component\UrlAsset;

/**
 * @author Mithun Mandal <mithun12000@gmail.com>
 * @since 2.0
 */
class AppUrlAsset extends UrlAsset
{    
   
     public $url = [
        [
            'email'=>[
                'label' => 'Content Modules', 
                'url' => ['/#'],
                'linkOptions'=>[
                    'class' => 'fa fa-group',
                ],
                'items' => [
                    [
                        'label' => 'Media', 
                        'url' => ['/media/media/index'],
                        'linkOptions'=>[
                            'class' => 'fa fa-group',
                        ]
                    ],  
                ]
            ],
            'ztrash'=>[
                'label' => 'Trash Management', 
                'url' => ['/#'],
                'linkOptions'=>[
                    'class' => 'fa fa-trash-o',
                ],
                'items' => [
                   
                    [
                        'label' => 'Media Trash', 
                        'url' => ['/media/trash/index'],
                        'linkOptions'=>[
                            'class' => 'fa fa-trash-o',
                        ]
                    ],
                ]
            ]
        ]
    ];
    
    public $module = ['media'=>'email','media/trash'=>'ztrash'];
}