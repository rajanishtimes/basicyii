<?php
$config = [];

$config['components']['db'] = [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.169.31.167;dbname=whpartner',
            'username' => 'whFire',
            'password' => 'Times@123',
            'charset' => 'utf8',
            'tablePrefix' => 'tc_',
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
