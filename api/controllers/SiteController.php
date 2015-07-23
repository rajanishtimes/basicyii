<?php
namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
/**
 * Site controller
 */
class SiteController extends ActiveController
{
    public $modelClass = 'common\models\User';  
    /*public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];//*/
    
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\rest\OptionsAction',
            ],
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }
}
