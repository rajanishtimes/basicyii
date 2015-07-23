<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\UserSearch $searchModel
 */

$this->title = 'Manage Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-trash-o fa-lg"></i>&nbsp; Trash', ['user-trash/index'], ['class' => 'btn btn-default']) ?>
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
                'template' => '{view} {update} {setpassword} {delete}'
        ],
            ]
    ]); ?>

</div>