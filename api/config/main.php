<?php
$params = array_merge(
    require(Yii::getAlias('@common').'/config/params.php'),
    require(Yii::getAlias('@common').'/config/params-local.php'),
    require(__DIR__.'/params.php'),
    require(__DIR__.'/params-local.php')
);

return [
    'id' => 'api',
    'name' => 'API',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['api\component\ApiBootstrap'],
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [                
                '<controller:\w+>.<format:\w+>'=>'<controller>/index',
                '<controller:\w+>/<action:\w+>.<format:\w+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>.<format:\w+>'=>'<module>/<controller>/<action>',
                
                //REST Controll                
                'PUT,PATCH <controller:\w+>/<id>' => '<controller>/update',
                'DELETE <controller:\w+>/<id>' => '<controller>/delete',
                'GET,HEAD <controller:\w+>/<id>' => '<controller>/view',
                'POST <controller:\w+>' => '<controller>/create',
                'GET,HEAD <controller:\w+>' => '<controller>/index',
                '<controller:\w+>/<id>' => 'options',
                '<controller:\w+>' => 'options',
            ]
        ],
        'user' => [
            'class' => 'common\component\User',
            'identityClass' => 'common\models\User',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','trace','info'],
                ],
                [
                    'logFile' => '@runtime/logs/info.log',
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['trace','info'],
                ],
                [
                    'logFile' => '@runtime/logs/warning.log',
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['warning'],
                ],
                [
                    'logFile' => '@runtime/logs/error.log',
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
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
