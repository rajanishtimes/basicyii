<?php
use yii\helpers\Html;
use yii\grid\GridView;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\groupsearch $searchModel
 */

$this->title = 'Manage Groups';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        
    <p>
        <?= Html::a('Create Group', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-trash-o fa-lg"></i>&nbsp; Trash', ['group-trash/index'], ['class' => 'btn btn-default']) ?>
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
