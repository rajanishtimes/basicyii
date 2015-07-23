<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\group $model
 */

$this->title = 'Update Group';
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-update">

    <h2><?= Html::encode($model->name) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
