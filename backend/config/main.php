<?php
ini_set("memory_limit",-1);
$params = array_merge(
    require(Yii::getAlias('@common').'/config/params.php'),
    require(Yii::getAlias('@common').'/config/params-local.php'),
    require(__DIR__.'/params.php'),
    require(__DIR__.'/params-local.php')
);

return [
    'id' => 'app-backend',
    'name' => 'Whatshot',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['backend\component\BackendBootstrap'],
    'controllerNamespace' => 'backend\controllers',
    'modules' => [       
        'usermanage' => [
            'class' => 'backend\modules\usermanage\Module',
        ],
        'profile' => [
            'class' => 'backend\modules\profile\Module',
        ],
        /*
        'country' => [
            'class' => 'backend\modules\country\Module',
        ],
        'state' => [
            'class' => 'backend\modules\state\Module',
        ],
        'city' => [
            'class' => 'backend\modules\city\Module',
        ],
        'zone' => [
            'class' => 'backend\modules\zone\Module',
        ],
        'locality' => [
            'class' => 'backend\modules\locality\Module',
        ],*/
    ],
    'components' => [
        'assetManager' => [
            'bundles' => [
                /*'yii\web\JqueryAsset' => [
                     'sourcePath' => null,
                     'js' => []
                ],//*/
                'yii\bootstrap\BootstrapAsset' => [
                     'sourcePath' => null,
                     'css' => []
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                     'sourcePath' => null,
                     'js' => []
                ],
                'yii\grid\GridViewAsset' => [
                    'depends'   => [
                        'backend\assets\AppAsset'
                    ],
                ],
            ],            
            'linkAssets' => true,
        ],        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [                
                'dashboard' => 'site/index',
                'login' => 'site/login',
                'forgot' => 'site/forgot',
                'reset-password' => 'site/reset-password',
                'profile'   => 'profile/default/index',
                'profile/update' => 'profile/default/update',
                'profile/changepassword' => 'profile/default/changepassword',
            ]
        ],        
        'authManager' => [
            'class' => 'common\component\PermissionManager',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile'=>'@runtime/logs/writeModelErrorLog.log',
                    'levels' => ['error', 'warning'],
                    'categories' => ['writemodel'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'request' => [
            'class' => 'yii\web\Request', // THIS IS YOUR AUTH MANAGER
            'cookieValidationKey' => 'ghsj&s_5{g#',
        ],
    ],
    'params' => $params,
];
