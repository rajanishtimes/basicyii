<?php

namespace common\modules\tags\controllers;

use Yii;
use common\models\Tags;
use common\models\tagssearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use common\models\Tagmap;
use common\models\Event;
use common\models\Venue;
use common\models\Content;

/**
 * TagsController implements the CRUD actions for tags model.
 */
class TagsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all tags models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new tagssearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $data = [];
            foreach($dataProvider->getModels() as $model){
                $data[] = ['id'=>$model->name, 'text' => $model->name];
            }
            return $data;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single tags model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new tags model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tags();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing tags model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing tags model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->softdelete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the tags model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return tags the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tags::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionTagSearch(){
        $searchModel = new tagssearch(['scenario'=>'search']);
        $param = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->searchEntity($param);
        return $this->render('tag_search',['dataProvider'=>$dataProvider,'searchModel'=>$searchModel]);
    }
    
    
    public function actionAutoEntitySearch($search = null,$etype=100){        
        Yii::$app->response->format = Response::FORMAT_JSON;
        switch($etype){
            case 100:
                $query = Event::find();
                break;
            case 200:
                $query = Venue::find();
                break;
            case 300:
                $query = Content::find();
                break;
        }
        
        if($search){
           $query->andFilterWhere(['like', 'name',$search.'%',false]);
        }
        $query->andFilterWhere(['status' => \common\component\AppActiveRecord::STATUS_PUBLISH,]);
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
        
        $data = [];
        foreach($dataProvider->getModels() as $model){
            $NameString = $model->id.' - '.$model->name;
            $data[] = ['id'=>$model->id, 'text' => $NameString];
        }
        return $data; 
    }
    
    public function actionAddTagToEntity(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = ['success'=>0,'req_msg'=>'Invalid Request'];
        $postdata = Yii::$app->request->post();
        $tag_array = explode(',', $postdata['tags']);
        foreach($tag_array as $t){
            $tagModel = Tags::findOne(['name'=>$t]);
            if($tagModel){
                $tagMap = Tagmap::findOne(['tagId'=>$tagModel->Id,'entityId'=>$postdata['entity_id'],'entityType'=>$postdata['entity_type']]);
                if(!$tagMap){
                    $tagMap = new Tagmap();
                    $tagMap->tagId = $tagModel->Id;
                    $tagMap->entityId = $postdata['entity_id'];
                    $tagMap->entityType = $postdata['entity_type'];
                    $tagMap->save(false);
                    $response['success'] = 1;
                    $response['req_msg'] = 'Tag added to entity';
                }
                else{
                    $response['success'] = 1;
                    $response['req_msg'] = 'Tag already added on entity';
                }
            }
            else{
                $response['req_msg'] = 'Tag was not created';
            }
        }
        return $response;
    }
    
}
