<?php
use yii\helpers\Html;
use yii\adminUi\widget\Box;
use yii\adminUi\widget\Row;
use yii\adminUi\widget\Column;

/**
 * @var yii\web\View $this
 * @var common\models\User $model
 */
$this->title =  'Modify Group Subscription';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fullname, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = $this->title;

Row::begin();
    Column::begin();
        Box::begin([
            'type' => Box::TYPE_INFO,
            'header' => $model->fullname,
            'headerIcon' => 'fa fa-user',
        ]);
        echo  $this->render('_form_updategroup', [
            'model' => $model,
        ]);
        Box::end();
    Column::end();
Row::end();
?>