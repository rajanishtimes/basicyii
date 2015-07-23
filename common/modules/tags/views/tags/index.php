<?php

use yii\helpers\Html;
use yii\adminUi\widget\Box;
use yii\adminUi\widget\Row;
use yii\adminUi\widget\Column;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\tagssearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tags';
$this->params['breadcrumbs'][] = $this->title;


Row::begin();
    Column::begin();
        Box::begin([
            'type' => Box::TYPE_INFO,
            'header' => $this->title,
            'headerIcon' => 'fa fa-gear',
            'headerButtonGroup' => true,
            'headerButton' => Html::a('Create Tags', ['create'], ['class' => 'btn btn-success'])
                             .Html::a('Map Tags', ['/tags/tags/tag-search'], ['class' => 'btn btn-default btn-default'])
                            .Html::a('<i class="fa fa-trash-o fa-lg"></i>&nbsp; Trash', ['trash/index'], ['class' => 'btn btn-default'])
        ]);
				// echo $this->render('_search', ['model' => $searchModel]); 
				
				echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\CheckboxColumn'],

		            'Id',
            'name',
            'description',
            //'createdBy',
            //'updatedBy',
            // 'createdOn',
            // 'updatedOn',
            // 'status',
            // 'ip',
            // 'issystem',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);

		Box::end();
    Column::end();
Row::end();
?>