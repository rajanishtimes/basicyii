gii2-modulegen
==============

Module generation extension for Gii2

By using this extension, you can generate pre-build module.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
$ php composer.phar require mithun12000/gii2-modulegen "*"
```

or add

```
"mithun12000/gii2-modulegen": "*"
```

to the ```require``` section of your `composer.json` file.

## Usage

```php
//if your gii modules configuration looks like below:
$config['modules']['gii'] = 'yii\gii\Module';

//change it to
$config['modules']['gii']['class'] = 'yii\gii\Module';
```

```php
//Add this into backend/config/main-local.php
$config['modules']['gii']['generators'] = [
        'modelgen' => ['class' => 'mithun\modulegen\module\Generator']
    ];
```