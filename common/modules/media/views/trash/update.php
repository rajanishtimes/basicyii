<?php


use yii\helpers\Html;
use yii\adminUi\widget\Box;
use yii\adminUi\widget\Row;
use yii\adminUi\widget\Column;

/* @var $this yii\web\View */
/* @var $model common\models\Asset */

$this->title = 'Update Asset: ' . ' ' . $model->Id;
$this->params['breadcrumbs'][] = ['label' => 'Assets', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Id, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Update';

Row::begin();
    Column::begin();
        Box::begin([
            'type' => Box::TYPE_INFO,
            'header' => $model->title,
            'headerIcon' => 'fa fa-user',
        ]);
        echo  $this->render('_form', [
            'model' => $model,
        ]);
        Box::end();
    Column::end();
Row::end();
?>