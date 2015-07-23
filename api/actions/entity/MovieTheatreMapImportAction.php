<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace api\actions\entity;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\rest\Action;

/**
 * CreateAction implements the API endpoint for creating a new model from the given data.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MovieTheatreMapImportAction extends Action
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;
    /**
     * @var string the name of the view action. This property is need to create the URL when the mode is successfully created.
     */
    public $viewAction = 'view';

    /**
     * Creates a new model.
     * @return \yii\db\ActiveRecordInterface the model newly created
     * @throws \Exception if there is any error when creating the model
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /**
         * @var \yii\db\ActiveRecord $model
         */
        $model = new $this->modelClass([
            'scenario' => $this->scenario,
        ]);
        
        $req = Yii::$app->getRequest()->getBodyParams();
        
        if ($model->import($req['doc'])) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            //$response->getHeaders()->set('Location', Url::toRoute([$this->viewAction, 'id' => $id], true));
            //echo "Vanue Import Done.";
        }

        return $model;
    }
}
