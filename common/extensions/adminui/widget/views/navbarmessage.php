<?php
use yii\helpers\Url;
use yii\adminUi\assetsBundle\AdminUiAsset;
use yii\helpers\Html;
use common\models\extras\Broadcast;


$bundle = AdminUiAsset::register($this);
$home_uri = Url::home(true);
?>
<a href="#" class="dropdown-toggle" data-toggle="dropdown">
	<i class="fa fa-envelope"></i>
        <?php
            if(isset($notifications) && count($notifications) > 0){
                echo '<span class="label label-success">'.count($notifications).'</span>';
            }
        ?>
</a>
<ul class="dropdown-menu">
        <?php 
            if(isset($notifications)){
        ?>
                <li class="header">You have <?=count($notifications)?> unread messages</li>
        <?php
                if(!empty($notifications)){
                    
        ?>
                    <li>
                        <ul class="menu">
                    
        <?php        
                    foreach($notifications as $noty){
        ?>
        
                        <li>
                            <a href="<?=  Url::to(['/message/message/read/','id'=>$noty['id']]) ?>">
                                <div class="pull-left">
                                    <img style="border-radius:20%" src="<?php echo $home_uri?>/images/WH_TILE_150X150.png" class="img-circle" alt="WH-Logo"/>
                                </div>
                                <h4><?=  Broadcast::FROM_EMAIL_NAME;?></h4>
                                <p><?=$noty['short_msg']?></p>
                                <p>
                                    <small><i class="fa fa-clock-o"></i> &nbsp;
                                    <?php echo date('M j, h:i a',  strtotime($noty['createdOn']));?></small>
                                </p>
                            </a>
                        </li>
        <?php
                    }
        ?>
                        </ul>
                    </li>
                    
        <?php
                }
                echo '<li class="footer">'.Html::a('See All Messages',['/message/message/']).'</li>';          
        
            }
        ?>
	
</ul>