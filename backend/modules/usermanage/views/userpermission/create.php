<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Permission $model
 */

$this->title = 'Create Permission';
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permission-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_create', [
        'model' => $model,
        'userId' => $userId,
        'modulesmap' => $modulesmap,
    ]) ?>

</div>
