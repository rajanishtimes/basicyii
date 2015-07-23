<?php

/* 
 * Coder: Mithun Mandal
 * Contact: 01206636679/8527580960
 * Project: Timescity CMS Using Yii2
 */

namespace common\modules\media\uploader;

use yii;

trait UploadTrait {
    
    /**
     * 
     * @param mixed $model 
     */
    public function getModel($model,$hash='',$id=0,$table=''){
        if($hash){
            return $model::findOne(['mediahash' => $hash]);
        }elseif ($id !=0 && $table!='') { 
            if($armodel = $model::findOne(['remoteId' => $id,'table'=>$table])){
                return $armodel;
            }else{
                return new $model();
            }
        }else{
            return new $model();
        }
    }
    
    public function getDataFromChannel($url,$isPost=false,$postvariable=[]) {        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if($isPost){
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->cleanPost($postvariable));
        }

        $resp = curl_exec($ch);
        curl_close($ch);
        return $resp;
    }
    
    /**
    * Arrays are walked through using the key as a the name.  Arrays
    * of Arrays are emitted as repeated fields consistent with such things
    * as checkboxes.
    * @desc Return data as a post string.
    * @param mixed by reference data to be written.
    * @param string [optional] name of the datum.
    */

   protected function &cleanPost(&$string, $name = NULL)
   {
        $thePostString = '' ;
        $thePrefix = $name ;

        if (is_array($string)){
                foreach($string as $k => $v){
                        if ($thePrefix === NULL){
                                $thePostString .= '&' . self::cleanPost($v, $k) ;
                        }else{
                                $thePostString .= '&' . self::cleanPost($v, $thePrefix . '[' . $k . ']') ;
                        }
                }

        }else{
                $thePostString .= '&' . urlencode((string)$thePrefix) . '=' . urlencode($string) ;
        }		
        $r = substr($thePostString, 1) ;		
        return $r ;
   }
    
    public function GetVideoThumb($VideoCode, $width, $height, $quality=80,$size = 'default', $type = 'Kaltura') {
        yii::trace($VideoCode, __METHOD__);
        if(!$VideoCode){ return false;}
        switch ($type){
            case "Kaltura" :    $format = "http://cdn.kaltura.com/p/0/thumbnail/entry_id/[CODE]/width/[WIDTH]/height/[HEIGHT]/type/1/quality/[QUALITY]";
                                $url = str_replace(array('[CODE]','[WIDTH]','[HEIGHT]','[QUALITY]'), array($VideoCode,$width,$height,$quality), $format);
                                yii::trace($url, __METHOD__);
                                break;
            case "Youtube" :    $format = "http://img.youtube.com/vi/[CODE]/[SIZE].jpg";
                                $url = str_replace(array('[CODE]','[SIZE]'), array($VideoCode,$size), $format);
                                yii::trace($url, __METHOD__);
                                break;
        }
        return $url;
    }
    
    
    public static function GetVideoCode($VideoCode,$embedCode, $type) {
        switch (strtolower($type)){
            case "kaltura" :    $code = array(
                                    'video_code'=>$VideoCode,
                                    'est_video_source'=>'Kaltura',
                                );
                                break;
            case "youtube" :
                                yii::trace($embedCode, __METHOD__);
                                $code = stripslashes(urldecode($embedCode));
                                
                                preg_match_all("/(youtube.com|youtube-nocookie.com)\/(v|embed)\/([a-zA-Z0-9_\-\|\.]+)/i", $code, $url_array);
                                yii::trace(print_r($url_array,true), __METHOD__);                                
                                
                                $code = array(
                                    'video_code'=>$url_array[3][0],
                                    'est_video_source'=>'youtube',
                                );
                                break;
            
            case "geobeats" :
                                yii::trace($embedCode, __METHOD__);
                                $code = stripslashes(urldecode($embedCode));
                                
                                preg_match_all("/geobeats.com([\/]+)videoclips([\/]+)embed([\/]+)([a-zA-Z0-9_\-\|\.]+)/i", $code, $url_array);
                                yii::trace(print_r($url_array,true), __METHOD__);                                
                                
                                $code = array(
                                    'video_code'=>$url_array[4][0],
                                    'est_video_source'=>'geobeat',
                                );
                                break;

            default :
                                $code = stripslashes(urldecode($embedCode));
                                $pos = strpos($code, '<embed');
                                if($pos !== false){
                                    preg_match_all("/src=(.*)/", $code, $url_array);
                                    yii::trace(print_r($url_array,true), __METHOD__);
                                    $urls = explode(' ',$url_array[1][0]);
                                    $url = trim($urls[0],'"');
                                    $code = $this->GetVideoCodeGeoBeatsFromUrl($url);
                                }
                                break;
        }
        return $code;
    }
    
    // added to play video other then youtube or kaltura 20 june 2014
    public static function GetVideoCodeGeoBeatsFromUrl($url)
    {
        //$Curl = parse_url($url);
        //$code = explode('/embed/',$Curl['path']);
        //yii::trace($code, __METHOD__);
        return array('video_code'=>$url,'est_video_source'=>'Geobeats');
    }
}