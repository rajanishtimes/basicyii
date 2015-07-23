<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\grouptrashsearch $searchModel
 */

$this->title = 'Deleted Groups';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<i class="fa fa-users fa-lg"></i>&nbsp; Back to Group', ['group/index'], ['class' => 'btn btn-success fa fa-long-arrow-left']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition' => GridView::FILTER_POS_HEADER,
        'columns' => [
            //['class' => 'yii\grid\CheckboxColumn'],
            //['class' => 'yii\grid\SerialColumn'],
            //'Id',
            'name',
            'parentGroup',
            //'statusName',
            //'createdon',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{restore} {delete}',
            ],
        ],
    ]); ?>

</div>
