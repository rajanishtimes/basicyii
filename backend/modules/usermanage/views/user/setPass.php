<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\User $model
 */
$this->title = 'Set Password';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fullname, 'url' => ['view', 'id' => $model->Id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-update">

    <h2><?= Html::encode($model->fullname) ?></h2>

    <?= $this->render('_form_resetPass', [
        'model' => $model,
    ]) ?>

</div>
