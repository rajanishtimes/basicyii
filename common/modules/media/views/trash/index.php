<?php

use yii\helpers\Html;
use yii\adminUi\widget\Box;
use yii\adminUi\widget\Row;
use yii\adminUi\widget\Column;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\Searchasset */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Assets';
$this->params['breadcrumbs'][] = $this->title;


Row::begin();
    Column::begin();
        Box::begin([
            'type' => Box::TYPE_INFO,
            'header' => $this->title,
            'headerIcon' => 'fa fa-user',
            'headerButtonGroup' => true,
            'headerButton' => Html::a('Create Asset', ['create'], ['class' => 'btn btn-success'])
                            .Html::a('<i class="fa fa-trash-o fa-lg"></i>&nbsp; Trash', ['user-trash/index'], ['class' => 'btn btn-default'])
        ]);
				// echo $this->render('_search', ['model' => $searchModel]); 
				
				echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\CheckboxColumn'],

		            'Id',
            'filename',
            'path',
            'uri',
            'description',
            // 'mimetype',
            // 'source',
            // 'embedcode',
            // 'mediahash',
            // 'metainfo',
            // 'createdBy',
            // 'updatedBy',
            // 'createdOn',
            // 'modifiedOn',
            // 'status',
            // 'ip',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);

		Box::end();
    Column::end();
Row::end();
?>