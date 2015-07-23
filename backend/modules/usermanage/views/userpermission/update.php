<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Permission $model
 */

$this->title = 'Update Permission: ' . ' ' . $model->Id;
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Id, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="permission-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_update', [
        'model' => $model,
        'userId' => $userId,
        'modulesmap' => $modulesmap,
    ]) ?>

</div>
