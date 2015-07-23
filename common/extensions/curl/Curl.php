<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace curl;
use yii;
use yii\base\Component;
use yii\base\Exception;

class Curl extends Component{
    
    /**
     *
     * @var string 
     */
    protected $url;
    
    /**
     *
     * @var resource
     */
    public $ch;
    
    
    private $multi;
    
    /**
     *
     * @var array 
     */
    public $options = array();
    
    public $info = array();
    
    public $error_code = 0;
    
    public $error_string = '';
    
    public $cookie = '';
    
    protected $validOptions = array(
        'timeout'=>array('type'=>'integer'),
        'login'=>array('type'=>'array'),
        'proxy'=>array('type'=>'array'),
        'proxylogin'=>array('type'=>'array'),
        'setOptions'=>array('type'=>'array'),
    );

    
    /**
    * Initialize the extension
    * check to see if CURL is enabled and the format used is a valid one
    */
    public function init(){
        if( !function_exists('curl_init') ){
            throw new Exception( yii::t('Curl', 'You must have CURL enabled in order to use this extension.') );        
        }
        yii::trace ( 'initiated Curl', 'Curl' );
        $this->ch = curl_init();
    }
    
    
    /**
    * Setter
    * @set the option
    */
    public function setOption($key,$value){
        //yii::trace ( 'Curl option set:'.$value, 'Curl' );
        curl_setopt($this->ch,$key, $value);
    }
    
    
    /**
    * Formats Url if http:// dont exist
    * set http://
    */
    public function setUrl($url){
        if(!preg_match('!^\w+://! i', $url)) {
            $url = 'http://'.$url;
        }
        //yii::trace ( 'Curl option set url:'.$url, 'Curl' );
        $this->url = $url;
    }
    
    /*
    * Set Url Cookie
    */
    public function setCookies($values){
        if (!is_array($values)){
            throw new Exception(yii::t('Curl', 'options must be an array'));
        }else{
            $params = $this->cleanPost($values);
        }
        yii::trace ( 'Curl option Cookie Set:'.$params, 'Curl' );
        $this->setOption(CURLOPT_COOKIE, $params);
    }
    
    /*
    @VALID OPTION CHECKER
    */
    protected static function checkOptions($value, $validOptions){
        if (!empty($validOptions)) {
            foreach ($value as $key=>$val) {	
                if (!array_key_exists($key, $validOptions)) {
                    throw new Exception(yii::t('Curl', '{k} is not a valid option', array('{k}'=>$key)));
                }
                $type = gettype($val);
                if ((!is_array($validOptions[$key]['type']) && ($type != $validOptions[$key]['type'])) || (is_array($validOptions[$key]['type']) && !in_array($type, $validOptions[$key]['type']))) {
                    throw new Exception(yii::t('Curl', '{k} must be of type {t}',
                        array('{k}'=>$key,'{t}'=>$validOptions[$key]['type'])));
                }
                
                if (($type == 'array') && array_key_exists('elements', $validOptions[$key])) {
                    self::checkOptions($val, $validOptions[$key]['elements']);
                }
            }
        }
    }
    
    
    protected function checksslurl($url){
        if((parse_url($url,PHP_URL_SCHEME) == 'https')||(parse_url($url,PHP_URL_PORT) == 443) ){
            $this->setOption(CURLOPT_SSLVERSION,3);
            $this->setOption(CURLOPT_SSL_VERIFYPEER, FALSE);
            $this->setOption(CURLOPT_SSL_VERIFYHOST, 2);
        }
    }
    
    
    /*
    @DEFAULTS
    */
    protected function defaults(){
        //!isset($this->options['timeout']) ? $this->setOption(CURLOPT_TIMEOUT,30) : $this->setOption(CURLOPT_TIMEOUT,$this->options['timeout']);
        isset($this->options['setOptions'][CURLOPT_HEADER]) ? $this->setOption(CURLOPT_HEADER,$this->options['setOptions'][CURLOPT_HEADER]) : $this->setOption(CURLOPT_HEADER,FALSE);
        isset($this->options['setOptions'][CURLOPT_RETURNTRANSFER]) ? $this->setOption(CURLOPT_RETURNTRANSFER,$this->options['setOptions'][CURLOPT_RETURNTRANSFER]) : $this->setOption(CURLOPT_RETURNTRANSFER,TRUE);
        //isset($this->options['setOptions'][CURLOPT_FOLLOWLOCATION]) ? $this->setOption(CURLOPT_FOLLOWLOCATIO,$this->options['setOptions'][CURLOPT_FOLLOWLOCATION]) : $this->setOption(CURLOPT_FOLLOWLOCATION,TRUE);
        isset($this->options['setOptions'][CURLOPT_FAILONERROR]) ? $this->setOption(CURLOPT_FAILONERROR,$this->options['setOptions'][CURLOPT_FAILONERROR]) : $this->setOption(CURLOPT_FAILONERROR,TRUE);
    }
    
