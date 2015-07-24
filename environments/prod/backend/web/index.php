<?php
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');
defined('REALM') or define('REALM', 'TmCt');
error_reporting(E_ALL & ~E_NOTICE);

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/aliases.php');

$config = yii\helpers\ArrayHelper::merge(
    require(Yii::getAlias('@common').'/config/main.php'),
    require(Yii::getAlias('@common').'/config/main-local.php'),
    require(Yii::getAlias('@backend').'/config/main.php'),
    require(Yii::getAlias('@backend').'/config/main-local.php')
);
/*
echo '<pre>';
print_r($config);
echo '</pre>';
///*/
$application = new yii\web\Application($config);
$application->run();
