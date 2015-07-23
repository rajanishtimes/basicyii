<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\UserTrashSearch $searchModel
 */

$this->title = 'Deleted Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>           
        <?= Html::a('<i class="fa fa-user fa-lg"></i>&nbsp; Back to User', ['user/index'], ['class' => 'btn btn-success fa fa-long-arrow-left']) ?>    
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\CheckboxColumn'],
            //'Id',
            'username',
            // 'email:email',
            // 'status',
            'firstname',
            'lastname',
            // 'groupId',
            // 'reportTo',
            // 'reportUserType',
            // 'phone',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{restore} {delete}',
        ],
            ]
    ]); ?>

</div>
