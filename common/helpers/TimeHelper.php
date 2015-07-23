<?php
namespace common\helpers;

/**
 * Description of TextHelper
 * This is time utitly used in various context
 * @author mukesh soni
 */
class TimeHelper {
    
    /*
     * @author: mukesh soni
     * @description : will change format of given date 
     * @param $date : string to be truncate
     * @param $format : return format, default would be 31-01-2015 03:55: pm
     */
    public function format($date,$format = 'M d, Y h:ia'){
        if(!empty($date)){
            return date($format,strtotime($date));
        }
        else {
            return false;
        }
    }
    
    /*
     * @author: mukesh soni
     * @description : date and time in single form
     * @param $date : string to be truncate
     * @param $time : lenth to truncate
     * @param $validate : validate both time and date to be in good condition else it will return only validate factor
     * @param $format : return format, default would be 31-01-2015 03:55: pm
     */
    public function mergeDateTime($date,$time,$validate = false,$format = 'M d, Y h:ia'){
        if(!$validate){
            return date($format,strtotime($date.' '.$time));
        }
        else{
            $str_date = strtotime($date);
            $str_time = strtotime($time);
            $hasDateValid = $hasTimeValid = false;
            if(!empty($str_date)){
                $valid = $date;
                $hasDateValid = true;
            }
            if(!empty($str_time)){
                $valid .=  $time;
                $hasTimeValid = true;
            }
            if($hasDateValid && $hasTimeValid){
                return date($format,strtotime($valid));
            }
            else{
                return $valid;
            }
        }
    }   
    
}
