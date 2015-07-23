<?php

/* 
 * Coder: Mithun Mandal
 * Contact: 01206636679/8527580960
 * Project: Timescity CMS Using Yii2
 */

?>
<div class="row">
    <div class="col-md-12 video-preview">
        
    </div>
    <div class="col-md-12 embed-forms">
        <div class="clearfix">
            <input type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->getCsrfToken()?>" />
        <h4>Video Url</h4>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-youtube"></i></span>
            <input type="text" name="videourl" class="form-control" placeholder="http://youtube.com/">            
            <div class="input-group-btn">
                <button type="button" class="btn btn-primary mediasave">Save</button>
            </div>
        </div>
        </div>
    </div>
    
    <div class="col-md-12 embed-forms">
        <div class="clearfix">
            <input type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->getCsrfToken()?>" />
        <h4>Kaltura Video (Video Id)</h4>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-video-camera"></i></span>
            <input type="text" name="videoId" class="form-control" placeholder="vfg56">
            <input type="hidden" name="source" value="kaltura" >
            <input type="hidden" name="type" value="videoId">
            <div class="input-group-btn">
                <button type="button" class="btn btn-primary mediasave">Save</button>
            </div>
        </div>
        </div>
    </div>
    
    <div class="col-md-12  embed-forms">
        <div class="clearfix">
            <input type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->getCsrfToken()?>" />
        <h4>Embed Code</h4>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-youtube-play"></i></span>
            <textarea name="embed" class="form-control"></textarea>
        </div>
        <button type="button" class="btn btn-primary col-md-12 col-xs-12 col-sm-12 mediasave">Save</button>            
        </div>
    </div>
</div>