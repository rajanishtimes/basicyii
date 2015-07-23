<?php
$config['components']['db'] = [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.169.31.167;dbname=whcontent',
            'username' => 'whFire',
            'password' => 'Times@123',
            'charset' => 'utf8',
            'tablePrefix' => 'tc_',
        ];
		
$config['components']['whatshot_fontend_db'] = [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.169.31.167;dbname=whweb',
            'username' => 'whFire',
            'password' => 'Times@123',
            'charset' => 'utf8',
            'tablePrefix' => 'wh_',
        ];
$config['components']['fb_db'] = [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.169.31.139;dbname=dev_whatshot',
            'username' => 'tcity',
            'password' => 'tcity@321',
            'charset' => 'utf8',
            'tablePrefix' => 'tc_',
        ];
$config['components']['oldcmsdb'] = [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.169.31.57;dbname=timescity',
            'username' => 'tcity',
            'password' => 'tcity@123',
            'charset' => 'utf8',
        ];

$config['components']['solr_db'] = [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.169.31.139;dbname=whatshot_solr',
            'username' => 'tcity',
            'password' => 'tcity@123',
            'charset' => 'utf8',
            'tablePrefix' => 'tc_',
        ];

$config['components']['mongodb'] = [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb:192.169.29.102:27017/wh_main'
        ];

$config['components']['mailer'] = [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'cmailer.indiatimes.com',
            ],
        ];
   
return $config;