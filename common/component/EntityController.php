<?php
namespace common\component;

use Yii;
use yii\filters\VerbFilter;

/**
 * DefaultController implements the CRUD actions for Restaurant model.
 */
class EntityController extends AccessController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'saveasdraft'   => ['post'],
                    'validate'      => ['post'],
                    'publish'       => ['put','post'],
                    //'unpublish'     => ['put','post'],                    
                    'savepublish'   => ['post'],
                    //'delete'        => ['post'],
                    'sendtopublish' => ['put','post'],
                ],
            ],
        ]);
    }

    /**
     * Save as Draft ActiveRecord model.
     * If save is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSaveasdraft($id=0)
    {      
        
         //\yii::trace('in action draft: '.__METHOD__,'theater');
         
        $model = $this->findModel($id);
        //\Yii::trace(print_r($model,true),__METHOD__); 
        if ($model->load(Yii::$app->request->post()) && $model->saveAsDraft()) {
            //return $this->redirect(['view', 'id' => $model->Id]);
            
           //\yii::trace('in action save model: '.__METHOD__,'theater');
           
            //\Yii::trace(print_r(Yii::$app->request->post(),true),__METHOD__);
            
            header("content-type:application/json");
            echo \yii\helpers\Json::encode(['status'=>true]);
            Yii::$app->end();
            
            
            
        }else{
            header("content-type:application/json");
            echo \yii\helpers\Json::encode(['status'=>false]);
            Yii::$app->end();
        }
    }
    
    /**
     * Validate ActiveRecord model and generate error & warning
     * If validation is successful then it will display all other buttons in form
     * @param integer $id
     * @return mixed
     */
    public function actionValidate($id=0)
    {
        $model = $this->findModel($id);        
        $data = $model->checkError(true);        
        
        if(isset($data['errors'])){
            $data['html'] = '';
            foreach($data['errors'] as $type=>$errordata){
                if($type=='error'){
                    $class = 'alert-danger';
                    $icon = 'fa fa-ban';
                }elseif($type=='warning'){
                    $class = 'alert-warning';
                    $icon = 'fa fa-warning';
                }else{
                    $class = 'alert-info';
                    $icon = 'fa fa-info';
                }
                foreach ($errordata as $field => $message){
                    $data['html'] .= \yii\adminUi\widget\Alert::widget([
                        'options' => [
                            'class' => $class.' alert-dismissable',
                        ],
                        'icon' => $icon,
                        'closeButton' => [],
                        'body' => $field.': '.$message,
                    ]);
                    
                }
            }
        }
        
        header("content-type:application/json");
        echo \yii\helpers\Json::encode($data);
        Yii::$app->end();
    }
    
    /**
     * Publish ActiveRecord model
     * If validation is successful then it will display all other buttons in form
     * @param integer $id
     * @return mixed
     */
    public function actionPublish($id)
    {
        
        $this->findModel($id)->published();
        return $this->redirect(['update','id'=>$id]);
        //return $this->redirect(['index']);
    }
    
    /**
     * UnPublish ActiveRecord model
     * If validation is successful then it will display all other buttons in form
     * @param integer $id
     * @return mixed
     */
    public function actionSavesendpublish($id=0)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $model->sendtopublish();
            //return $this->redirect(['view', 'id' => $model->Id]);
        }
    }
    
    /**
     * UnPublish ActiveRecord model
     * If validation is successful then it will display all other buttons in form
     * @param integer $id
     * @return mixed
     */
    public function actionSendtopublish($id)
    {
        $this->findModel($id)->sendtopublish();

        return $this->redirect(['index']);
    }
    
    /**
     * UnPublish ActiveRecord model
     * If validation is successful then it will display all other buttons in form
     * @param integer $id
     * @return mixed
     */
    public function actionUnpublish($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('unpublish');
        if ($model->load(Yii::$app->request->post()) && $model->unpublished()) {            
            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->renderAjax('unpublish', [
                'model' => $model
            ]);
        }
    }
    
    /**
     * Validate ActiveRecord model and generate error & warning
     * If published, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSavepublish($id=0)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->published();
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }
    
    /**
     * Publish in Bulk ActiveRecord model.
     * If save is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkpublish($ids)
    {
        $models = $this->findModels($ids);
        
        foreach($models as $model){
            $model->published();
        }

        return $this->redirect(['index']);
    }
    
    /**
     * UnPublish in Bulk ActiveRecord model.
     * If save is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkunpublish($ids)
    {
        $models = $this->findModels($ids);
        
        foreach($models as $model){
            $model->unpublished();
        }

        return $this->redirect(['index']);
    }
    
    /**
     * Delete in Bulk ActiveRecord model.
     * If save is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkdelete($ids)
    {
        $models = $this->findModels($ids);
        
        foreach($models as $model){
            if($model->checkdraft()){
                $model->softdelete();
            }
        }

        return $this->redirect(['index']);
    }
    
    /**
     * UpdateStatus ActiveRecord model
     * If validation is successful then it will display all other buttons in form
     * @param integer $id
     * @return mixed
     */
    public function actionUpdatestatus($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('statusupdate');
        if ($model->load(Yii::$app->request->post()) && $model->statuspublished()) {            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->renderAjax('statusupdate', [
                'model' => $model
            ]);
        }
    }
}
