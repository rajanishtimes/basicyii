<?php

//VideoFlow - Joomla Multimedia System for Facebook//

/**
* @ Version 1.2.0 
* @ Copyright (C) 2008 - 2014 Kirungi Fred Fideri at http://www.fidsoft.com
* @ VideoFlow is free software
* @ Visit http://www.fidsoft.com for support
* @ Kirungi Fred Fideri and Fidsoft accept no liability arising from use of this software 
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
**/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class VideoflowDailymotionPlay {

    /**
     * Generates embed codes needed to playback media    
     *      
     */
    
  var $playerwidth;
  
  var $playerheight;

	
  //Build embed code; 
  
  function buildEmbedcode ($media, $env = null) {
    global $vparams;
    if (defined('VFPLGPLAYER')) $autoplay = '0'; else $autoplay = '1';
    // Set player size dynamically
    if (!empty($this->playerwidth)) $vparams->playerwidth = $this->playerwidth;
    if (!empty ($this->playerheight)) $vparams->playerheight = $this->playerheight;
    
    //Player size in lightbox popup. Normally bigger than default size.
    $layout = JRequest::getString('layout');
    if ($layout == 'lightbox'){
    $vparams->playerheight = $vparams->lplayerheight;
    $vparams->playerwidth = $vparams->lplayerwidth;
    }

    
    //Set set a default preview image, if there is none associated with current media file
    if (!empty($media->pixlink)) {
    $pixlink = $media->pixlink;
    } else {   
    $pixlink = JURI::root().'components/com_videoflow/players/preview.jpg';
    }
    
    
    //Facebook embedcode - Embeds video on Facebook. Not required if you are not using the Facebook application

    $c = JRequest::getCmd('c');
    $frm = JRequest::getBool('iframe');
    if ((!$frm && $c == 'fb') || $env == 'fb') {
      $vfplayer = 'http://www.dailymotion.com/swf/video/'.$media->file.'?autoPlay=1';
      $embedcode = '';
    return array('player'=>$vfplayer, 'flashvars'=>$embedcode);
    }
     
    
    
    /*
    * We use javascript to embed the player 
    */    

   /*
    $embedcode = "
      var flashvars =
      {
      };

      var params =
      {
        'allowscriptaccess':                     'always',
        'allowfullscreen':                        'true',
        'bgcolor':                               '#FFFFFF',
        'wmode':                                 'opaque'
      };

      var attributes =
      {
        'id':                                    'vf_fidsPlayer',
        'name':                                  'vf_fidsPlayer'
      };

      swfobject.embedSWF('http://www.dailymotion.com/swf/video/".$media->file."?autoPlay=1&enableApi=1&playerapiid=vf_fidsPlayer', 'vfmediaspace', '$vparams->playerwidth', '$vparams->playerheight', '9', false, flashvars, params, attributes);
      ";
    

$embedcode = '<div class="vfrespiframe"><div id="vfplayer'.$media->id.'"></div></div>

<script>
    (function() {
        var e = document.createElement("script"); e.async = true;
        e.src = document.location.protocol + "//api.dmcdn.net/all.js";
        var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(e, s);
    }());
    window.dmAsyncInit = function()
    {
        var vfdplayer'.$media->id.' = DM.player("vfplayer'.$media->id.'", {video: "'.$media->file.'", width: "'.$vparams->playerwidth.'", height: "'.$vparams->playerheight.'", autoplay:'.$autoplay.', id:vf_fidsPlayer'.$media->id.'});
        vfdplayer'.$media->id.'.addEventListener("apiready", function(e)
        {
           // e.target.play();
        });
    };
</script>';

*/

$embedcode = '<div class="vfrespiframe"><iframe src="http://www.dailymotion.com/embed/video/'.$media->file.'?autoplay='.$autoplay.'" width="'.$vparams->playerwidth.'" height="'.$vparams->playerheight.'" frameborder="0"></iframe></div>';
    
    // If you prefer, you could use the standard embed code commented out below
    
    /*
    $embedcode = "
    <object width='$vparams->playerwidth' height='$vparams->playerheight'>
    <param name='movie' value='http://www.dailymotion.com/swf/video/".$media->file."?autoPlay=1'></param>
    <param name='allowFullScreen' value='true'></param>
    <param name='allowscriptaccess' value='always'></param>
    <embed src='http://www.dailymotion.com/swf/video/".$media->file."?autoPlay=1' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' width='$vparams->playerwidth' height='$vparams->playerheight'></embed>
    </object>";
    */
    //End standard embed code;
    
    
    return $embedcode;
  }
}