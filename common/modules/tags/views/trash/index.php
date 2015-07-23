<?php

use yii\helpers\Html;
use yii\adminUi\widget\Box;
use yii\adminUi\widget\Row;
use yii\adminUi\widget\Column;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\tagssearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Deleted Tags';
$this->params['breadcrumbs'][] = $this->title;


Row::begin();
    Column::begin();
        Box::begin([
            'type' => Box::TYPE_INFO,
            'header' => $this->title,
            'headerIcon' => 'fa fa-gear',
            'headerButtonGroup' => true,
            'headerButton' => Html::a('Back to Tags', ['tags/index'], ['class' => 'btn btn-success fa fa-long-arrow-left'])
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

            [
                'class' => 'yii\grid\ActionColumn',
				'template' =>'{restore} {delete}',
			],
        ],
    ]);
		Box::end();
    Column::end();
Row::end();
?>