<?php
$config = [];
if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';
    $config['modules']['gii'] = 'yii\gii\Module';
}

$config['components']['db'] = [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.150.243.174;dbname=tc_backend',
            'username' => 'tcity',
            'password' => 'tcity@321',
            'charset' => 'utf8',
			'tablePrefix' => 'tc_',
        ];
return $config;