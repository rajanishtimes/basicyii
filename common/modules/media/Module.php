<?php

namespace common\modules\media;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\media\controllers';
	
	public $defaultRoute = 'media';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
