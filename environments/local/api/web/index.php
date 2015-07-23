<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'local');
defined('REALM') or define('REALM', 'TmCt');

error_reporting(E_ALL & ~E_NOTICE);
define('APPAPI', 'http://appapi.whatshot.in/appapi/');
require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/aliases.php');

$config = yii\helpers\ArrayHelper::merge(
    require(Yii::getAlias('@common').'/config/main.php'),
    require(Yii::getAlias('@common').'/config/main-local.php'),
    require(Yii::getAlias('@api').'/config/main.php'),
    require(Yii::getAlias('@api').'/config/main-local.php')
);
/*
echo '<pre>';
print_r($config);
echo '</pre>';
///*/
$application = new yii\web\Application($config);
$application->run();
