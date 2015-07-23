<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


namespace backend\modules\usermanage;
use Yii;
use Yii\UrlAsset\component\UrlAsset;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppUrlAsset extends UrlAsset
{    
    public $url = [
        [
            'usermanage'=>[
                'label' => 'Admin Management', 
                'url' => ['/usermanage/group/index'],
                'linkOptions'=>[
                    'class' => 'fa fa-user',
                ],
                'items' => [
                    [
                        'label' => 'User', 
                        'url' => ['/usermanage/user/index'],
                        'linkOptions'=>[
                            'class' => 'fa fa-user',
                        ]
                    ],  
                    [
                        'label' => 'Group', 
                        'url' => ['/usermanage/group/index'],
                        'linkOptions'=>[
                            'class' => 'fa fa-group',
                        ]
                    ], 
                    [
                        'label' => 'User Trash', 
                        'url' => ['/usermanage/user-trash/index'],
                        'linkOptions'=>[
                            'class' => 'fa fa-trash-o',
                        ]
                    ],
                    [
                        'label' => 'Group Trash', 
                        'url' => ['/usermanage/group-trash/index'],
                        'linkOptions'=>[
                            'class' => 'fa fa-trash-o',
                        ]
                    ],
                ]
            ]
        ]
    ];
    
    
    public $module = ['usermanage'=>'usermanage'];
}