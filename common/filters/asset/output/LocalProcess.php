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


class VideoflowLocalPlay {

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
    $tools = new videoflowTools;
    if (!empty ($vparams->skin) && stripos($vparams->skin, 'http://') === FALSE && stripos($vparams->skin, 'https://') === FALSE) {  
        $vparams->skin = JURI::root().'components/com_videoflow/players/'.ltrim($vparams->skin, '/');
    } 
    
    // Set player size dynamically
    if (!empty($this->playerwidth)) $vparams->playerwidth = $this->playerwidth;
    if (!empty ($this->playerheight)) $vparams->playerheight = $this->playerheight;
        
    //Set default preview image, correcting for earlier versions where files name was save as full URL
    //Correct for ealier versions that saved full image URL    
    if (!empty($media->pixlink)) {
       if (stripos($media->pixlink, 'http://') === FALSE && stripos($media->pixlink, 'https://') === FALSE) {  
         $pixlink = JURI::root().$vparams->mediadir.'/_thumbs/'.$media->pixlink;
         } else {
         $pixlink = $media->pixlink;
         }
      } else if (empty($media->pixlink) && file_exists(JPATH_ROOT.DS.$vparams->mediadir.DS.'_thumbs'.DS.$media->title.'.jpg')){
       $pixlink = JURI::root().$vparams->mediadir.'/_thumbs/'.$media->title.'.jpg';
      } else {     
      $pixlink = JURI::root().'components/com_videoflow/players/preview.jpg';
      }    
    //Set media URL to full URL, excepting earlier versions where filename was saved as full URL 
    if ($media->server == 'local' && (stripos($media->file, 'http://') === FALSE && stripos($media->file, 'https://') === FALSE)) {
      include_once (JPATH_ROOT.DS.'components'.DS.'com_videoflow'.DS.'helpers'.DS.'videoflow_file_manager.php'); 
      $fm = new VideoflowFileManager;
      $subdir = $fm->getFileInfo ($media->type);
      $media->orr = $media->file;
      $media->file = JRoute::_(JURI::root().$vparams->mediadir.'/'.$subdir['dir'].'/'.$media->file);
    }
      
    $layout = JRequest::getString('layout');
          
    if ($media->type == 'mp3' || $media->type == 'wav' || $media->type == 'ogg') $mtype = 'audio'; else $mtype = 'video'; 
        
