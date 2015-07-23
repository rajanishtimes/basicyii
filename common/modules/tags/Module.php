<?php

namespace common\modules\tags;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\tags\controllers';
	
	public $defaultRoute = 'tags';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
