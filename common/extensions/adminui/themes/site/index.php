<?php
/**
 * @var yii\web\View $this
 */

use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
use yii\adminUi\assetsBundle\AdminUiMorrisChartAsset;
use yii\adminUi\assetsBundle\BootstrapLoaderAsset;
use yii\adminUi\widget\Box;
use yii\grid\GridView;
use yii\adminUi\widget\Tabs;
$this->title = 'What\'s Hot CMS';

AdminUiMorrisChartAsset::register($this);
BootstrapLoaderAsset::register($this);

?>
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>1</h3>
                <p>T</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    1
                </h3>
                <p>
                   T
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>
                    1
                </h3>
                <p>
                   T 
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>
                   1
                </h3>
                <p>
                    T
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
</div><!-- /.row -->
<div class="row">
    <div class="col-md-12">
    <?php 
    
        Box::begin([
            'type' => 'box-solid bg-light-blue',
            'header' => "Quick Access",
            'headerIcon' => 'fa fa-gear',
            'class'=>'bg-maroon',
            'headerButtonGroup' => true,
        ]);
        //echo Html::a('<i class="fa fa-file-text-o"></i> Content',['/content/content/index'],['class'=>'btn btn-app']);
       // echo Html::a('<i class="fa fa-heart"></i> Event',['/event/event/index'],['class'=>'btn btn-app']);
        //echo Html::a('<i class="fa fa-home"></i> Venue',['/venue/venue/index'],['class'=>'btn btn-app']);
        //echo Html::a('<i class="fa fa-tag"></i> Tags',['/tags/tags/tag-search'],['class'=>'btn btn-app']);
        //echo Html::a('<i class="fa fa-android"></i> Android App',['/downloads/WhatsHot_v1.0.10.apk'],['class'=>'btn btn-app']);        
        
    ?>
    <?php
        Box::end();
    ?>
    </div>    
    
</div>

  