<?php

namespace api\component;

/**
 * Utitly code will be placed here
 *
 * @author mukesh
 */
class Utility {
    /*
     * Mukesh Soni : This function will return only 10 digit mobile no.
     */
    public static function cleanMobileNo($phone){
        if(strpos($phone, '+91') !== false){
            $phone = preg_replace('/\+91/', "", $phone);
        }
        $phone = trim($phone);
        return $phone;
    }
    
    public static function getCleanFBUrl($url){
        $url_components = parse_url($url);
        return $$url_components['host'].$url_components['path'];
    }
    
    /*
     * @author: Mukesh Soni
     * @description: function will return only domain name from url excluding http/https and rest path of url.
     */
    public static function getDomainFromURL($url){
		
		$pattern = ';(?:https?://)?(?:[a-zA-Z0-9.-]+?\.(?:com|net|org|gov|edu|mil|in)|\d+\.\d+\.\d+\.\d+);';
		$index = preg_match($pattern, $url);		
		if($index){
			$url_components = parse_url($url);
			if(strpos($url, 'http') === false){
				$path=explode('/',$url_components['path']);
				return $path[0];
			}
			else{
				return $url_components['host'];
			}
		}
		else
		return false;
    }      
}
