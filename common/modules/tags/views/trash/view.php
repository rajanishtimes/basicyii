<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\grid\GridView;
use yii\adminUi\widget\Box;
use yii\adminUi\widget\Row;
use yii\adminUi\widget\Column;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\tags */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tags', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


Row::begin();
    Column::begin();
        Box::begin([
            'type' => Box::TYPE_INFO,
            'header' => $this->title,
            'headerIcon' => 'fa fa-gear',
            'headerButtonGroup' => true,
            'headerButton' => Html::a('Update', ['update', 'id' => $model->Id], ['class' => 'btn btn-primary'])
                            .Html::a('Delete', ['delete', 'id' => $model->Id], [
								'class' => 'btn btn-danger',
								'data' => [
									'confirm' => 'Are you sure you want to delete this item?',
									'method' => 'post',
								],
							])
        ]);
		
		echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'Id',
            'name',
            'description',
            'createdBy',
            'updatedBy',
            'createdOn',
            'updatedOn',
            'status',
            'ip',
            'issystem',
        ],
    ]);
        Box::end();
    Column::end();
Row::end();
?>