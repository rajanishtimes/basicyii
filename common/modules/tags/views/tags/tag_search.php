<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\helpers\Url;
use yii\adminUi\widget\Box;
use yii\adminUi\widget\Row;
use yii\adminUi\widget\Column;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use yii\adminUi\assetsBundle\BootstrapLoaderAsset;
use common\models\EntityType;
use yii\adminUi\assetsBundle\MultiSelectAsset;

use common\models\Event;
use common\models\Content;
use common\models\Venue;
use common\models\City;

MultiSelectAsset::register($this);
BootstrapLoaderAsset::register($this);


/* @var $this yii\web\View */
/* @var $model common\models\Question */
/* @var $searchModel common\models\QuestionSearch */
/* @var $dataProvider yii\data\SqlDataProvider */

$this->title = 'Search Enities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-search">
<?php 
Row::begin();
    Column::begin();
        Box::begin([
            'type' => Box::TYPE_INFO,
            'header' => $this->title,
            'headerIcon' => 'fa fa-gear',
            'headerButtonGroup' => true,
            'headerButton' => Html::a('<i class="fa fa-plus"></i> Add Tag', ['/tags/tags/create'], ['class' => 'btn btn-success'])
                             .Html::a('View All', ['/tags/tags/index'], ['class' => 'btn btn-default btn-default'])
                             .Html::a('<i class="fa fa-arrow-circle-left"></i> Back', 'javascript:history.back();', ['class' => 'btn btn-default'])
        ]);
            Box::begin([
                'type' => 'box box-solid box-info',
                'header' => "Search",
                'headerIcon' => 'fa fa-search',
                'headerButtonGroup' => true,            
            ]);
            $form = ActiveForm::begin([
                'action' => ['/tags/tags/tag-search'],
                'method' => 'get',
            ]);
            
            $city_data = ArrayHelper::map(City::find()->andWhere(['status'=>1])->All(),'id', 'name');
            echo $form->field($searchModel, 'city_id')->dropDownList($city_data,['class'=>'sel_multi','multiple'=>'multiple'])->label('City');
            
            echo $form->field($searchModel, 'tags')->widget(Select2::className(), [
                'options' => ['class'=>'form-control tgmulti','value' => $model->tagstr],
                'pluginOptions' => [                
                'tags' => true,
                'maximumInputLength' => 30,
                'multiple' => false,
                'initSelection' => new JsExpression('function (element, callback) {
                                    var data = [];
                                    $(element.val().split(",")).each(function () {
                                        data.push({id: this, text: this});
                                    });
                                    callback(data);
                                }'),
                'ajax'  => ["url"=> Yii::$app->urlManager->createUrl(['/tags/tags/index']),
                                "dataType" => 'json',
                                "data" => new JsExpression('function(term, page) {
                                  return {
                                    "tagssearch[name]": term
                                  };
                                }'),
                                "results" => new JsExpression('function(data, page) {                                    
                                  return {                                    
                                    results: data
                                  };
                                }')
                              ],
            ],
          ]);
    ?>
            <div class="form-group text-center">
                <?= Html::submitButton('Search Linked Entity', ['class' => 'btn btn-primary']) ?>                
            </div>
    <?php
            
            ActiveForm::end();
            Box::end();    
            //echo "<pre>";print_r($dataProvider->getModels());echo"</pre>";die;
            echo "<hr/>";
            $form = ActiveForm::begin([
                'action' => ['/tags/tags/add-entity'],
                'method' => 'get',
                'options'  => ['class'=>'form-inline']
            ]);
            echo '<div id="err_msg"></div>';
            echo $form->field($searchModel, 'add_entity_type',['options'=>['id'=>'sel_entity_type','class'=>'form-group','style'=>'width:10%;padding-right:10px;']])->dropDownList(['100'=>'Event','200'=>'Venue','300'=>'Content'])->label('');
            echo $form->field($searchModel, 'add_entity_id',['options'=>['class'=>'form-group','style'=>'width:80%;padding-right:20px;']])->widget(Select2::className(), [
                'options' => ['class'=>'form-control input-sm','placeholder' => 'Search for a Entity Name'],
                'pluginOptions' => [                
                'createSearchChoice' => new JsExpression("function(term, data) {
                                            if ($(data).filter(function() {
                                              return this.text.localeCompare(term) === 0;
                                            }).length === 0) {
                                              return {
                                                id: term,
                                                text: term
                                              };
                                            }
                                          }"),
               'minimumInputLength' => 2,
               'maximumInputLength' => 100,
               'initSelection' => new JsExpression('function (element, callback) {}'),
               'ajax'  => ["url"=> Yii::$app->urlManager->createUrl(['/tags/tags/auto-entity-search']),
                                "dataType" => 'json',
                                "data" => new JsExpression('function(term, page,etype) {
                                  return {
                                    "search": term,
                                    "etype": $(\'#tagssearch-add_entity_type\').val(),
                                  };
                                }'),
                                "results" => new JsExpression('function(data, page) {                                    
                                  return {                                    
                                    results: data
                                  };
                                }')
                              ],
            ],
          ])->label('');
          echo Html::submitButton('Add Tag', ['class' => 'btn btn-primary','id'=>'btnAddTag']);
          ActiveForm::end();             
            
            
    echo GridView::widget([
            'filterPosition' => false,
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [

                ['attribute'=>'entity_id','label'=>'Entity Id','options'=>['style'=>'width:7%']],
                ['attribute'=>'entity_name','label'=>'Entity Name','format'=>'html','options'=>['style'=>'width:50%'],'value'=>function($model){
                    switch($model['entity_type']){
                        case EntityType::EVENT_ENTITY_ID:
                            return Html::a($model['entity_name'],['/event/event/update','id'=>$model['entity_id']],['target'=>'_blank']);
                        case EntityType::CONTENT_ENTITY_ID:
                            return Html::a($model['entity_name'],['/content/content/update','id'=>$model['entity_id']],['target'=>'_blank']);
                        case EntityType::VENUE_ENTITY_ID:
                            return Html::a($model['entity_name'],['/venue/venue/update','id'=>$model['entity_id']],['target'=>'_blank']);
                        default:    
                            return Html::a('(not set)',['#']);
                    }
                }],
                ['attribute'=>'city_name','label'=>'City','options'=>['width'=>'10%']],        
                ['attribute'=>'entity_type','label'=>'Entity Type','options'=>['style'=>'width:10%'],'format'=>'text','value'=>function($model){
                    return EntityType::getName($model['entity_type']);
                }],
                ['attribute'=>'created_on','format'=>'datetime','contentOptions' => ['style' => 'width: 10%;']],       
                ['attribute'=>'updated_on','format'=>'datetime','contentOptions' => ['style' => 'width: 10%;']],               
                
        ],
    ]);        
            
            
        Box::end();        
        
                
        
        $this->registerJs('
            $(".sel_multi").multiselect({
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                disableIfEmpty :true,
                numberDisplayed: 6,
            });
            
            $(".tgmulti").click(function(){
                if($("div.btn-group.open").length > 0){
                    $("div.btn-group.open").removeClass("open");
                }
            
            });
            
            function showMsg(msg){
                $("#err_msg").html(\'<div class="alert alert-warning alert-dismissable"><i class="fa fa-info"></i><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><b>Message: </b>\'+msg+\'</div>\');
            }
            
            $("#btnAddTag").click(function(){
                tags = $("#tagssearch-tags").val();
                entity_type = $("#tagssearch-add_entity_type").val();
                entity_id = $("#tagssearch-add_entity_id").val();
                if(tags == "" || tags == undefined){
                    alert("Please search tag first");
                    return false;
                }
                if(entity_id == "" || entity_id == undefined){
                    alert("Please search an entity first");
                    return false;
                }
                if(isNaN(entity_id)){
                    alert("Please select entity from search list");
                    return false;
                }
                $.ajax({
                    url:"'.Url::to(['/tags/tags/add-tag-to-entity']).'",
                    data:{entity_id:entity_id,tags:tags,entity_type:entity_type},
                    type:"POST",
                    beforeSend:function(){
                        waitingDialog.show();
                    },
                    success:function(d){
                        showMsg(d.req_msg);
                        if(d.success == 1){
                            setTimeout(function(){window.location.reload();},3000);
                        }
                    },
                    complete:function(){
                        waitingDialog.hide();
                    }
                });
                return false;
                
            });


        ',View::POS_READY);
?>    
</div>