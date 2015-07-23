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
        'message' => [
            'class' => 'backend\modules\message\Module',
        ],
        'event' => [
            'class' => 'backend\modules\event\Module',
        ],
        
	
        'content' => [
            'class' => 'backend\modules\content\Module',
        ],
        'splpages' => [
            'class' => 'backend\modules\splpages\Module',
        ],
       'populartags' => [
            'class' => 'backend\modules\populartags\Module',
        ],
        
        'criticreviews' => [
            'class' => 'backend\modules\criticreviews\Module',
        ],
        'criticuser' => [

            'class' => 'backend\modules\criticuser\Module',

        ],
       /*
        'email' => [
            'class' => 'backend\modules\email\Module',
        ],
        
        * 
        */
        'venue' => [
            'class' => 'backend\modules\venue\Module',
        ],
        'source' => [
            'class' => 'backend\modules\source\Module',
        ],
        'sponser' => [
            'class' => 'backend\modules\sponser\Module',
        ],
        'question' => [
            'class' => 'backend\modules\question\Module',
        ],
		'feed' => [
            'class' => 'backend\modules\feed\Module',
        ], 
	'tags' => [
            'class' => 'common\modules\tags\Module',
        ],        
        'profile' => [
            'class' => 'backend\modules\profile\Module',
        ],
        /*
        'eventcategory' => [
            'class' => 'backend\modules\eventcategory\Module',
        ],
        
         * 
         */
        
        'seo' => [
            'class' => 'backend\modules\seo\Module',
        ],
        'usermanage' => [
            'class' => 'backend\modules\usermanage\Module',
        ],
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
        ],
        'cuisine' => [
            'class' => 'backend\modules\cuisine\Module',
        ],        
        'metadata' => [
            'class' => 'yii\metadata\Module',
        ],
        
        'media' => [
            'class' => 'common\modules\media\Module',
        ],
        
        'audit-trail' => [
            'class' => 'backend\modules\audittrail\Module',
        ],
         
        'constants' => [
            'class' => 'backend\modules\constants\Module',
        ],
        'serverconstants' => [

            'class' => 'backend\modules\serverconstants\Module',

        ],
        'pushnotification' => [
            'class' => 'backend\modules\pushnotification\Module',
        ],
        
        'report' => [
            'class' => 'backend\modules\report\Module',
        ],
        'broadcast' => [
            'class' => 'backend\modules\broadcast\Module',
        ],    
        
        /*
        'syncsolr' => [
            'class' => 'backend\modules\syncsolr\Module',
        ],
         * 
         */
        'feature' => [
            'class' => 'backend\modules\feature\Module',
        ],
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
