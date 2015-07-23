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
    $videoinfo = new stdClass; 
    $data = $this->runTool('readRemote', $vlink);
    if (!empty($data)){    
    $videoinfo = $this->runTool('getTags', $data);    
    }     
    $vregex = '/<[\s]*meta[\s]*property="?([^>"]*)"?[\s]*content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si';
    preg_match_all ($vregex, $data, $out, PREG_PATTERN_ORDER); 
    for ($i=0;$i < count($out[1]);$i++) {
        if (strtolower($out[1][$i]) == 'og:image') $videoinfo->pixlink = $out[2][$i];
        if (strtolower($out[1][$i]) == 'og:url') $videoinfo->medialink = $out[2][$i];
    }
    if (empty($videoinfo->title)) {
        preg_match('/<title>([^>]*)<\/title>/si', $data, $out);
        if (isset($out) && is_array($out) && count($out) > 0) $videoinfo->title = strip_tags(htmlspecialchars_decode($out[1]));
        }
    if (!empty($videoinfo->title) && stripos ($videoinfo->title, 'Dailymotion - ') !== false) {
    $videoinfo->title = substr($videoinfo->title, 14); 
    }    
    if (empty($videoinfo->medialink)) $videoinfo->medialink = $vlink;
    $parselink = parse_url(trim($videoinfo->medialink));
    $vcode = substr ($parselink['path'], 7);
    $vcode = substr($vcode, 0, stripos($vcode, '_'));
    if (!empty($vcode)) $videoinfo->file = $vcode; else $videoinfo->file = $videoinfo->medialink;
    $videoinfo->cat = null;
    $videoinfo->type = 'dm';
    $videoinfo->server = 'dailymotion';
    return $videoinfo;
    }
      
  function runTool($func=null, $param1=null, $param2=null, $param3=null, $param4=null)
    {
    include_once JPATH_COMPONENT_SITE.DS.'helpers'.DS.'videoflow_tools.php';
    $tools = new VideoflowTools();
    $tools->func   = $func;
    $tools->param1 = $param1;
    $tools->param2 = $param2;
    $tools->param3 = $param3;
    $tools->param4 = $param4;
    return $tools->runTool();
    }  
}  