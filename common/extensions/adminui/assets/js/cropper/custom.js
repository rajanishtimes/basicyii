var _img = null,_thumb = null,_lastReq = null;;
/*
 * Mukesh Soni : Function will load image in popup to enable crop feature.
 */
function start_crop(){
    _thumb = $(this),P = _thumb.parent().parent(),id=P.find('[data-dz-id-var]').val();
    entity_id = getParameterByName('id');
    if(entity_id =="" || entity_id === undefined){
        entity_id = "";
    }
    $.magnificPopup.open({
        items: {src: media_action+"getcrop?id="+id+"&entityType="+_entityType},
        type: 'ajax',
        mainClass: 'mfp-img-mobile',
        image:{verticalFit: true},
        callbacks:{
            open:function(){
                waitingDialog.show();
            },
            ajaxContentAdded: function() {
                _c = $(this.content).find('.img_cont')
                _img = $(this.content).find('.img_cont > img');
                var box = _img.data('box');
                _img.cropper({
                    aspectRatio: 1,
                    autoCrop:1,
                    checkImageOrigin:0,
                    crop: function(data) {
                        var json = [
                            '{"x":' + data.x,
                            '"y":' + data.y,
                            '"height":' + data.height,
                            '"width":' + data.width + "}"
                          ].join();
                        $('#crop_data').val(json);
                        //console.log(json);
                    },
                    built:function(){
                        waitingDialog.hide();
                        if(box!="" && box != undefined){
                            _img.cropper('setCropBoxData',box);
                        }
                    },
                });
                
            },
            updateStatus: function(data) {
                console.log('Status changed', data);
                if(data.status == "error"){
                    alert("some error occured");
                    waitingDialog.hide();    
                }
                // "data" is an object that has two properties:
                // "data.status" - current status type, can be "loading", "error", "ready"
                // "data.text" - text that will be displayed (e.g. "Loading...")
                // you may modify this properties to change current status or its text dynamically
            },
            close:function(){
                if(_img){
                    _img.cropper("destroy");
                }
                waitingDialog.hide();    
            }
        }}, 0);
}

/*
 * Mukesh Soni : Function will save cropped image back to server.
 */
function do_crop(e){
    var frm = $(this).closest('form');
    var url = frm.attr('action');         
    var str = JSON.stringify(_img.cropper("getCropBoxData"));
    $('#box_cordinate').val(str);
    var _data = frm.serialize();
    var P = _thumb.parent().parent();
    $.ajax(url, {
        type: "post",
        data: _data,
        dataType:"json",
        beforeSend: function () {
            waitingDialog.show();
        },
        success: function (res) {
            if(res.state == 200 && res.message === null){
                try{
                    var isrc = _img.attr('src');
                    var arrstr = isrc.split("?t=");
                    _img.cropper('replace',arrstr[0]+'?t='+new Date().getTime());                    
                    //_thumb.attr('src',_img.cropper("getDataURL"));
                }
                catch(e){
                    console.log(e);
                }
                P.find('.dz-success-mark').after('<div class="thumb-cropped"><span></span></div>');
                P.removeClass('dz-success').removeClass('dz-processing');
                _alert("Well Done! image cropped successfully");
               
            }
            else{
                _alert("Opppss! \n" + res.message);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('Some error occured, please try again');
        },
        complete: function () {
            waitingDialog.hide();
        }
    });
    return false;
}

function thumbUpdate(f,d){
    var thumb = $(f.previewTemplate);
    if(f.is_cropped === 1){
        //thumb.addClass('thumb-cropped');
        thumb.find('.dz-success-mark').after('<div class="thumb-cropped"><span></span></div>');
    }
    if(f.is_cover === 1){
        thumb.addClass('cover_img').attr('title','Cover Image');
    }
}

/*
 * Mukesh Soni return ids of uploaded media from dropzone.
 * @returns {getIds.ids|Array}
 */
function getIds(){
    var ids =  new Array();
    $('.dropzone .dz-preview').each(function(i,e){
        id = $(e).find('[data-dz-id]').val();
        ids.push(id);
    });
    return ids;
}

/*
 * Mukesh Soni
 * Send Request to server to update order in db
 */
function updateOrder(event, ui ){    
    if(_lastReq !== null){
        _lastReq.abort();
    }
    ids = getIds();
    _lastReq = $.ajax({
        url:media_action+'save-order',
        type:"POST",
        data:{ids:ids.join(',')},
        beforeSend:function(){},
        success:function(){},
        complete:function(){
            _lastReq = null;
        }
    });
}

/*
 * Mukesh Soni : Call on btn click and mark image as cover
 */
function set_cover(e){
    var _A = $(this);
    var id = _A.data('asset-id');
    var ids = getIds();
    ids = $.grep(ids, function(val) {
        return val != id;
    });
    $.ajax({
        url : media_action+'set-cover',
        data:{cover_id:id,uncover_id:ids},
        type:'post',
        beforeSend:function(){
            waitingDialog.show();
        },
        success:function(d){
            _alert('Image has been marked as cover image');
            $('.dropzone .dz-preview').removeClass('cover_img').attr('title','');
            _thumbparent = _thumb.parent().parent();
            
            _thumbparent.addClass('cover_img').attr('title','Cover Image');
            $('.dropzone .dz-default').after(_thumbparent);
            //_thumbparent.remove();
            $( ".dropzone" ).sortable("refresh");
        },
        error:function(){
            alert('Error Occured, Please try again');
        },
        complete:function(){
            waitingDialog.hide();    
        }
    });
    //alert('called');
    return false;
}

/*
 * Mukesh Soni : Display an alert message in popup only.
 * disapear after 3secs
 */
function _alert(msg){
    $('.alertmsg').remove();
    var $alert = [
      '<tr class="alertmsg"><td><div class="alert alert-success" role="alert">',
        '<i class="fa fa-bell"></i>',
        '<button type="button" class="close" data-dismiss="alert">&times;</button>',
        '<strong>',
        msg,
        '</strong>',
      '</div></td></tr>'
    ].join("");
    $('#tblCrop tbody tr#alert_msg').after($alert);
    setTimeout(function(){$('.alertmsg').fadeOut('slow')},3000);
}
function close_crop(e){
    $.magnificPopup.close();
    return false;
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}


(function($){
    $(document).ready(function(){
        $('div.dropzone').on('click','[data-dz-thumbnail]',start_crop);
        $(document).on('click','button.btn-crop',do_crop);
        $(document).on('click','button.btn-cover',set_cover);
        $(document).on('click','button.btn-close',close_crop);      
        $(".dropzone").sortable({
            items:'.dz-preview',
            helper : 'clone',
            revert: true,
            tolerance: "pointer",
            update: updateOrder
        });
    })  
    
})(jQuery);
