<?php

//VideoFlow - Joomla Multimedia System for Facebook//

/**
* @ Version 1.1.4
* @ Copyright (C) 2008 - 2011 Kirungi Fred Fideri at http://www.fidsoft.com
* @ VideoFlow is free software
* @ Visit http://www.fidsoft.com for support
* @ Kirungi Fred Fideri and Fidsoft accept no responsibility arising from use of this software 
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
**/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

class VideoflowRemoteProcessor {

    /**
     * Gets video info from third-patry websites
     */
  
  function processLink ($vlink) {
    $videoinfo = false;
    $parseurl = parse_url (trim($vlink));
    $vstring = substr($vlink, stripos($vlink, 'v='));
    if (!empty($vstring)) parse_str($vstring);
    if (!empty($v)) {
    $videoinfo = new stdClass;
    $videoinfo->title = null;
    $videoinfo->pixlink = null;
    $videoinfo->details = null;
    $videoinfo->tags = null; 
    $videoinfo->file = $v;
    $videoinfo->cat = null;
    $videoinfo->type = 'fb';
    $videoinfo->server = 'facebook';
    $videoinfo->medialink = 'http://www.facebook.com/video/video.php?v='.$v;
    } else {
   JError::raiseWarning(500, JText::_('Facebook does not allow embedding this video on this site.'));
    }
    return $videoinfo;
    }
 }  