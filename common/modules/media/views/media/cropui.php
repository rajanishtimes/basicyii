<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\UtilityHelper;
use common\models\EntityType;

    $queryParams = Yii::$app->request->queryParams;
    $entityType = isset($queryParams['entityType']) ? $queryParams['entityType'] : 0;
    $img_domain = Yii::$app->params['img_domain'];
    $previewUrl = Yii::$app->params['imagepreviewurl'];
    $box_data = $img_data = '';
    $show_url = '';
    if(UtilityHelper::isInternalUrl($model->uri)){
        $url = $previewUrl.$model->uri;
        $show_url = $img_domain.$model->uri;
    }
    else{
        $show_url = $url = $model->uri;
        $cropdata = json_decode($model->cropdata,true);
        if($cropdata){
            $box_data = json_encode($cropdata['box_data']);
            $img_data = json_encode($cropdata['img_data']);
        }
    }



?>

<div class="container crop_window">
    <div class="row">
        <?php $form = ActiveForm::begin(['id'=>'frmCrop','enableClientValidation' => false,'enableClientScript' => false,]);?>
            <input type="hidden" name="Asset[crop_data]" value="" id="crop_data">
            <input type="hidden" name="Asset[box_cordinate]" value="" id="box_cordinate">
            <table class="table" style="width:95%" align="center" id="tblCrop">
                <tbody>
                    <tr class="bg-primary" id="alert_msg">
                        <td >
                            <div class="pull-left">
                                <div class="bs-callout bs-callout-warning">
                                    <h4>Cropping</h4>
                                    <p class="text-info" style="color:#FFF">Use your mouse pointer to drag a region thereafter click on "Crop" button to save image. <br/><small>(Once you done, can't be undone at any movement later)</small></p>    
                                    <p>
                                        <label class="text-orange">Image URL:</label> <label><?php echo $show_url?></label>
                                    </p>
                                </div>
                            </div>
                            <div class="pull-right">
                                <?php 
                                    if($entityType == EntityType::EVENT_ENTITY_ID || $entityType == EntityType::CONTENT_ENTITY_ID){
                                ?>
                                    <button class="btn btn-warning  btn-sm btn-cover" data-asset-id="<?php echo $model->Id?>" data-entity-id="">Set Cover Image</button>
                                <?php 
                                    }
                                ?>
                                <button class="btn btn-success  btn-sm btn-crop" type="submit">Crop</button>
                                <button class="btn btn-danger  btn-sm btn-close">Close</button>
                            </div>
                       </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <div class="img_cont">
                                <img class="main" alt="image not found" src="<?php echo $url?>?t=<?=time()?>" data-box = '<?=$box_data?>' data-img="<?=$img_data?>">
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        <?php ActiveForm::end();?>
    </div>
</div>
<style>
    .mfp-close{color:#FFF !important;}
    .crop_window{width:80%;background: #FFF;display: box}
    .img_cont{text-align: center}
    .img_cont .main{width: 100%;}
</style>