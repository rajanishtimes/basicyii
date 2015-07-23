<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'local');
defined('REALM') or define('REALM', 'TmCt');
define('APPAPI', 'http://dotdot.appapi.whatshot.in/appapi/');
define('CMSAPI', 'http://api.devcms.whatshot.in/');
define('SOLRURL', 'http://192.169.34.184:8886/whatshot/events/dataimport?command=full-import&clean=true');
define('APP_API_DIGEST','admin:b864341098e300bc5bba03');


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
