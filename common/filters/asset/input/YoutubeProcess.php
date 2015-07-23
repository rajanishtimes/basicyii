<?php

//VideoFlow - Joomla Multimedia System for Facebook//
/**
* @ Version 1.2.1 
* @ Copyright (C) 2008 - 2011 Kirungi Fred Fideri at http://www.fidsoft.com
* @ VideoFlow is free software
* @ Visit http://www.fidsoft.com for support
* @ Kirungi Fred Fideri and Fidsoft accept no responsibility arising from use of this software 
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
**/

class VideoflowRemoteProcessor {

    /**
     * Gets video info from third-patry websites
     */
  
    function processLink ($vlink) {
    $parselink = parse_url(trim($vlink));
    $data = $this->runTool('readRemote', $vlink);
    if (!empty($data)) {
    $videoinfo = $this->runTool ('getTags', $data);  
    }
    if (!isset($videoinfo) || !is_object($videoinfo)) $videoinfo = new stdClass();
    $vstring = substr($vlink, stripos($vlink, 'v='));
    if (!empty($vstring)) parse_str($vstring);
    if (!empty($v)) {
    $videoinfo->medialink = 'http://www.youtube.com/watch?v='.$v;
    $videoinfo->pixlink = 'http://img.youtube.com/vi/'.$v.'/0.jpg';
    $videoinfo->file = $v;
    } else {
    $videoinfo->medialink = $vlink;
    $videoinfo->pixlink = '';
    $videoinfo->file = $vlink;
    } 
    $videoinfo->type = "yt";
    $videoinfo->server = "youtube";
    $videoinfo->cat = $this->processCat($data);
    return $videoinfo;
    }
    
    
    function processCat($data)
    {
      $cat = '';
      if (!empty($data)) {
      $regex = '#id="eow-category"><a href=".*">([^</a>].*)</a>#i';
      preg_match($regex, $data, $out);
      if (is_array($out) && !empty($out[1])) $cat = $out[1];
      }
    return $cat;
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