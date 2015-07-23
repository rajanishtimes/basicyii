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
$this->params['breadcrumbs'][] = ['label' => 'profile', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Row::begin();
    Column::begin();
        Box::begin([
            'type' => Box::TYPE_INFO,
            'header' => $this->title,
            'headerIcon' => 'fa fa-user',
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
                    //'createTime',
                    //'updateTime',
                    //'createdByUser',
                    //'updatedByUser',
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
        ?>
<div class="center-block text-center">
    <?=Html::a('Update Profile', ['update'], ['class' => 'btn btn-primary'])?>
    <?=Html::a('Change Password',['changepassword'], ['class' => 'btn btn-danger',])?>
</div>
        <?php
    Column::end();
Row::end();
?>