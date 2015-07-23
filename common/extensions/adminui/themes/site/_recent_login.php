<?php
use yii\adminUi\widget\Box;
use common\models\LoginHistory;
use yii\grid\GridView;
use yii\adminUi\widget\Tabs;
?>

<div class="row">
    <div class="col-md-12">
    <?php 
    
        Box::begin([
            'type' => Box::TYPE_PRIMARY,
            'header' => "Recent Login",
            'headerIcon' => 'fa fa-history',
            'class'=>'bg-maroon',
            'headerButtonGroup' => true,
        ]);        
        $model = new LoginHistory();
        $dataProvider = $model->getDashboardData();
        \yii\widgets\Pjax::begin(['timeout' => 10000]);
        echo GridView::widget([
                'filterPosition' => false,
                'dataProvider' => $dataProvider,
                'filterModel' => $model,
                'tableOptions' => ['class' => 'table table-bordered'],
                'columns' => [
                    ['attribute'=>'user_id','contentOptions' => ['style' => 'width: 6%;']],
                    ['attribute'=>'username','label'=>'Username','contentOptions' => ['style' => 'width: 15%;'],'format'=>'raw','value'=>function($model){
                        return $model->user->username;
                    }],
                    ['attribute'=>'name','label'=>'Name','contentOptions' => ['style' => 'width: 35%;'],'format'=>'raw','value'=>function($model){
                        return $model->user->firstname.' '.$model->user->lastname;
                    }],                    
                    ['attribute'=>'group','label'=>'Primary Group','contentOptions' => ['style' => 'width: 10%;'],'format'=>'raw','value'=>function($model){
                        return $model->user->groupName;
                    }],
                    ['attribute'=>'login_time','label'=>'Login Time','contentOptions' => ['style' => 'width: 10%;'],'format'=>'datetime'],
                    ['attribute'=>'ip','label'=>'IP','contentOptions' => ['style' => 'width: 10%;']],                            
                    
                ],
            ]);
        \yii\widgets\Pjax::end();
    ?>
    <?php
        Box::end();
    ?>
    </div>    
    
</div>