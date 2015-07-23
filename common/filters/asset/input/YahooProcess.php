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
        if (empty($videoinfo->title)) {
        preg_match('/<title>([^>]*)<\/title>/si', $data, $out);
        if (isset($out) && is_array($out) && count($out) > 0) $videoinfo->title = strip_tags(htmlspecialchars_decode($out[1]));
        }
    if (empty($videoinfo->medialink)) $videoinfo->medialink = $vlink;
    preg_match('/so.addVariable\("id",[\s]["|\']([^"|\']*)["|\'\)]/i', $data, $out);
    preg_match('/so.addVariable\("vid",[\s]["|\']([^"|\']*)["|\'\)]/i', $data, $out1);
    if (!empty($out[1]) && !empty($out1[1])) {
    $vcode = $out1[1].'|'.$out[1];
    } else {
    $parselink = parse_url(trim($videoinfo->medialink));
      if (stripos($parselink['path'], 'watch') !== false) {
          $vcode = substr ($parselink['path'], 7);
          $vcode = str_replace('/', '|', $vcode);
          } else {
          JError::raiseWarning(500, JText::_('Yahoo! has placed restrictions on this video. It may not work on this site.'));              
              $vstring = substr($vlink, stripos($vlink, 'v='));
              if (!empty($vstring)) {
              parse_str($vstring);
              if (!empty($v)) $vcode = $v;
              }
          }
    }
    if (!empty($vcode)) $videoinfo->file = $vcode; else $videoinfo->file = null;
    $videoinfo->cat = null;
    $videoinfo->type = 'yv';
    $videoinfo->server = 'yahoo';
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