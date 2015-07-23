<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\GroupPermissionSearch $searchModel
 */

$this->title = 'Permissions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permission-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Permission', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition'    => false,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            'groupName',            
            [                    // the owner name of the model
                'label' => 'Module',
                'value' => function($model){ if($model->module == '*') return  'All'; else return $model->module;},
            ],
            [                    // the owner name of the model
                'label' => 'Controller',
                'value' => function($model){ if($model->controller == '*') return  'All'; else return $model->controller;},
            ],
            [                    // the owner name of the model
                'label' => 'Action',
                'value' => function($model){ if($model->action == '*') return  'All'; else return $model->action;},
            ],
            [                    // the owner name of the model
                'label' => 'Type',
                'value' => function($model){ if($model->type == 1) return  'Granted'; else return 'Restrict';},
            ],
            [                    // the owner name of the model
                'label' => 'Status',
                'value' => function($model){ if($model->status == 1) return  'Active'; else return 'InActive';},
            ],
            //'controller',
            //'action',
            // 'createdOn',
            // 'createdBy',
            // 'updatedOn',
            // 'updatedBy',
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
