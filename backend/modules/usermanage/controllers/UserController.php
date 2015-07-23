<?php

namespace backend\modules\usermanage\controllers;

use Yii;
use common\models\User;
use common\models\CreateUser;
use common\models\UpdateUser;
use common\models\UpdatePass;
use common\models\SetPass;
use yii\data\ActiveDataProvider;
use app\models\UserSearch;
//use yii\web\Controller;
use common\component\AccessController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;
use yii\web\Response;
use yii\widgets\ActiveForm;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AccessController
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
     * Lists all User models.
     * @return mixed
     */
    public function actionValidate($id =0)
    {
        if($id){
            if (($model = UpdateUser::findOne($id)) !== null) {            
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }else{
            $model = new CreateUser;
        }
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }
    

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {        
        $searchModel = new UserSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CreateUser;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (($model = UpdateUser::findOne($id)) !== null) {            
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->softdelete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionSetpassword($id)
    {
        if (($model = SetPass::findOne($id)) !== null) {            
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        } else {
            return $this->render('setPass', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionUpdategroups($id) {        
        $models = \common\models\UpdateGroup::findOne($id);
        
        if ($models->load(Yii::$app->request->post()) && $models->saveAll()) {
            return $this->redirect(['view', 'id' => $models->Id]);
        } else {
            return $this->render('updategroup', [
                'model' => $models,
            ]);
        }
    }
}
