<?php

namespace backend\modules\usermanage\controllers;

use Yii;
use common\models\Permission;
use app\models\GroupPermissionSearch;
use common\component\AccessController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\metadata\component\MetaData;
use yii\data\ArrayDataProvider;

/**
 * GrouppermissionController implements the CRUD actions for Permission model.
 */
class GrouppermissionController extends AccessController
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
     * Lists all Permission models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GroupPermissionSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Permission model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Permission model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($groupId)
    {
        $model = new Permission;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['group/view', 'id' => $model->groupId]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'groupId' => $groupId,
                'modulesmap' => new Metadata(),
            ]);
        }
    }

    /**
     * Updates an existing Permission model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id,$groupId)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['group/view', 'id' => $model->groupId]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'groupId' => $groupId,
                'modulesmap' => new MetaData(),
            ]);
        }
    }

    /**
     * Deletes an existing Permission model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id,$groupId)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['group/view', 'id' => $groupId]);
    }

    /**
     * Finds the Permission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Permission the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Permission::findOne($id)) !== null) {
            return $model;
        } else {
            return new Permission;
        }
    }
}
