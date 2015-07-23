<?php
$params = array_merge(
    require(Yii::getAlias('@common').'/config/params.php'),
    require(Yii::getAlias('@common').'/config/params-local.php'),
    require(__DIR__.'/params.php'),
    require(__DIR__.'/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'class' => 'common\component\User',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
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
