<?php
$config = [];
if (!YII_ENV_TEST) {
    $config['modules']['debug'] = 'yii\debug\Module';
}
return $config;