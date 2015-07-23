<?php

namespace backend\modules\usermanage\controllers;

use Yii;
use common\models\Group;
use common\models\GroupTrashSearch;
use common\component\AccessController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GroupTrashController implements the CRUD actions for group model.
 */
class GroupTrashController extends AccessController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ]);
    }

    /**
     * Lists all group models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GroupTrashSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Deletes an existing group model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    
    public function actionRestore($id) {
        $this->findModel($id)->restore();

        return $this->redirect(['index']);
    }

    /**
     * Finds the group model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return group the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Group::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
