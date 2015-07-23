<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\grid\GridView;
use yii\adminUi\widget\Box;
use yii\adminUi\widget\Row;
use yii\adminUi\widget\Column;
use yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var common\models\User $model
 */


$this->title = $model->fullname;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Row::begin();
    Column::begin();
        Box::begin([
            'type' => Box::TYPE_INFO,
            'header' => $this->title,
            'headerIcon' => 'fa fa-user',
            'headerButtonGroup' => true,
            'headerButton' => Html::a('Update', 
                                    ['update', 'id' => $model->Id], 
                                    ['class' => 'btn btn-primary']
                                )
                            .Html::a('Delete', 
                                    ['delete', 'id' => $model->Id], 
                                    [
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
                    'firstname',
                    'lastname',
                    //'Id',
                    'username',
                    //'auth_key',
                    //'password_hash',
                    //'password_reset_token',
                    'email',
                    'phone',
                    //'role',
                    'groupName',
                    'statusName',
                    'createTime',
                    'updateTime',
                    'createdByUser',
                    'updatedByUser',

                    //'groupId',
                    //'reportTo',
                    //'reportUserType',

                ],
            ]);
        Box::end();
        
        Box::begin([
            'type' => Box::TYPE_INFO,
            'header' => 'Additional Groups',
            'headerIcon' => 'fa fa-users',
            'headerButtonGroup' => true,
            'headerButton' => Html::a('Update Groups', 
                                    ['updategroups', 'id' => $model->Id], 
                                    ['class' => 'btn btn-primary']
                                )
        ]);
        
        echo  ListView::widget([
                'dataProvider' => $model->getUsersGroup(),
                //'showOnEmpty'   => true,
                'emptyText'     => 'No Group Listed',
                'emptyTextOptions' => [
                                        'class' => 'callout callout-danger',
                                        ],
                'options'       => [
                                        'class' => 'list-group'
                                    ],
                'layout'        => '{items}',
                'itemOptions'   =>[
                                    'tag'   => 'a',
                                    'href'  => '#',
                                    'class' => 'list-group-item'
                                ],
                'itemView'      => function($model, $key, $index, $widget){
                                        return $model->groupName;
                                    }
                ]);
        
        Box::end();
        
        Box::begin([
            'type' => Box::TYPE_INFO,
            'header' => 'Rules',
            'headerIcon' => 'fa fa-cogs',
            'headerButtonGroup' => true,
            'headerButton' => Html::a('Add', 
                                    ['userpermission/create', 'userId' => $model->Id], 
                                    ['class' => 'btn btn-primary']
                                )
        ]);
        
        echo GridView::widget([
            'dataProvider' => $model->getAllRules(),
            'filterPosition'    => false,
            'layout' => "{items}",
            'columns' => [                
                [                    // the owner name of the model
                    'label' => 'User / Group',
                    'value' => function($model){ if($model->userId) return  $model->userName; else return $model->groupName;},
                ],
                [                    // the owner name of the model
                    'label' => 'Type',
                    'value' => function($model){ if($model->userId) return  'User'; else return 'Group';},
                ],
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
                        $params[0] = 'userpermission/'.$action;                        
                        $params['userId'] = $model->userId;
                        return Url::toRoute($params);
                    },
                    'buttons' => [
                                    'update' => function($url, $model){
                                        if($model->userId){
                                         return Html::tag('li',Html::a('<span class="fa fa-edit fa-lg"></span> '.Yii::t('yii', 'Update'), $url, [
                                            'title' => Yii::t('yii', 'Update'),
                                            'data-pjax' => '0',
                                        ]));
                                        }else{
                                            return '';
                                        }
                                    },
                                    'delete' => function($url, $model){
                                        if($model->userId){
                                            return Html::tag('li',Html::a('<span class="fa fa-trash-o fa-lg"></span> '.Yii::t('yii', 'Delete'), $url, [
                                                'title' => Yii::t('yii', 'Delete'),
                                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                                'data-method' => 'post',
                                                'data-pjax' => '0',
                                            ]));
                                        }else{
                                            return '';
                                        }
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