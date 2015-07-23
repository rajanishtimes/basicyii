<?php

$aditionaVendor = dirname(__DIR__).'/extensions';
return [
    'id' => 'Test Application',    
    'bootstrap' => ['common\component\CommonBootstrap'],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'extensions' => array_merge(include (Yii::getAlias('@vendor/yiisoft/extensions.php')),[
        'yii2-urlasset' => 
                [
                  'name' => 'yii2-urlasset',
                  'version' => '9999999-dev',
                  'alias' => 
                  [
                    '@yii/UrlAsset' => $aditionaVendor . '/yii2-urlasset',
                  ],
                ],
        'adminui' => 
                [
                  'name' => 'adminui',
                  'version' => '9999999-dev',
                  'alias' => 
                  [
                    '@yii/adminUi' => $aditionaVendor . '/adminui',
                    '@vendor/adminUi/assets' => $aditionaVendor . '/adminui/assets',
                    '@backend/themes/adminui' => $aditionaVendor . '/adminui/themes',
                  ],
                  'bootstrap' => 'yii\\adminUi\\AdminUiBootstrap',
                ],
        'gii2-modulegen' => 
                [
                  'name' => 'gii2-modulegen',
                  'version' => '9999999-dev',
                  'alias' => 
                  [
                    '@mithun/modulegen' => $aditionaVendor . '/gii-modulegen',
                  ],
                ],
        'yii2-metadata' => 
                [
                  'name' => 'yii2-metadata',
                  'version' => '9999999-dev',
                  'alias' => 
                  [
                    '@yii/metadata' => $aditionaVendor . '/yii2-metadata',
                  ],
                ],
    ]),
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=tc_backend',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix' => 'tc_'
        ],
        'oldcmsdb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=tc_backend',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix' => 'tc_'
        ],
        'log_db'=>[
                   'class' => '\yii\mongodb\Connection',
                   'dsn' => 'mongodb://192.169.29.102:27017/whatshot_log',
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
                'db'=>[
                    'class' => 'yii\mongodb\log\MongoDbTarget',
                    'enabled' => true,
                    'db' =>'log_db',
                    'levels' => ['info','trace', 'warning','error'],
                    'categories' => ['api']
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
        'user' => [
            'class' => 'common\component\User',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'affiliate_mapper'=>[
            'class' => 'common\component\AffiliateMapper',
        ],
        'formatter' => [
            'defaultTimeZone' => 'Asia/Kolkata',
            'timeZone' => 'Asia/Kolkata',
        ]
        /*
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // THIS IS YOUR AUTH MANAGER
            'connectionID'    => 'db',
            'assignmentTable' => 'tc_user',
        ],
         * 
         */        
    ],
];
