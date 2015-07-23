var youtubeobj;
function VideoPlayerObject(obj){
    this.obj = obj;
    this.config = {};
    this.config.width = this.obj.attr('data-width');
    this.config.height = this.obj.attr('data-height');
    this.config.videoName = this.obj.attr('data-videoName');
    this.config.source = this.obj.attr('data-source');
    this.config.videoCode = this.obj.attr('data-videoCode');
    this.config.thumb = this.obj.attr('data-thumb');
    this.config.duration = this.obj.attr('data-duration');
    this.config.audio = this.obj.attr('data-audio');
    this.config.autoplay = this.obj.attr('data-autoplay');
    this.config.relatedvideo = '';
    this.config.pageurl = this.obj.attr('data-pageurl');
    
    this.canPlay = false;
    this.apiload = {
                        kaltura:false,
                        youtube:false,
                        kalturaP_id:303962,
                        youtubePlayer:null
                    };
    this.debug();
    this.showVideo();
}

VideoPlayerObject.prototype = {
    constructor: VideoPlayerObject,
    debug:function(){
            debug('Main Obj');
            //debug(this.obj);
    },
    init:function(obj,config){
        this.obj = obj;
        this.config = config;
        this.canPlay = false;
        this.debug();
    },
    showVideo:function(){
        var v = document.createElement('video');
        if(FlashDetect.installed){
            this.showFlashPlayer();
        }else if (v.canPlayType && v.canPlayType('video/mp4').replace(/no/, '')){
            this.showHtmlPlayer()
        }else {
            this.showFallBack();
        }
    },
    showHtmlPlayer:function(){
        this.canPlay = true;
        $(this.obj).html('<div id="player"></div>');
        if(this.config.source == 'Kaltura'){
            this.loadKalturaVideo();
        }else if(this.config.source == 'Youtube'){
            this.loadYouTubeVideo();
        }        
    },
    loadYouTubeVideo:function(){
        if(typeof YT == 'undefined'){
            // Load the IFrame Player API code asynchronously.
            var tag = document.createElement('script');
            tag.src = "https://www.youtube.com/player_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);        
            this.apiload.youtube = true;
            var width = this.config.width;
            var height = this.config.height;
            var videoCode = this.config.videoCode;

            window.onYouTubePlayerAPIReady = function() {            
               debug('called onYouTubePlayerAPIReady');
               youtubeobj = new YT.Player('player', {
                                                    height: height,
                                                    width: width,
                                                    videoId: videoCode
                                                });
            };
        }else{
            this.apiload.youtubePlayer = new YT.Player('player', {
                                                    height: this.config.height,
                                                    width: this.config.width,
                                                    videoId:this.config.videoCode
                                                });            
        }
        
    },
    loadKalturaVideo:function(){
        if(typeof getklSource == 'function'){
            getklSource(this.apiload.kalturaP_id, this.config.videoCode,this.config.width,this.config.height);
        }else{            
            var tmpjs = document.createElement("script");
            tmpjs.type = "text/javascript"
            tmpjs.src = asseturl+"/js/js_kalturaapi.js";
            document.getElementsByTagName("head")[0].appendChild(tmpjs);
            
            var PublisherId = this.apiload.kalturaP_id;
            var VideoCode = this.config.videoCode;
            var width = this.config.width;
            var height = this.config.height;
            
            
            
            tmpjs.onreadystatechange = function() {
                getklSource(PublisherId, VideoCode,width,height);
            }
            tmpjs.onload = function() {
                getklSource(PublisherId, VideoCode,width,height);
            }
        }
    },    
    showFlashPlayer:function(){
        $(this.obj).html(this.getEmbedCode());
    },
    showFallBack:function(){
        $(this.obj).html('Sorry Your Browser is not able to play this video, Please change the browser.');
    },
    getEmbedCode:function(){
        var Width = this.config.width;
        var Height = this.config.height;
        var FlashVars = new Array();
        FlashVars.push('channelid=10032');
        
        if(this.config.source == 'Kaltura'){
            FlashVars.push('playerid=24');
            FlashVars.push('contentid='+this.config.videoCode);            
        }else if(this.config.source == 'Youtube'){
            FlashVars.push('playerid=10');
            FlashVars.push('contentpath=http://www.youtube.com/v/'+this.config.videoCode);
        }else{
            return false;
        }
        
        FlashVars.push('image='+this.config.thumb);
        FlashVars.push('autoplay='+this.config.autoplay);
        FlashVars.push('audio='+this.config.audio);
        FlashVars.push('title='+this.config.videoName);
        FlashVars.push('keywords=');
        FlashVars.push('section=Entertainment');
        FlashVars.push('videosection=videoshow');
        FlashVars.push('duration='+this.config.duration);
        FlashVars.push('relatedvideo='+this.config.relatedvideo);
        FlashVars.push('thumburl='+this.config.thumb);
        FlashVars.push('pageurl='+this.config.pageurl);
        FlashVars.push('tadsid=0');
        
        var FlashVar = FlashVars.join('&');
        
        var code = '<OBJECT id="myMovie" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="'+Width+'" height="'+Height+'">\n\
            <PARAM name="movie" value="http://timesofindia.indiatimes.com/configspace/ads/TimesWrapper.swf"></PARAM>\n\
            <PARAM name="allowFullScreen" value="true"/><PARAM name="quality" value="high"/>\n\
            <PARAM name="wmode" value="opaque"/>\n\
            <PARAM name="allowScriptAccess" value="always"/>\n\
            <PARAM name="allowNetworking" value="all"/>\n\
            <PARAM name="flashvars" value="'+FlashVar+'"/>\n\
            <EMBED allowFullScreen="true" quality="high" wmode="transparent" allowScriptAccess="always" flashvars="'+FlashVar+'" allowNetworking="all" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="'+Width+'" height="'+Height+'" name="myMovie" src="http://timesofindia.indiatimes.com/configspace/ads/TimesWrapper.swf" style="z-index:-1"></EMBED>\n\
            </OBJECT>';
        return code;
    }
}