    /*
    @MAIN FUNCTION FOR PROCESSING CURL
    */
    public function run($url,$GET = TRUE,$POSTSTRING = array()){
        //yii::trace ( 'Execute Curl:'.$url, 'Curl' );
        //yii::trace ( 'Execute Curl post string:'.PHP_EOL . print_r ( $POSTSTRING, true ), 'Curl' );
        yii::log ( 'Execute Curl: url ['.$url.'] postval: '.PHP_EOL . print_r ( $POSTSTRING, true ),'info', 'ext.curl.Curl' );
        $this->setUrl($url);
        if( !$this->url ){
            throw new Exception( yii::t('Curl', 'You must set Url.') );	
        }
        self::checkOptions($this->options,$this->validOptions);
        $this->checksslurl($url);
        if($GET == TRUE){
            $this->setOption(CURLOPT_URL,$this->url);
            $this->defaults();
        }else if($GET == FALSE){
            $this->setOption(CURLOPT_URL,$this->url);
            $this->defaults();
            $this->setOption(CURLOPT_POST, TRUE);
            $this->setOption(CURLOPT_POSTFIELDS, $this->cleanPost($POSTSTRING));
        }
        
        if(isset($this->options['setOptions'])){
            foreach($this->options['setOptions'] as $k=>$v){
                $this->setOption($k,$v);
            }
        }

        isset($this->options['login']) ? $this->setHttpLogin($this->options['login']['username'],$this->options['login']['password']) : null;
        isset($this->options['proxy']) ? $this->setProxy($this->options['proxy']['url'],$this->options['proxy']['port']) : null;
        if(isset($this->options['proxylogin'])){
            if(!isset($this->options['proxy']))
                throw new Exception( yii::t('Curl', 'If you use "proxylogin", you must define "proxy" with arrays.') );
            else
                $this->setProxyLogin($this->options['login']['username'],$this->options['login']['password']);
        }

        $return = curl_exec($this->ch);
        // Request failed
        if($return === FALSE){
            $this->error_code = curl_errno($this->ch);
            $this->error_string = curl_error($this->ch);
            $this->info = curl_getinfo($this->ch);
            yii::trace ( 'Execute Curl error:'.print_r(array('code'=>$this->error_code,'msg'=>$this->error_string,),true), 'Curl' );
            curl_close($this->ch);

        // Request successful
            if($GET == TRUE){
                $key = md5($this->url);
                if($cache = yii::$app->getCache()){
                    $return = $cache->get($key);
                }
                return $return;
            }
        } else {
            $this->info = curl_getinfo($this->ch);
            yii::trace ( 'Execute Curl error:'.print_r($this->info,true), 'Curl' );
            curl_close($this->ch);
            if($GET == TRUE){
                $key = md5($this->url);
                if($cache = yii::$app->getCache()){
                    $return = $cache->get($key);
                }
                return $return;
            }
        }
    }
    
    
    /*
    @MAIN FUNCTION FOR PROCESSING CURL
    */
    public function prepare($url,$GET = TRUE,$POSTSTRING = array()){
        yii::trace ( 'Prepare Curl:'.$url, 'Curl' );
        yii::trace ( 'Prepare Curl post string:'.$POSTSTRING, 'Curl' );
        
        $this->setUrl($url);
        if( !$this->url ){
            throw new Exception( yii::t('Curl', 'You must set Url.') );
        }
        
        //$this->ch = curl_init();
        
        $this->checksslurl($url);
        
        self::checkOptions($this->options,$this->validOptions);
        
        if($GET == TRUE){
            $this->setOption(CURLOPT_URL,$this->url);
            $this->defaults();
        }else if($GET == FALSE){
            $this->setOption(CURLOPT_URL,$this->url);
            $this->defaults();
            $this->setOption(CURLOPT_POST, TRUE);
            $this->setOption(CURLOPT_POSTFIELDS, $this->cleanPost($POSTSTRING));
        }
        if(isset($this->options['setOptions'])){
            foreach($this->options['setOptions'] as $k=>$v){
                $this->setOption($k,$v);
            }
        }
    }
    
    
    public function execute(){
        yii::trace ( 'Execute Curl', 'Curl' );
        $return = curl_exec($this->ch);
        // Request failed
        if($return === FALSE){
            $this->error_code = curl_errno($this->ch);
            $this->error_string = curl_error($this->ch);
            curl_close($this->ch);
            echo "Error code: ".$this->error_code."<br />";
            echo "Error string: ".$this->error_string;
        // Request successful
        } else {
            $this->info = curl_getinfo($this->ch);
            curl_close($this->ch);
            return $return;
        }
    }
    
