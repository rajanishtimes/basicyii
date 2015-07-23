<?php
namespace common\helpers;

/**
 * Description of TextHelper
 * This is text utitly used in various context
 * @author mukesh soni
 */
class TextHelper {
    //put your code here
    
    /*
     * @author: mukesh soni
     * @description : will truncate text to specific lenght
     * @param $str : string to be truncate
     * @param $len : lenth to truncate
     * @param $elipse : incase lenght is small $elipse would be added to the end of text
     */
    public function truncate($str,$len = 50,$elipse = '...'){
        if(strlen($str) > $len){
            return substr($str, 0,$len-3).$elipse;
        }  
        else{
            return $str;
        }
    }
    
    /*
     * @author: mukesh soni
     * @description : will remove all tags from text
     * @param $text : string to become plan text
     */
    public function toPlanText($text){
        return strip_tags($text);
    }
    
    /*
     * @author: mukesh soni
     * @description : will return text with , seprated
     * @param infinate argument list
     */
    public function mergerWithComma(){
        $res = [];
        $arg_list = func_get_args();
        foreach($arg_list as $arg){
            if(!empty($arg)){
                if(!is_array($arg)){
                    $res[] = $arg;
                }
                else{
                    $res = array_merge($res,$arg);
                }
            }
        }
        return implode(', ', $res);
    }    
    
}
