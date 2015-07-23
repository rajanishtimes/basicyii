<?php
use yii\helpers\Html;
use yii\grid\GridView;
/**
 * @var yii\web\View $this
 * @var common\models\User $user
 */
?>

Hi User,

System has been pulled facebook Events in last few Hours..


Here are the details.

<?php foreach($latestEvent as $event){ ?>

    <?php 
        echo $event->name." (".$event->cityname." from ".date('d m Y',$event->startDate)." to ".date('d m Y',$event->endDate).")";
        echo Html::a(Html::encode('View'), ['event/event/view',['id'=>$event->Id]]);
    ?><br >

<?php } ?>


Please have a look.

regards
Cms Teams.
