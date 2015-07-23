<?php

//VideoFlow - Joomla Multimedia System for Facebook//
/**
* @ Version 1.1.6 
* @ Copyright (C) 2008 - 2011 Kirungi Fred Fideri at http://www.fidsoft.com
* @ VideoFlow is free software
* @ Visit http://www.fidsoft.com for support
* @ Kirungi Fred Fideri and Fidsoft accept no responsibility arising from use of this software 
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
**/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class VideoflowFacebookPlay {

    /**
     * Generates embed codes needed to playback media    
     *      
     */
    
  var $playerwidth;
  
  var $playerheight;

  //Build embed code; 

  function buildEmbedcode ($media, $env = null) {

    global $vparams;
    
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
    } else if (file_exists(JPATH_ROOT.DS.$vparams->mediadir.DS.'_thumbs'.DS.$media->title.'jpg')) {   
    $pixlink = JURI::root().$vparams->mediadir.'/_thumbs/'.$media->title.'jpg';
    } else {
    $pixlink = JURI::root().'components/com_videoflow/players/preview.jpg';
    }
    
    // Facebook Player
   
    $vfplayer = 'http://www.facebook.com/v/'.$media->file;

  
    //Facebook embedcode - Embeds video on Facebook. Not required if you are not using the Facebook application

    $c = JRequest::getCmd('c');
    $frm = JRequest::getBool('iframe');
    if ((!$frm && $c == 'fb') || $env == 'fb') {
    return array('player'=>$vfplayer, 'flashvars'=>'');
    }

    $embedcode = "
    <object width='$vparams->playerwidth' height='$vparams->playerheight'>
    <param name='movie' value='$vfplayer'></param>
    <param name='allowFullScreen' value='true'></param>
    <param name='allowscriptaccess' value='always'></param>
    <embed src='$vfplayer' type='application/x-shockwave-flash' allowscriptaccess='always' allowfullscreen='true' width='$vparams->playerwidth' height='$vparams->playerheight'></embed>
    </object>";
 
    return $embedcode;
  }
}