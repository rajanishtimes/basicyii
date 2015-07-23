<?php

//VideoFlow - Joomla Multimedia System for Facebook//
/**
* @ Version 1.2.1 
* @ Copyright (C) 2008 - 2014 Kirungi Fred Fideri at http://www.fidsoft.com
* @ VideoFlow is free software
* @ Visit http://www.fidsoft.com for support
* @ Kirungi Fred Fideri and Fidsoft accept no liability arising from use of this software 
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
**/



defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class VideoflowYoutubePlay {

    /**
     * Generates embed codes needed to playback media    
     *      
     */
    
  var $playerwidth;
  
  var $playerheight;


  //Build embed code; 

  function buildEmbedcode ($media, $env = null) {
    global $vparams;
    include_once(JPATH_ROOT.DS.'components'.DS.'com_videoflow'.DS.'helpers'.DS.'videoflow_tools.php');
    $device = new VideoflowTools;
    $this->mobile = $device->detectMobile();
    if (!empty ($vparams->skin) && stripos($vparams->skin, 'http://') === FALSE && stripos($vparams->skin, 'https://') === FALSE) {  
      $vparams->skin = JURI::root().'components/com_videoflow/players/'.ltrim($vparams->skin, '/');
    }
    
    // Set player size dynamically
    if (!empty($this->playerwidth)) $vparams->playerwidth = $this->playerwidth;
    if (!empty ($this->playerheight)) $vparams->playerheight = $this->playerheight;
    
    //Player size in lightbox popup. Normally bigger than default size.

    $controlbarval = 'bottom';
    $layout = JRequest::getString('layout');
    if ($layout == 'lightbox'){
    $vparams->playerheight = $vparams->lplayerheight;
    $vparams->playerwidth = $vparams->lplayerwidth;
    $controlbarval = 'over';
    }
    
    //Defile common flash variables
    $smoothing = 'smoothing';
    $plogo = $vparams->logo;
    $loading = '';
    $autoval = 1;
    $vfskin = 'vfskin';
    $flashinstall = JURI::root().'components/com_videoflow/players/expressInstall.swf';
    $file = 'file';
    $image = 'image';
    $autostart = 'autostart';
    $vlogo = 'logo';
    $vclass = '';
    $autoplay = ' autoplay="autoplay"';
    $vwidth = $vparams->playerwidth;
    $vheight = $vparams->playerheight;
    
    //Define NonverBlaster flash variables
  
    if ($vparams->player == 'nonverblaster'){
    $vfplayer = JURI::root().'components/com_videoflow/players/NonverBlaster.swf';   
    $file = 'mediaURL';
    $image = 'teaserURL';
    $smoothing = 'allowSmoothing';
    $autostart = 'autoPlay';
    $vlogo = 'indentImageURL';
    $autoval = 'true';
    } elseif ($vparams->player == 'JW') {
    $vfplayer = JURI::root().'components/com_videoflow/players/jwplayer/jwplayer.flash.swf';
    $vfskin = 'skin';
    $autostart = 'autostart';
    }

    //Set set a default preview image, if there is none associated with current media file

    if (!empty($media->pixlink)) {
    $pixlink = $media->pixlink;
    } else if (file_exists(JPATH_ROOT.DS.$vparams->mediadir.DS.'_thumbs'.DS.$media->title.'jpg')) {   
    $pixlink = JURI::root().$vparams->mediadir.'/_thumbs/'.$media->title.'jpg';
    } else {
    $pixlink = JURI::root().'components/com_videoflow/players/preview.jpg';
    }
    
    //Facebook embedcode - Embeds video on Facebook. Not required if you are not using the Facebook application
    $c = JRequest::getCmd('c');
    $frm = JRequest::getBool('iframe');

    if ((!$frm && $c == 'fb') || $env == 'fb') {
      if (($vparams->player == 'JW' || $vparams->player == 'nonverblaster') && $vparams->jwforyoutube) {
      $embedcode = "$file=$media->medialink&width=$vparams->fbpwidth&height=$vparams->fbpheight&$autostart=$autoval&$image=$pixlink&$vlogo=$plogo&crop=false&controlColor=0x3fd2a3&controlBackColor=0x000000&id=$media->id&$vfskin=$vparams->skin&logo.hide=false";
      } else {
      $vfplayer = 'http://www.youtube.com/v/'.$media->file.'?autoplay=1&fs=1&rel=0';
      $embedcode = '';
      }
    return array('player'=>$vfplayer, 'flashvars'=>$embedcode);
    }
	
	if (($vparams->player == 'ME' || $vparams->player == 'projekktor' || $vparams->player == 'videojs') && $vparams->jwforyoutube) {   
		$vwidth = $vheight = '100%';
    if ($vparams->player == 'videojs') {
      $vclass = 'video-js vjs-default-skin';
      $vwidth = 'auto';
      $vheight = 'auto';
    } elseif ($vparams->player == 'projekktor') {
      $vclass = 'projekktor';
    } else {
      $vclass = '';
    }
    $embedcode = '<video id="vf_fidsPlayer" class="'.$vclass.'" width="'.$vwidth.'" height="'.$vheight.'" style="max-width:'.$vwidth.'; max-height:'.$vheight.';" preload="auto" src="'.$media->medialink.'" type="video/youtube" title="'.$media->title.'" controls="controls"></video>';
    if ($vparams->player == 'ME') {      
		$embedcode .= "<script>
					jQuery('#vf_fidsPlayer').mediaelementplayer({
					features: ['playpause','progress','volume','fullscreen'],
					enableAutosize: true,
					enablePluginSmoothing: true,
					showPosterWhenEnded: true
					});
					</script>";
   } elseif($vparams->player == 'videojs') {
     $embedcode .= "<script>videojs('#vf_fidsPlayer', {'controls':true, 'ytcontrols': false, 'techOrder': ['youtube']});</script>";
   }
	} elseif (($vparams->player == "JW" || $vparams->player == 'nonverblaster') && $vparams->jwforyoutube) {
    if (!empty ($vparams->flashhtml5) && $vparams->player == 'JW') {
    $vwidth = '100%';
    $embedcode = "
    jwplayer('vfmediaspace').setup({
        'flashplayer': 				 '$vfplayer',
        'file':                                  '$media->medialink',
        'title':                                 '$media->title',
        'image':                                 '$pixlink',
        'displayclick':                          'play',
        'controlbar':                            '$controlbarval',
        'smoothing':                             'true',
        'repeat':                                'none',
        'stretching':                            'uniform',
        'id':                                    'vf_fidsPlayer',
        'autostart':                             'true',
        'skin':                                  '$vparams->skin',
        'width':                                 '$vwidth',
        'aspectratio':                           '16:9',
        'logo':                                  '$vparams->logo',
        'logo.hide':				 'false',
        'dock':                                  'true'
    });";
    } else {
    $embedcode = "
      var flashvars =
      {
        '$file':                                 encodeURIComponent('$media->medialink'),
        'title':                                 '$media->title', 
        '$image':                                '$pixlink',
        'displayclick':                          'play',
        '$smoothing':                             'true',
        'repeat':                                'none',
        'stretching':                            'uniform',
        'controlbar':                            '$controlbarval',
        'id':                                    'vf_fidsPlayer',
        '$autostart':                            '$autoval',
        'dock':                                  'true',
        '$vfskin':                               '$vparams->skin',
        'width':                                 '$vparams->playerwidth',
        'height':                                '$vparams->playerheight',
        'bgcolor':                               '000000',
        'bgcolor1':                              '000000',
        'bgcolor2':                              '000000',
        'margin':                                '5', 
        'showstop':                              '1',
        'showvolume':                            '1',
        'showtime':                              '2',
        'showfullscreen':                        '1', 
        'playertimeout':                         '3000',
        'buffermessage':                         '$loading',
        'showiconplay':                          '1',
        '$vlogo':                                '$plogo',
	      'logo.hide':				 'false',
        'controlColor':                          '0x3fd2a3',
        'controlBackColor':                      '0x000000',
        'scaleIfFullScreen':                     'true',
	      'showScalingButton':                     'true',
	      'showTimecode':                          'true',
	      'crop':                                  'false',
	      'buffer':                                '4'
      };

      var params =
      {
        'allowfullscreen':                       'true',
        'allowscriptaccess':                     'always',
        'bgcolor':                               '#000000',
        'wmode':                                 'opaque'
      };

      var attributes =
      {
        'id':                                    'vf_fidsPlayer',
        'name':                                  'vf_fidsPlayer'
      };

      swfobject.embedSWF('$vfplayer', 'vfmediaspace', '$vparams->playerwidth', '$vparams->playerheight', '9', '$flashinstall', flashvars, params, attributes);
      ";
    }
  } else {
  $embedcode = "<div class='vfrespiframe'> <iframe class='youtube-player' type='text/html' width='$vparams->playerwidth' height='$vparams->playerheight' src='http://www.youtube.com/embed/$media->file?autoplay=1&enablejsapi=1&playerapiid=vf_fidsPlayer".$media->id."&rel=0' frameborder='0'  allowscriptaccess='always' allowfullscreen='true'>
  </iframe></div>";
  }
  return $embedcode;
  }
}