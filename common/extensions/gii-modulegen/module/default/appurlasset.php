<?php
/**
 * This is the template for generating a module class file.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

$className = $generator->moduleClass;
$moduleName = $generator->moduleID;
$controllerClass = $generator->getControllerID();
$pos = strrpos($className, '\\');
$ns = ltrim(substr($className, 0, $pos), '\\');
$className = substr($className, $pos + 1);

echo "<?php\n";
?>
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


namespace <?= $ns ?>;

use Yii\UrlAsset\component\UrlAsset;

/**
 * @author Mithun Mandal <mithun12000@gmail.com>
 * @since 2.0
 */
class AppUrlAsset extends UrlAsset
{    
   
     public $url = [
        [
            '<?=$moduleName?>'=>[
                'label' => '<?=ucfirst($moduleName)?> Management', 
                'url' => ['/#'],
                'linkOptions'=>[
                    'class' => 'fa fa-group',
                ],
                'items' => [
                    [
                        'label' => '<?=ucfirst($moduleName)?>', 
                        'url' => ['/<?= $controllerClass ?>'],
                        'permission' => '/<?=$moduleName?>/<?= $controllerClass ?>/index',
                        'linkOptions'=>[
                            'class' => 'fa fa-group',
                        ]
                    ],  
                ]
            ],
            'ztrash'=>[
                'items' => [
                   
                    [
                        'label' => '<?=ucfirst($moduleName)?> Trash', 
                        'url' => ['/<?=$moduleName?>/<?=$generator->getControllerID(true)?>/index'],
                        'linkOptions'=>[
                            'class' => 'fa fa-trash-o',
                        ]
                    ],
                ]
            ]
        ]
    ];
    
    public $module = ['<?=$moduleName?>'=>'<?=$moduleName?>','<?=$moduleName?>/<?=$generator->getControllerID(true)?>'=>'ztrash'];
}