    //Defile common flash variables
      $smoothing = 'smoothing';
      $plogo = $vparams->logo;
      $loading = '';
      $autoval = 1;
      $vfskin = 'vfskin';
	    $controlbar = 'controlbar';
      $controlbarval = 'bottom';
      if ($layout == 'lightbox') $controlbarval = 'over';
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
      if ($media->type == 'mp3') $autoval = 'false';
      }   
    //Define Neolao flash variables     
    elseif ($vparams->player == 'neolao') {
    $vfcon = mt_rand();
    $vfplayer = JURI::root().'components/com_videoflow/players/neolao.swf?v='.$vfcon;
    $file = 'flv';
    if ($media->type == 'mp3') {
    $vfplayer = JURI::root().'components/com_videoflow/players/neomp3.swf?v='.$vfcon;
    $file = 'mp3';
    }
    $image = 'startimage';
    $autostart = "autoplay";
    $loading = JText::_('Loading').'_n_';
    $vlogo = 'top1';
    $plogo = $vparams->logo.'|-15|15';  
    }
    
    //Define JW flash variables
    
    elseif ($vparams->player == 'JW') {
    $vfplayer = JURI::root().'components/com_videoflow/players/jwplayer/jwplayer.flash.swf';
    $vfskin = 'skin';
    $image = 'image';
    $autostart = 'autostart';
    $vlogo = 'logo';  
    $vwidth = '100%';
    $vheight = '100%';        
    }
	
	//Define ME flash variables    
    elseif ($vparams->player == 'ME') {
    $vfplayer = JURI::root().'components/com_videoflow/players/me/flashmediaelement.swf';
	  $controlbar = 'controls';
	  $controlbarval = true;
    $vwidth = "100%";
    $vheight = "100%";
    } elseif ($vparams->player == 'projekktor') {
    $vfplayer =   JURI::root()."components/com_videoflow/players/projekktor/swf/StrobeMediaPlayback/StrobeMediaPlayback.swf";
    $vclass = 'projekktor';
    $autoplay = '';
    }  elseif ($vparams->player == 'videojs'){
    $vfplayer =   JURI::root()."components/com_videoflow/players/videojs/video-js.swf";
    $vclass = 'video-js vjs-default-skin';   
    if ($media->type == 'mp4' ||$media->type == 'webm' || $media->type == 'ogv' || $media->type == 'flv') $autoplay = '';   
    $vwidth = 'auto';
    $vheight = 'auto'; 
    }    
     
    //Facebook Embed Code    
    $c = JRequest::getCmd('c');
    $frm = JRequest::getBool('iframe');
    if ((!$frm && $c == 'fb') || $env == 'fb') {
    $embedcode = "$file=$media->file&width=$vparams->fbpwidth&height=$vparams->fbpheight&$autostart=$autoval&$image=$pixlink&$vlogo=$plogo&crop=false&controlColor=0x3fd2a3&controlBackColor=0x000000&id=$media->id&$vfskin=$vparams->skin&bgcolor=000000&bgcolor1=000000&bgcolor2=000000&showfullscreen=1&showvolume=1&showtime=2&controlbar=$controlbarval&dock=true&playertimeout=3000";
    if ($media->type == 'swf') $vfplayer = $media->file;
    return array('player'=>$vfplayer, 'flashvars'=>$embedcode);
    }                             
      
    //Projekktor player
        
    if ($vparams->player == 'projekktor_custom') {
      if (!empty($vparams->vsources) && $mtype !='audio') {
      $sources = '0: {src: "'.$media->file.'", type: "'.$mtype.'/'.$media->type.'"}';
     //     $sources = $this->gensources($media, ''); 
      } else {
      $sources = '0: {src: "'.$media->file.'", type: "'.$mtype.'/'.$media->type.'"}';
      }
      $embedcode = "jQuery(document).ready(function() {
        projekktor('#vfmediaspace', {
        poster: '".$pixlink."',
        title: '".$media->title."',
        playerFlashMP4: '".$vfplayer."',
        playerFlashMP3: '".$vfplayer."',
        width:'".$vparams->playerwidth."',
        height:'".$vparams->playerheight."',
        playlist: [
            {"
            .$sources.
            "}
        ]    
        }, function(player) {} // on ready 
        );
    }); ";
    return $embedcode;
    //JW Player
        
    } elseif ($vparams->player == 'JW') {
    $embedcode = "jwplayer('vfmediaspace').setup({";
	  if (empty($vparams->jwplayerurl)) {
		$embedcode .= "
		'flashplayer': 				 			               '$vfplayer',
		";
		}
		if (!empty($vparams->vsources) && $mtype !='audio') {
      $level = "{ file: '".$media->file."' },";
      $embedcode .= "levels: [".$this->gensources($media, $level)."],";
      } else {
      $embedcode .= "'$file':                                 '$media->file',";
      }
      $embedcode .= "
        'title':                                 '$media->title', 
        'repeat':                                 'false',     
        '$image':                                '$pixlink',
        'displayclick':                          'play',
        '$smoothing':                            'true',
        'stretching':                            'uniform',
        'controlbar':                            '$controlbarval',
        'id':                                    'vf_fidsPlayer',
        '$autostart':                            '$autoval',
        'dock':                                  'true',
        '$vfskin':                               '$vparams->skin',
        'width':                                 '$vwidth',
        'aspectratio':                           '16:9',
        'bgcolor':                               '000000',
        'bgcolor1':                              '000000',
        'bgcolor2':                              '000000',
        'margin':                                '5', 
        'showstop':                              '1',
        'showvolume':                            '1',
        'showtime':                              '2',
        'showfullscreen':                        '1', 
        'playertimeout':                         '3000',
        '$vlogo':                                '$plogo',
		    'logo.hide':				                     'false',
        'controlColor':                          '0x3fd2a3',
        'controlBackColor':                      '0x000000',
	      'buffer':                                '4'
    });";
    } elseif ($vparams->player == 'neolao' || $vparams->player == 'nonverblaster') {
    $embedcode = "
      var flashvars =
      {
        '$file':                                 encodeURIComponent('$media->file'),
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
		    'logo.hide':				 			               'false',
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
    } else {    
     if ($media->type == 'mp3' || $media->type == 'ogg' || $media->type == 'wav') {
			$embedcode = '<video class="'.$vclass.'" width="'.$vwidth.'" height="'.$vheight.'" style="max-width:'.$vwidth.'; max-height:'.$vheight.';" id="vf_fidsPlayer" poster="'.$pixlink.'" ';
			if ($media->type == 'mp3') $embedcode .= 'src="'.$media->file.'" type="audio/mpeg"';
			if ($media->type == 'ogg') $embedcode .= 'src="'.$media->file.'" type="audio/ogg"';
			if ($media->type == 'wav') $embedcode .= 'src ="'.$media->file.'" type="audio/wav"';
      $embedcode .= $autoplay;
			$embedcode .= ' controls="controls">';
			$embedcode .= JText::_('COM_VIDEOFLOW_MEDIA_ERR');
			$embedcode .= '</video>';  
    } else {
    $embedcode = '<video id="vf_fidsPlayer" class="'.$vclass.'" controls="controls"'.$autoplay.' poster="'.$pixlink.'" style="width:'.$vwidth.'; height:'.$vheight.';" width="'.$vwidth.'" height="'.$vheight.'" >';
		if ($media->type == 'mp4') {
		$source = '<source src="'.$media->file.'" type="video/mp4" title="'.$media->title.'" />';
		} elseif ($media->type == 'webm') {
		$source = '<source src="'.$media->file.'" type="video/webm" title="'.$media->title.'" />';
		} elseif ($media->type == 'ogv') {
		$source = '<source src="'.$media->file.'" type="video/ogv" title="'.$media->title.'" />';
		} elseif ($media->type == 'flv') {
		$source = '<source src="'.$media->file.'" type="video/flv" title="'.$media->title.'" />';
		}
		if (!empty($vparams->vsources)) $embedcode .= $this->genSources($media, $source); else $embedcode .= $source;
		$embedcode .= '<object width="'.$vwidth.'" height="'.$vheight.'" type="application/x-shockwave-flash" data="'.$vfplayer.'">
		<param name="movie" value="'.$vfplayer.'" />
		<param name="flashvars" value="controls=true&file='.$media->file.'" />
		<img alt="'.$media->title.'" src="'.$pixlink.'" width="'.$vwidth.'" height="'.$vheight.'" style="width:'.$vwidth.'; height:'.$vheight.';" title="'.JText::_("COM_VIDEOFLOW_MEDIA_ERR").'" />
		</object>';
		$embedcode .= '<img alt="'.$media->title.'" src="'.$pixlink.'" style="width:'.$vwidth.'; height:'.$vheight.';" width="'.$vwidth.'" height="'.$vheight.'" title="'.JText::_("COM_VIDEOFLOW_MEDIA_ERR").'" />';
		$embedcode .='</video>';
		}
    if ($vparams->player == 'ME') {
		$embedcode .= "<script>
					var vfplayer = jQuery('#vf_fidsPlayer').mediaelementplayer({
					features: ['playpause','progress','volume','fullscreen', 'backlight'],
					enableAutosize: true,
					enablePluginSmoothing: true,";
		if ($media->type == 'flv') $embedcode .= " plugins: ['flash','silverlight'], mode: 'shim',";
		$embedcode .=" showPosterWhenEnded: true
					});
					</script>";
    } elseif($vparams->player == 'projekktor') { 

    } elseif ($vparams->player == 'videojs') {
    $embedcode .= "<script>
                videojs('#vf_fidsPlayer', {'controls':true});
                videojs('#vf_fidsPlayer').ready(function(){
                  this.on('ended', function(){
                   this.posterImage.show();
                  });
                }); 
                </script>";
    }
    }      
    if ($media->type == 'swf') {
      $embedcode = "
      var flashvars =
      {};

      var params =
      {
        'allowscriptaccess':                     'always',
        'allowfullscreen':                       'true',
        'wmode':                                 'opaque'
      };

      var attributes =
      {
        'id':                                    'vf_fidsPlayer',
        'name':                                  'vf_fidsPlayer'
      };
      swfobject.embedSWF('$media->file', 'vfmediaspace', '$vparams->playerwidth', '$vparams->playerheight', '9', '$flashinstall', flashvars, params, attributes);
      ";
    }        
  return $embedcode;
  } 
  
      /*
    * Creates html5 compatible video tags with multiple sources
    * MP4 file must come first for ios  
    */

 function genSources($media, $source) {
	global $vparams;
	jimport('joomla.filesystem.file');  
	$sources = $levels = '';
  $index = 0;  
	$exts = array_diff($vparams->vsources, array($media->type));
	if (in_array('mp4', $exts)) {
    $fpath = JPATH_ROOT.DS.$vparams->mediadir.DS.'_altvideos'.DS.JFile::stripExt($media->filename).'.mp4'; 
		if (file_exists($fpath)) { 
    $file = JURI::root().'/'.$vparams->mediadir.'/_altvideos/'.JFile::stripExt($media->filename).'.mp4';
    $sources .= '<source src="'.$file.'" type="video/mp4" title="'.$media->title.'" />';
    $levels .= "{ file: '".$file."' },";
		}
    $exts = array_diff ($vparams->vsources, array('mp4'));
	}
	if ($vparams->player == 'JW') $levels .= $source; else $sources .= $source; 
	if (!empty($exts)) {	
		foreach ($exts as $ext) {
			$file =  JFile::stripExt(str_ireplace($vparams->mediadir.'/videos/', $vparams->mediadir.'/_altvideos/', $media->file)).'.'.$ext;
			if (file_exists($file)) {
        $sources .= '<source src="'.$file.'" type="video/'.$ext.'" title="'.$media->title.'" />';
        $levels .= "{ file: '".$file."' },";
        }
      }
	 }
 	 if ($vparams->player == 'JW'){
    $sources = substr($levels, 0, -1);
    }
   return $sources;
 }
}