    /**
    * Arrays are walked through using the key as a the name. Arrays
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
        $r =& substr($thePostString, 1) ;	
        return $r ;
    }
    
    /*
    @LOGIN REQUEST
    sets login option
    If is not setted , return false
    */
    public function setHttpLogin($username = '', $password = '') {
        $this->setOption(CURLOPT_USERPWD, $username.':'.$password);
    }
    
    /*
    @PROXY SETINGS
    sets proxy settings withouth username
    */
    public function setProxy($url,$port = 80){
        $this->setOption(CURLOPT_HTTPPROXYTUNNEL, TRUE);
        $this->setOption(CURLOPT_PROXY, $url.':'.$port);
    }
    
    /*
    @PROXY LOGIN SETINGS
    sets proxy login settings calls onley if is proxy setted
    */
    public function setProxyLogin($username = '', $password = '') {
        $this->setOption(CURLOPT_PROXYUSERPWD, $username.':'.$password);
    }
    
    /*
    @MAIN FUNCTION FOR PROCESSING CURL
    */
    public function addChannel(Curl $ch,$name){
        yii::trace ( 'CHannel Add for multi-curl-query', 'Curl' );
        yii::trace ( 'CHannel Add name:'.$name, 'Curl' );
        $this->multi[$name] = $ch->ch;
        curl_multi_add_handle($this->ch, $ch->ch);
    }
    
    /*
    @MAIN FUNCTION FOR PROCESSING CURL
    */
    public function multiexecute(){
        yii::trace ( 'Multi Curl executing', 'Curl' );
        $running = null;
        do {
            curl_multi_exec($this->ch, $running);
        } while($running > 0);
        
        // get content and remove handles
        foreach($this->multi as $id => $c) {
            $result[$id] = curl_multi_getcontent($c);
            curl_multi_remove_handle($mh, $c);
        }	
        // all done
        curl_multi_close($mh);
        yii::trace ( 'Multi Curl executing result:'.PHP_EOL . print_r ( $result, true ), 'Curl' );
        return $result;	
    }
    
    
    public function setCookie(){
        if($this->cookie == ''){
            foreach(yii::app()->request->getCookies() as $cookie){
                //$cookie->name;
                $this->cookie .= $cookie->name.'=' . yii::app()->request->cookies[$cookie->name] . ';';
                //yii::log ( 'Execute Curl: cookie: '.$cookie->name.'=' . yii::app()->request->cookies[$cookie->name] . ';','info', 'ext.curl.Curl' );
            }
        }
        $this->setOption(CURLOPT_COOKIE, $this->cookie);
    }
    
    /*
    @MAIN FUNCTION FOR PROCESSING CURL
    */
    public function asyncrun($url,$GET = TRUE,$POSTSTRING = array()){
        $this->setCookie();
        //yii::trace ( 'Execute Curl post string:'.PHP_EOL . print_r ($_COOKIE, true ), 'Curl' );
        //yii::trace ( 'Execute Curl:'.$url, 'Curl' );
        //yii::trace ( 'Execute Curl post string:'.PHP_EOL . print_r ( $POSTSTRING, true ), 'Curl' );
        //yii::log ( 'Execute Curl: url ['.$url.'] postval: '.PHP_EOL . print_r ( $POSTSTRING, true ),'info', 'ext.curl.Curl' );
        
        $this->setUrl($url);
        $this->checksslurl($url);
        
        if( !$this->url ){
            throw new Exception( yii::t('Curl', 'You must set Url.') );	
        }
        
        self::checkOptions($this->options,$this->validOptions);
        $this->setOption(CURLOPT_TIMEOUT,1);
        $this->setOption(CURLOPT_TIMEOUT_MS, 20);
        
        if($GET == TRUE){
            $this->setOption(CURLOPT_URL,$this->url);
            $this->defaults();
        }else if($GET == FALSE){
            $this->setOption(CURLOPT_URL,$this->url);
            $this->defaults();
            $this->setOption(CURLOPT_POST, TRUE);
            $this->setOption(CURLOPT_POSTFIELDS, $this->cleanPost($POSTSTRING));
        }
        
        if(isset($this->options['setOptions'])){
            foreach($this->options['setOptions'] as $k=>$v){
                $this->setOption($k,$v);
            }
        }
        
        $return = curl_exec($this->ch);
        curl_close($this->ch);
        //yii::log ( 'Execute Curl End: url ['.$url.']','info', 'ext.curl.Curl' );
    }
}