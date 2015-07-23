<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\adminUi\widget\Box;
use yii\adminUi\widget\Row;
use yii\adminUi\widget\Column;
use yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var app\models\group $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Row::begin();
    Column::begin();
        Box::begin([
            'type' => Box::TYPE_INFO,
            'header' => $this->title,
            'headerIcon' => 'fa fa-users',
            'headerButtonGroup' => true,
            'headerButton' => Html::a('Update', [
                                                    'update', 
                                                    'id' => $model->Id], 
                                                    ['class' => 'btn btn-primary']
                            )
                            .Html::a('Delete', ['delete', 'id' => $model->Id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ])
        ]);
        echo  DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        #'Id',
                        'name',
                        'parentGroup',
                        'StatusName',
                        //'createdon',
                        'CreateTime'
                    ],
                ]);
        Box::end();
        Box::begin([
            'type' => Box::TYPE_INFO,
            'header' => 'Rules',
            'headerIcon' => 'fa fa-cogs',
            'headerButtonGroup' => true,
            'headerButton' => Html::a('Add', 
                                    ['grouppermission/create', 'groupId' => $model->Id], 
                                    ['class' => 'btn btn-primary']
                                )
        ]);
        
        echo GridView::widget([
            'dataProvider' => $model->getAllRules(),
            'filterPosition'    => false,
            'layout' => "{items}",
            'columns' => [                
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
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'urlCreator'    => function($action, $model, $key, $index){
                        $params = is_array($key) ? $key : ['id' => (string) $key];
                        $params[0] = 'grouppermission/'.$action;                        
                        $params['groupId'] = $model->groupId;
                        return Url::toRoute($params);
                    },
                    'buttons' => [
                                    'update' => function($url, $model){
                                         return Html::tag('li',Html::a('<span class="fa fa-edit fa-lg"></span> '.Yii::t('yii', 'Update'), $url, [
                                            'title' => Yii::t('yii', 'Update'),
                                            'data-pjax' => '0',
                                        ]));
                                    },
                                    'delete' => function($url, $model){
                                        return Html::tag('li',Html::a('<span class="fa fa-trash-o fa-lg"></span> '.Yii::t('yii', 'Delete'), $url, [
                                            'title' => Yii::t('yii', 'Delete'),
                                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                            'data-method' => 'post',
                                            'data-pjax' => '0',
                                        ]));
                                    }
                                  ],
                ],
            ],
        ]); 
        Box::end();
        
        
        Box::begin([
            'type' => Box::TYPE_INFO,
            'header' => 'Permissions',
            'headerIcon' => 'fa fa-shield',
        ]);
        echo GridView::widget([
            'dataProvider' => $model->getPermissions(),
            'filterPosition'    => false,
            'layout' => "{items}",
            'columns' => [
                [                    // the owner name of the model
                    'label' => 'Module',
                    'value' => function($model){
                        return $model[0];
                    },
                ],
                [                    // the owner name of the model
                    'label' => 'Controller',
                    'value' => function($model){
                        return $model[1];
                    },
                ],
                [                    // the owner name of the model
                    'label' => 'Action',
                    'value' => function($model){ return $model[2];},
                ]
            ],
        ]);
        Box::end();
    Column::end();
Row::end();
?>
