<?php

namespace backend\modules\profile\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\User;
use common\models\UpdateUser;
use common\models\UpdatePass;
//use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [                    
                    [
                        'actions' => ['index','update','changepassword'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    public function actionChangepassword()
    {
        if (($model = UpdatePass::findOne(\Yii::$app->user->id)) !== null) {            
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->redirect(['index']);
        } else {
            return $this->render('updatePass', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionUpdate()
    {        
        if (($model = UpdateUser::findOne(\Yii::$app->user->id)) !== null) {            
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else{
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $this->redirect(['index']);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }
    
    
    public function actionIndex()
    {        
        return $this->render('profile', [
            'model' => $this->findModel(\Yii::$app->user->id),
        ]);
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
}