/*
 * 
 * @param Dom Object obj
 * @returns {EmbedUploaded}
 */
function EmbedUploaded(obj,conf){
    this.obj = obj;
    this.items = [];
    this.conf = conf;
    this.complete = false;
    this.registeruploader();
}

EmbedUploaded.prototype = {
    constructor: EmbedUploaded,
    
    registeruploader:function(){
        var tobj = this;
        this.obj.find('.mediasave').click(function() {
                var parent = $(this).parents(".embed-forms");
                debug(parent)
                var formdata = parent.find("input, checkbox, select, textarea").serialize();
                tobj.disablesubmitbuttons();
                tobj.startprogressbar($(this));
                tobj.uploadstart(formdata);
                return false;
            });
    },
    
    disablesubmitbuttons:function(){
        this.obj.find('.mediasave').addClass('disabled');
    },
    
    enablesubmitbuttons:function(){
        this.obj.find('.mediasave').removeClass('disabled');
    },
    
    stopprogressbar:function(){
        debug('stop progess');
        this.obj.find(".progress").remove();
        this.complete = false;        
    },
    
    startprogressbar:function(obj){
        var tobj = this;
        var doc = $('<div class="progress xs progress-striped active"><div id="uploadprogress" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 0%"><span class="sr-only">0% Complete</span></div></div>');
        obj.parents(".embed-forms").append(doc);
        
        var progress = 0,
        interval = setInterval( function() {                
                progress = Math.min( progress + Math.random() * 0.1, 1 );
                
                width = parseInt(100*progress);
                
                $('#uploadprogress').css('width',width+'%');
                
                if( tobj.complete === true){
                        tobj.stopprogressbar();
                        clearInterval( interval );
                }else if(progress === 1){
                    progress = 0;
                }
        }, 200 );
    },
    
    uploadstart:function(datavar){
        var tobj = this;
        $.ajax({
            type:'POST',
            url:tobj.conf.url,
            data:datavar,
            success:function(jsondoc){tobj.afterupload(jsondoc)},
            complete:function(data){tobj.afteruploadcomplete(data)},
            error:function(errors){tobj.afteruploaderror(errors)}
        });
    },
    
    afteruploaderror:function(error){
        debug('afteruploaderror');
        debug(error);
    },
    
    afterupload:function(jsondoc){
        debug('afterupload');
        debug(jsondoc);
        this.addmedia(jsondoc);
    },
    
    afteruploadcomplete:function(data){
        debug('afteruploadcomplete');
        debug(data);
        this.complete = true;
        this.enablesubmitbuttons();
    },
    
    render:function(doc){
        var container = this.obj.find(this.conf.preview_selector);
        var tobj = this;
        
        var meta = $.parseJSON(doc.metainfo);
        
        var htmldoc = $('<div />')
                .addClass("col-md-2 col-xs-6 media-wrapper")
                .append(
                    $('<div />')
                    .addClass('media-container')
                    .attr({
                        'id':'media-'+doc.Id,
                        'data-id':doc.Id,
                        'data-caption':doc.description,
                        'data-videoId':doc.filename,
                        'data-source':doc.source,
                        'data-url':doc.uri,
                        'data-width':meta.width,
                        'data-height':meta.height,
                        'data-img':meta.thumbnailUrl
                    })
                    .append(
                        $('<input />')
                        .attr({
                            'type':'hidden',
                            'name':tobj.conf.inputparam,
                            'value':doc.Id
                        })
                    )
                    .append(
                        $('<img />')
                        .addClass("img-thumbnail")
                        .attr({
                            'alt':doc.description,
                            'src':meta.thumbnailUrl
                        })
                    )
                    .append(
                        $('<span />')
                        .addClass("fa fa-check fa-2x iconsuccess")                        
                    )
                    .append(
                        $('<a />')
                        .addClass("btn btn-sm btn-default removebtn")
                        .html("Remove")
                        .attr({
                            'data-id':doc.Id,
                        })
                    )
                );
        
        htmldoc.find('.removebtn').click(function(){
            tobj.removemedia($(this).data('id'))
        });
        container
           .append(
                htmldoc
           );
    },
    
    alert:function(msg){
        alert(msg);
    },
    
    addmedia:function(doc){
        var tobj = this;
        var found = false;
        if(this.items.length>0){            
            $.each(this.items,function(i,data){
                if(data.Id == doc.Id){
                    found = true;
                    tobj.alert('Already Added.');
                    return false;
                }
            })
        }
        
        if(!found){
            this.items.push(doc);
            this.render(doc)
        }
    },
    
    addmedias:function(docs){
        var tobj = this;
        console.log('this is doc'+docs);
        if(docs){
             if(docs.length>0){            
            $.each(docs,function(i,doc){
                tobj.addmedia(doc);
            })
        }
            
        }
       
    },
    
    removemedia:function(id){
        debug(id);
        var index = 0;
        if(this.items.length>0){            
            $.each(this.items,function(i,data){
                if(data.Id == id){
                    index = i;
                    return false;
                }
            })
        }
        debug("index:"+index);
        this.items.splice(index,1);
        debug(this.items)
        
        $('#media-'+id).parents('.media-wrapper').remove();
    },
    
    viewmedia:function(){
        
    }    
}


function debug(msg) {
    try {        
        if ('info' in window.console) {
            try {
                window.console['info'](msg);
            } catch (w) {
                window.console.log(msg);
            }
        } else {
            window.console.log(msg);
        }
    } catch (ex) {
    }
}