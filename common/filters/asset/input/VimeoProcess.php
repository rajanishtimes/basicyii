<?php

//VideoFlow - Joomla Multimedia System for Facebook//

/**
* @ Version 1.1.4
* @ Copyright (C) 2008 - 2010 Kirungi Fred Fideri at http://www.fidsoft.com
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
    $parselink = parse_url(trim($vlink));
    if (!empty($parselink['fragment'])) {
    $plink = $parselink['fragment'];
    } else if (!empty($parselink['path'])){
    $plink = $parselink['path'];
    } else {
    $plink = $vlink;
    }
    preg_match("/\d+/", $plink, $vcode);
    if (!is_array($vcode) || empty($vcode[0])) return false;
    $vlink = "http://www.vimeo.com/".$vcode[0];
    $data = $this->runTool('readRemote', $vlink);
    if (!empty($data)){    
    $videoinfo = $this->runTool('getTags', $data);    
   }     
    $vregex = '/<[\s]*meta[\s]*property="?([^>"]*)"?[\s]*content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si';
    preg_match_all ($vregex, $data, $out, PREG_PATTERN_ORDER); 
    for ($i=0;$i < count($out[1]);$i++) {
        if (strtolower($out[1][$i]) == 'og:title') $videoinfo->title = $out[2][$i];
        if (strtolower($out[1][$i]) == 'og:image') $videoinfo->pixlink = $out[2][$i];
        if (strtolower($out[1][$i]) == 'og:url') $videoinfo->medialink = $out[2][$i];
        if (strtolower($out[1][$i]) == 'og:image') $videoinfo->pixlink = $out[2][$i];
        if (strtolower($out[1][$i]) == 'og:description') $videoinfo->details = $out[2][$i];
    } 
  
    if (empty($videoinfo->medialink)) $videoinfo->medialink = $vlink;
    

    $videoinfo->file = $vcode[0];
    $videoinfo->cat = null;
    $videoinfo->type = 'vm';
    $videoinfo->server = 'vimeo';
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