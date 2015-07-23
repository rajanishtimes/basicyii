<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Permission $model
 */

$this->title = 'Update Permission';
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Update Permission', 'url' => ['view', 'id' => $groupId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="permission-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_update', [
        'model' => $model,
        'groupId' => $groupId,
        'modulesmap' => $modulesmap,
    ]) ?>

</div>
