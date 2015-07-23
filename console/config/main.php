<?php
$params = array_merge(
    require(Yii::getAlias('@common').'/config/params.php'),
    require(Yii::getAlias('@common').'/config/params-local.php'),
    require(__DIR__.'/params.php'),
    require(__DIR__.'/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['console\component\ConsoleBootstrap'],
    'controllerNamespace' => 'console\controllers',
    'modules' => [],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['yii\db\Command::query'],
                ],
            ],
        ],    
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl' =>  $params['cmsurl'],
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
    ],
    'params' => $params,
];
