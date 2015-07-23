<?php
namespace common\component;

use Yii;
use yii\filters\VerbFilter;

/**
 * DefaultController implements the CRUD actions for Restaurant model.
 */
class TrashController extends AccessController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'restore'       => ['post'],
                    'bulkrestore'   => ['post'],
                    'bulkdelete'    => ['post'],
                ],
            ],
        ]);
    }

    /**
     * Restore ActiveRecord model.
     * If save is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionRestore($id=0)
    {
        $this->findModel($id)->restore();

        return $this->redirect(['index']);
    }
    
    /**
     * Restore Many ActiveRecord model.
     * If save is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkrestore($ids)
    {
        $models = $this->findModels($ids);
        
        foreach($models as $model){
            $model->restore();
        }

        return $this->redirect(['index']);
    }
    
    /**
     * Restore Many ActiveRecord model.
     * If save is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkdelete($ids)
    {
        $models = $this->findModels($ids);
        
        foreach($models as $model){
            $model->delete();
        }

        return $this->redirect(['index']);
    }
}
