<?php
namespace common\helpers;

use Yii;
use yii\helpers\Url;

/**
 * Description of helper
 * This is basic utility helper
 * @author mukesh soni
 */
class UtilityHelper {
    
    /*
     * @author: mukesh soni
     * @description : will change format of given date 
     * @param $date : string to be truncate
     * @param $format : return format, default would be 31-01-2015 03:55: pm
     */
    public function ArrayToMetaString($array){
        return json_encode($array);
        
    }
    
    /*
     * @author : mukesh soni
     * @descripiton: check whether url belongs to this site or another website.
     * @return : boolean true/false onle
     */
    
    public static function isInternalUrl($url){
        $return = false;
        $base = Url::base(true);
        $base_component = parse_url($base);
        $url_component  = parse_url($url);
        if(!empty($url_component['host'])){
            if($base_component['host'] == $url_component['host']){
                $return = true;
            }
        }
        else{   // our url sometime don't have associated host name in.
            $return = true;
        }
        return $return;    
    }
    
    
    
    public static function get_array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if ( ! isset($value[$columnKey])) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( ! isset($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if ( ! is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    
}
    
}
