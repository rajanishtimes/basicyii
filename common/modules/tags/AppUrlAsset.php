<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


namespace common\modules\tags;

use Yii\UrlAsset\component\UrlAsset;

/**
 * @author Mithun Mandal <mithun12000@gmail.com>
 * @since 2.0
 */
class AppUrlAsset extends UrlAsset
{    
   
     public $url = [
        [
//            'email'=>[
//                'label' => 'Utility', 
//                'url' => ['/#'],
//                'linkOptions'=>[
//                    'class' => 'fa fa-group',
//                ],
//                'items' => [
//                    [
//                        'label' => 'Tags', 
//                        'url' => ['/tags/tags/index'],
//                        'linkOptions'=>[
//                            'class' => 'fa fa-group',
//                        ]
//                    ],  
//                ]
//            ],
            'ztrash'=>[
                'label' => 'Trash Management', 
                'url' => ['/#'],
                'linkOptions'=>[
                    'class' => 'fa fa-trash-o',
                ],
                'items' => [
                   
                    [
                        'label' => 'Tags Trash', 
                        'url' => ['/tags/trash/index'],
                        'linkOptions'=>[
                            'class' => 'fa fa-trash-o',
                        ]
                    ],
                ]
            ]
        ]
    ];
    
    //public $module = ['tags'=>'email','tags/trash'=>'ztrash'];
     public $module = ['tags/trash'=>'ztrash'];
}
