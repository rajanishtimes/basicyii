<?php

namespace common\modules\media\controllers;

use Yii;
use common\models\Asset;
use common\models\Searchasset;
use common\modules\media\components\ExifReader;
use common\modules\media\uploader\UploadMethods;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\library\Cropper;
use common\helpers\UtilityHelper;

use cdn_service\WhImage;

/**
 * MediaController implements the CRUD actions for Asset model.
 */
class MediaController extends Controller
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
     * Lists all Asset models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Searchasset();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Asset model.
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
     * Creates a new Asset model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpload($path='')
    {        
        $upload = new UploadMethods();
        $model = $upload->upload([], $path);
        
        if($model !== null){
            header("content-type:application/json");
            echo \yii\helpers\Json::encode($model);
            Yii::$app->end();
        }else{
            return false;
        }
    }

    /**
     * Creates a new Asset model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Asset();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->Id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Asset model.
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
     * Deletes an existing Asset model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
        
        if(Yii::$app->request->isAjax){
            return true;
        }else{
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Asset model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Asset the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Asset::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionGetcrop($id){
        $model = $this->findModel($id);
        
        if(Yii::$app->request->post()){
            $msg = NULL;
            $src = Yii::getAlias($model->path);
            $post_data =  Yii::$app->request->post();
            $crop_data = $post_data['Asset']['crop_data'];
            $crop_box = $post_data['Asset']['box_cordinate'];
            $crop_arr = json_decode($post_data['Asset']['crop_data'],true);
            $box_arr = json_decode($post_data['Asset']['box_cordinate'],true);
            $merger_str = json_encode(['img_data'=>$crop_arr,'box_data'=>$box_arr]);
            /*
             * Note : In case:  assset stored on our server then we'll crop it too also.
             */
            if(UtilityHelper::isInternalUrl($model->uri)){
                if($model->is_cropped == 0){
                    $orgpath = Yii::getAlias(str_replace('@media','@media_org',$model->path));
                    $dirname = dirname($orgpath);
                    if(!file_exists($dirname)){
                        if(mkdir($dirname,0755,true)){
                            @copy($src,$orgpath);
                        }
                    }
                }
                $crop = new Cropper($src, $crop_data);
                $msg = $crop -> getMsg();
                if(empty($msg)){
                    //$model->cropdata = $merger_str;
                    WhImage::clearCache(basename($model->uri));
                    $model->cropdata = ''; 
                    $model->is_cropped = 1;
                    $model->update();
                }
            }
            else{
                /*
                 * Note : In case : third party url asset, We are just storing its crop co-ordinates.
                 */
                $model->cropdata = $merger_str;
                $model->is_cropped = 1;
                $model->update();
            }
            $response = array(
                'state'  => 200,
                'message' => $msg,
                //'result' => $crop -> getResult()
            );
            echo \yii\helpers\Json::encode($response);die;
        }        
        return $this->renderAjax('cropui',['model'=>$model]);
    }
    
    
    /*
     * @author : Mukesh Soni <mukeshsoni151@gmail.com>
     * @description : this will update order of assets in asset table's sort_order field after re-ordering.
     * @since:  March 02, 2015
     */
    public function actionSaveOrder(){
        $response = ['error'=>1,'req_msg'=>'Invalid Request'];
        $req = Yii::$app->request->post();
        $ids = explode(',', $req['ids']);
        $conn = Yii::$app->getDb();
        foreach($ids as $order => $id){
            $comm = $conn->createCommand('update tc_asset set sort_order = '.$order.' where id = '.$id)->execute();
        }
        $response['error'] = 0;
        $response['req_msg'] = 'success';
        return \yii\helpers\Json::encode($response);
    }
    
    /*
     * @author : Mukesh Soni <mukeshsoni151@gmail.com>
     * @description : This is used to mark an asset as cover image
     * @param $id : id of assest.
     */
    public function actionSetCover(){
        $response = ['error'=>1,'req_msg'=>'Invalid Request'];
        $req = Yii::$app->request->post();
        $conn = Yii::$app->getDb();
        $cover_id = $req['cover_id'];
        $uncover_id = !empty($req['uncover_id']) ? $req['uncover_id'] : [];
        $r = $conn->createCommand('update tc_asset set is_cover = 1, sort_order = 0 where id = '.$cover_id)->execute();
        if(!empty($uncover_id)){
            foreach($uncover_id as $order => $id){
                if(is_numeric($id)){
                    $r = $conn->createCommand('update tc_asset set is_cover = 0 where id = '.$id)->execute();
                    $comm = $conn->createCommand('update tc_asset set sort_order = '.($order+1).' where id = '.$id)->execute();
                }
            }
        }
        $response['error'] = 0;
        $response['req_msg'] = 'success';
        return \yii\helpers\Json::encode($response);
    }
    
    public function actionIsCropped($id){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = Asset::findOne(['id'=>$id]);
        $res = ['success'=>0];
        if($model){
            if($model->is_cropped){
                $res['success'] = 1;
            }
        }
        else{
            throw  new NotFoundHttpException('File not exist');
        }
        return $res;
    }
 
}
