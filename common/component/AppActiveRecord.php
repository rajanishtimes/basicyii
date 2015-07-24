<?php
namespace common\component;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\base\Event;
use yii\db\BaseActiveRecord;
use yii\base\Model;
use common\models\Tags;
use common\models\Tagmap;
use common\models\Tagassetmap;
use common\models\Asset;
use common\models\AssetTagTrait;
use yii\base\ModelEvent;
use common\models\Attribute;



class AppActiveRecord extends \yii\db\ActiveRecord
{
    use AssetTagTrait;
    
    private $isInsert=true;


    /**
     * @event ModelEvent an event that is triggered before soft deleting a record.
     * You may set [[ModelEvent::isValid]] to be false to stop the insertion.
     */
    const EVENT_BEFORE_SOFTDELETE = 'beforeSoftDelete';
    /**
     * @event Event an event that is triggered after a soft deleting
     */
    const EVENT_AFTER_SOFTDELETE = 'afterSoftDelete';
    
    /**
     * @event ModelEvent an event that is triggered before soft deleting a record.
     * You may set [[ModelEvent::isValid]] to be false to stop the insertion.
     */
    const EVENT_BEFORE_PUBLISH = 'beforePublish';
    /**
     * @event Event an event that is triggered after a soft deleting
     */
    const EVENT_AFTER_PUBLISH = 'afterPublish';
    
    /**
     * @event ModelEvent an event that is triggered before soft deleting a record.
     * You may set [[ModelEvent::isValid]] to be false to stop the insertion.
     */
    const EVENT_BEFORE_PUBLISHREADY = 'beforePublishReady';
    /**
     * @event Event an event that is triggered after a soft deleting
     */
    const EVENT_AFTER_PUBLISHREADY = 'afterPublishReady';
    
    /**
     * @event ModelEvent an event that is triggered before soft deleting a record.
     * You may set [[ModelEvent::isValid]] to be false to stop the insertion.
     */
    const EVENT_BEFORE_UNPUBLISH = 'beforeUnpublished';
    /**
     * @event Event an event that is triggered after a soft deleting
     */
    const EVENT_AFTER_UNPUBLISH = 'afterUnpublished';
    
    /**
     * @event ModelEvent an event that is triggered before soft deleting a record.
     * You may set [[ModelEvent::isValid]] to be false to stop the insertion.
     */
    const EVENT_BEFORE_DRAFT = 'beforeDraft';
    /**
     * @event Event an event that is triggered after a soft deleting
     */
    const EVENT_AFTER_DRAFT = 'afterDraft';
    
    /**
     * @event ModelEvent an event that is triggered before soft deleting a record.
     * You may set [[ModelEvent::isValid]] to be false to stop the insertion.
     */
    const EVENT_BEFORE_RESTORE = 'beforeRestore';
    /**
     * @event Event an event that is triggered after a soft deleting
     */
    const EVENT_AFTER_RESTORE = 'afterRestore';
    
    /**
     * @event ModelEvent an event that is triggered before soft deleting a record.
     * You may set [[ModelEvent::isValid]] to be false to stop the insertion.
     */
    const EVENT_BEFORE_TRANCINSERT = 'beforeTrancInsert';
    /**
     * @event Event an event that is triggered after a soft deleting
     */
    const EVENT_AFTER_TRANCINSERT = 'afterTrancInsert';
    
    /**
     * @event ModelEvent an event that is triggered before soft deleting a record.
     * You may set [[ModelEvent::isValid]] to be false to stop the insertion.
     */
    const EVENT_BEFORE_TRANCUPDATE = 'beforeTrancUpdate';
    /**
     * @event Event an event that is triggered after a soft deleting
     */
    const EVENT_AFTER_TRANCUPDATE = 'afterTrancUpdate';
    
    /**
     * @event Event an event that is triggered after a soft deleting
     */
    const EVENT_AFTER_STATE_CHANGED = 'afterStateChanged';
    
    const STATUS_DELETE = 0;
    const STATUS_PUBLISH = 1;
    const STATUS_PUBLISHREADY = 2;
    const STATUS_UNPUBLISH = 3;
    const STATUS_DRAFT = 4;
    const STATUS_SOURCED = 5;
    const STATUS_CANCEL = 6;
    const STATUS_OTHER = 7;
    const STATUS_TEMP_CLOSE = 8;
    const STATUS_PERMANENT_CLOSE = 9;
    const STATUS_OPENING_SOON = 10;
    const STATUS_CLOSING_FOR_SEASON = 11;
    const STATUS_ABORT = 100;
    
    const REDIRECT_TYPE_CANONICAL = 1;
    const REDIRECT_TYPE_301 = 301; //Moved Permanently
    const REDIRECT_TYPE_302 = 302; //Temp Moved
    
    public $redirect_types = [
        self::REDIRECT_TYPE_CANONICAL   =>  'Canonical',
        self::REDIRECT_TYPE_301         =>  '301 (Moved Permanently)',
        self::REDIRECT_TYPE_302         =>  '302 (Temporary Redirect)',
    ];
    
    
    
    public $statusnames = [
        self::STATUS_DELETE         => 'Deleted',
        self::STATUS_PUBLISH        => 'Active',
        //self::STATUS_DRAFT          => 'Draft',
    ];
    
    public $statenames = [
        self::STATUS_DELETE           => 'Delete',
        self::STATUS_PUBLISH          => 'Publish',
        self::STATUS_PUBLISHREADY     => 'Ready to be publish',
        self::STATUS_UNPUBLISH        => 'Unpublish',
        self::STATUS_DRAFT            => 'Draft',
        self::STATUS_SOURCED          => 'Sourced',
        self::STATUS_ABORT            => 'Aborted',
        self::STATUS_CANCEL           => 'Cancelled',
        self::STATUS_OTHER            => 'Other',
        self::STATUS_TEMP_CLOSE       => 'Temporary Closed',
        self::STATUS_PERMANENT_CLOSE  => 'Closed',
        self::STATUS_OPENING_SOON  => 'Opening Soon',
        self::STATUS_CLOSING_FOR_SEASON  => 'Closed for the Season'
            
        
    ];
    
    
    
    
    public function StateNameArray() {
        $ret = $this->statenames;
        unset($ret[self::STATUS_DELETE]);
        return $ret;
    }

    /**
     * 
     * @return string
     */
    public function ClassSortNeme() {
        return join('', array_slice(explode('\\', $this->className()), -1));
    }

    public function behaviors()
    {
        return array(
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['createdOn', 'updatedOn'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updatedOn',
                ],
                'value' => new Expression('NOW()'),
            ],
            'user' => [
                'class' => 'yii\behaviors\BlameableBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['createdBy', 'updatedBy'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updatedBy',
                ],
            ],
            'ip' => [
                'class' => 'yii\behaviors\AttributeBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'ip',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'ip',
                ],
                'value' => function($event){
                    if(method_exists(Yii::$app->request, 'getUserIP')){
                        return Yii::$app->request->getUserIP();
                    }else{
                        return '127.0.0.1';
                    }
                    
                }
            ],
                
             'audit' => [
                'class' => 'common\sammaye\LoggableBehavior',
                
            ],        
            'publishurl'=>[
                'class' => 'common\sammaye\PublishUrlBehavior',
            ]        
                
                 
        );
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'createdByUser' => 'Created By',
            'updatedByUser' => 'Updated By',
        ];
    }
    
    public function beforeTrancSave() {
        $event = new ModelEvent;
        \yii::trace('event generated:'.__METHOD__,'event');
        $this->isInsert = $this->getIsNewRecord();
        $this->trigger($this->isInsert ? self::EVENT_BEFORE_TRANCINSERT : self::EVENT_BEFORE_TRANCUPDATE, $event);
        
        return $event->isValid;
    }
    
    public function afterTrancSave() {
        \yii::trace('event generated:'.__METHOD__,'event');        
        $this->trigger($this->isInsert ? self::EVENT_AFTER_TRANCINSERT : self::EVENT_AFTER_TRANCUPDATE);
    }
    
    public function beforeSoftDelete() {
        $event = new ModelEvent;
        \yii::trace('event generated:'.__METHOD__,'event');
        $this->trigger(self::EVENT_BEFORE_SOFTDELETE, $event);

        return $event->isValid;
    }
    
    public function afterStateChanged() {
        \yii::trace('event generated:'.__METHOD__,'event');
        $this->trigger(self::EVENT_AFTER_STATE_CHANGED);
    }
    
    public function afterSoftDelete() {
        \yii::trace('event generated:'.__METHOD__,'event');
        $this->trigger(self::EVENT_AFTER_SOFTDELETE);
    }
    
    public function beforePublish() {
        $event = new ModelEvent;
        \yii::trace('event generated:'.__METHOD__,'event');
        $this->trigger(self::EVENT_BEFORE_PUBLISH, $event);

        return $event->isValid;
    }
    
    public function afterPublish() {
        \yii::trace('event generated:'.__METHOD__,'event');
        $this->trigger(self::EVENT_AFTER_PUBLISH);
    }
    
    public function beforePublishReady() {
        $event = new ModelEvent;
        \yii::trace('event generated:'.__METHOD__,'event');
        $this->trigger(self::EVENT_BEFORE_PUBLISHREADY, $event);

        return $event->isValid;
    }
    
    public function afterPublishReady() {
        \yii::trace('event generated:'.__METHOD__,'event');
        $this->trigger(self::EVENT_AFTER_PUBLISHREADY);
    }
    
    public function beforeUnPublish() {
        $event = new ModelEvent;
        \yii::trace('event generated:'.__METHOD__,'event');
        $this->trigger(self::EVENT_BEFORE_UNPUBLISH, $event);

        return $event->isValid;
    }
    
    public function afterUnPublish() {
        \yii::trace('event generated:'.__METHOD__,'event');
        $this->trigger(self::EVENT_AFTER_UNPUBLISH);
    }
    
    public function beforeDraft() {
        $event = new ModelEvent;
        \yii::trace('event generated:'.__METHOD__,'event');
        $this->trigger(self::EVENT_BEFORE_DRAFT, $event);

        return $event->isValid;
    }
    
    public function afterDraft() {
        \yii::trace('event generated:'.__METHOD__,'event');
        $this->trigger(self::EVENT_AFTER_DRAFT);
    }
    
    public function beforeRestore() {
        $event = new ModelEvent;
        \yii::trace('event generated:'.__METHOD__,'event');
        $this->trigger(self::EVENT_BEFORE_RESTORE, $event);

        return $event->isValid;
    }
    
    public function afterRestore() {
        \yii::trace('event generated:'.__METHOD__,'event');
        $this->trigger(self::EVENT_AFTER_RESTORE);
    }
    
    
    public function is_deletable() {
        return $this->beforeSoftDelete();
    }


    public function softdelete() {        
        if($this->hasAttribute('state')){
            if($this->state == self::STATUS_DRAFT){
                return $this->delete();
            }
        }
        
        if (!$this->beforeSoftDelete()) {
            return false;
        }
        \yii::trace($this->hasAttribute('state'), 'checkState');
        if($this->hasAttribute('state')){
            $this->state = self::STATUS_DELETE;
            \yii::trace('set State::'.$this->statenames[$this->state], 'setState');
        }        
        $this->status = self::STATUS_DELETE;
        \yii::trace('set Status::'.$this->statusnames[$this->status], 'setStatus');
        
        $isSave = parent::save(false);
        if($isSave){
            $this->afterSoftDelete();
        }
        return $isSave;
    }
    
    public function restore() {
        if (!$this->beforeRestore()) {
            return false;
        }
        \yii::trace($this->hasAttribute('state'), 'checkState');
        if($this->hasAttribute('state')){
            $this->state = self::STATUS_PUBLISHREADY;
            \yii::trace('set State::'.$this->statenames[$this->state], 'setState');
        }        
        $this->status = self::STATUS_PUBLISH;
        \yii::trace('set Status::'.$this->statusnames[$this->status], 'setStatus');
        
        $isSave = parent::save(false);
        if($isSave){
            $this->afterRestore();
        }
        return $isSave;
    }
    
    public function sendtopublish() {
        if (!$this->beforePublishReady()) {
            return false;
        }
        \yii::trace($this->hasAttribute('state'), 'checkState');
        if($this->hasAttribute('state')){
            $this->state = self::STATUS_PUBLISHREADY;
            \yii::trace('set State::'.$this->statenames[$this->state], 'setState');
        }        
        $this->status = self::STATUS_PUBLISH;
        \yii::trace('set Status::'.$this->statusnames[$this->status], 'setStatus');
        
        $isSave = parent::save();
        if($isSave){
            $this->afterPublishReady();
        }
        return $isSave;
    }
    
    public function published($statusupdate = true) {
        if (!$this->beforePublish()) {
            return false;
        }
        \yii::trace($this->hasAttribute('state'), 'checkState');
        
        if($this->hasAttribute('state')){
            $this->state = self::STATUS_PUBLISH;
            \yii::trace('set State::'.$this->statenames[$this->state], 'setState');
        } 
        
        if($statusupdate){
            $this->status = self::STATUS_PUBLISH;
            \yii::trace('set Status::'.$this->statusnames[$this->status], 'setStatus');        
        }
        $isSave = parent::save();
        if($isSave){
            $this->afterPublish();
        }
        return $isSave;
    }
    
    
    public function statuspublished() {
        $isSave = parent::save();
        if($isSave){
            $this->afterStateChanged();
        }
        return $isSave;
    }
    
    
    
    public function is_unpublishable() {
        return $this->beforePublish();
    }
    
    public function unpublished() {
        if (!$this->beforeUnPublish()) {
            return false;
        }
        \yii::trace($this->hasAttribute('state'), 'checkState');
        if($this->hasAttribute('state')){
            $this->state = self::STATUS_UNPUBLISH;
            \yii::trace('set State::'.$this->statenames[$this->state], 'setState');
        }        
        $this->status = self::STATUS_UNPUBLISH;
        \yii::trace('set Status::'.$this->statusnames[$this->status], 'setStatus');        
        $isSave = parent::save();
        if($isSave){
            $this->afterUnPublish();
        }
        return $isSave;
    }
    
    public function saveAsDraft(){
        if (!$this->beforeDraft()) {
            return false;
        }
        
        //\yii::trace('in action save draft model: '.__METHOD__,'theater');
        //\yii::trace($this->hasAttribute('state'), 'checkState');
        if($this->hasAttribute('state')){
            $this->state = self::STATUS_DRAFT;
            //\yii::trace('set State::'.$this->statenames[$this->state], 'setState');
        }        
        $this->status = self::STATUS_PUBLISH;
        //\yii::trace('set Status::'.$this->statusnames[$this->status], 'setStatus');
        $isSave = $this->save(false);
        if($isSave){
            $this->afterDraft();
        }
        return $isSave;
    }
    
    public function editsave($runValidation = true, $attributeNames = null){        
        \yii::trace($this->hasAttribute('state'), 'checkState');
        if($this->hasAttribute('state')){
            $this->state = self::STATUS_UNPUBLISH;
            \yii::trace('set State::'.$this->statenames[$this->state], 'setState');
        }        
        $this->status = self::STATUS_PUBLISH;
        \yii::trace('set Status::'.$this->statusnames[$this->status], 'setStatus');
        return $this->save($runValidation, $attributeNames);
    }
    
    
    public function checkdraft() {
        if($this->state == self::STATUS_DRAFT){
            return (\yii::$app->user->id == $this->createdBy) ? true : false;
        }else{
            return true;
        }
    }
    
    public function getStatuses(){
        return $this->statusnames;
    }
    
    public function getStatusName() {        
        return $this->statusnames[$this->status];
    }
    
    public function getCreateTime() {        
        return date('D, jS M Y \a\t g:ia',  strtotime($this->createdOn));
    }
    
    public function getUpdateTime() {        
        return date('D, jS M Y \a\t g:ia',  strtotime($this->updatedOn));
    }
    
    public function converttoArray($datastr) {
        if (preg_match('/\,/', $datastr) ) {
            $dataArr = explode(',', $datastr);
        } else if ( preg_match('/\|/', $datastr) ) {
            $dataArr = explode('|', $datastr);
        } else {
            $dataArr[] = trim($datastr);
        }
        return $dataArr;
    }
    
    /**
     * Sets the attribute values in a massive way.
     * @param array $values attribute values (name => value) to be assigned to the model.
     * @param boolean $safeOnly whether the assignments should only be done to the safe attributes.
     * A safe attribute is one that is associated with a validation rule in the current [[scenario]].
     * @see safeAttributes()
     * @see attributes()
     */
    public function setAttributes($values, $safeOnly = true)
    {
        if (is_array($values)) {
            $attributes = array_flip($safeOnly ? $this->safeAttributes() : $this->attributes());
            foreach ($values as $name => $value) {
                try{
                    //Yii::trace("Raj ".$name." in '" . $value);
                    
                    if (isset($attributes[$name])) {
                        $this->$name = $value;
                    } elseif ($safeOnly) {
                        $this->onUnsafeAttribute($name, $value);
                    }
                }catch(\yii\base\InvalidCallException $e){                    
                }
            }
        }
    }
    
    public function fieldsError() {
        return [];
    }

    public function fieldsWarning() {
        return [];
    }

    public function checkError($getError = true) {
        if($getError && $this->hasErrors()){
            $error = ['error'=>[],'warning'=>[],'other'=>[]];
            foreach($this->getErrors() as $field=>$fielderror){
                if(in_array($field, $this->fieldsError())){
                    $error['error'][$field] = $fielderror;
                }else if (in_array($field, $this->fieldsWarning())){
                    $error['warning'][$field] = $fielderror;
                }else{
                    $error['other'][$field] = $fielderror;
                }
            }
            return ['status' => !$this->hasErrors(), 'errors' => $error];
        }else{
            return ['status' => !$this->hasErrors()];
        }
    }
    
    public function getListingstat() {
        $data = [
            self::STATUS_DELETE => 0,
            self::STATUS_PUBLISH => 0,
            self::STATUS_PUBLISHREADY => 0,
            self::STATUS_UNPUBLISH  => 0,
            self::STATUS_DRAFT      => 0,
        ];
        
        $queryresult = $this->find()->select(['state','count(*) AS cnt'])->groupBy('state')->asArray()->all();
        
        foreach($queryresult as $model){
            $data[$model['state']] = $model['cnt'];
        }
        
        $data[self::STATUS_DRAFT] = $this->find()->where(['state'=>self::STATUS_DRAFT,'createdBy'=>  \yii::$app->user->id])->count();
        
        $dataval = [];
        foreach($data as $id=>$val){
            $dataval[$this->statenames[$id]] = $val;
        }
        
        return new \yii\data\ArrayDataProvider([
            'allModels' => [$dataval],
        ]);
    }
    
    public function getAppStateName() {        
        return $this->statusnames[$this->state];
    }
    
    public static function urlFilter($str)
    {        
        $str	=	trim($str);
        $str	=	str_replace("/","-",$str);
        $str	=	str_replace("&","and",$str);
        $str	=	str_replace("@","at",$str);
        $str	=	str_replace(".","",$str);
        $str	=	str_replace("  "," ",$str);
        $str	=	str_replace(" ","-",$str);
        $str	=	preg_replace('/[^A-Za-z0-9\.\/_-]+/', '', $str);
        $str	=	preg_replace('/\-{2,}/','-',$str);
        $str	=	str_replace("/-","/",$str);
        $str	=	str_replace("\/-","/",$str);
        $str	=	str_replace("-/","/",$str);
        $str	=	str_replace("-\/","/",$str);
        $str	=	str_replace("\-/","/",$str);
        $str	=	strtolower($str);
        return $str;
    }
    
    public static function seftxt($txt) {        
        $org_txt = str_replace("\\", '', stripslashes($txt));
	$txt = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $txt);
        $replce_ = array(
            ' ' => '-',
            '/' => '-',
            "\\" => '-',
            ',' => '-',
            ';' => '-',
            '>' => '-',
            '<' => '-',
            '--' => '-',
            '---' => '-',
            '----' => '-',
            '(' => '-',
            ')' => '-',
            '~' => '-',
            "'" => '',
            "!" => '',
            '"' => '-',
            '#039' => '-',
            "^0" => '',
            ":" => '',
            "." => '',
            "?" => '',
            "#" => '',
            "%" => '',
            "$" => '',
            '&' => '-');
        $txt = strtr($txt, $replce_);
        $txt = strtr($txt, $replce_);
        $txt = trim($txt, '-');
        $txt = strtolower($txt);

       
        return $txt;
    }

    public static function getAttributeId($attrib_name =''){
        $model = Attribute::findOne(['name'=>$attrib_name]);
        if($model)
            return $model->id;
        else
            return 0;
    }
    
    public function saveManyToMany($map,$raw_data){
        //Count the max length of datakeys array
        $datakey_count = count($map['datakey']);
        $max_length = 0;
        if($datakey_count >1){
            foreach($map['datakey'] as $idx => $field_name){
                if(!empty($raw_data[$idx]) && count($raw_data[$idx]) > $max_lenght){
                    $max_length = count($raw_data[$idx]);
                }
            }
        }
        else{
            reset($map['datakey']);
            $max_length = count($raw_data[key($map['datakey'])]);
        }
        try{
            for($i=0; $i<$max_length; $i++){
                $map_model = new $map['map_class'];                     //Object creation of map class
                foreach ($map['datakey'] as $data_idx => $f){           //the loop will go to how many fields mapped in datakey array
                    if(isset($raw_data[$data_idx][$i]))
                        $map_model->{$f} = $raw_data[$data_idx][$i];        //assign data value to each fields of map table
                }
                $map_model->{$map['foreign_key']} = $this->id;          //assign value to foreign key of active model
                if(!$map_model->save()){
                    return false;
                }
            }
        }
        catch(Exception $e){
            return false;
        }
       return true;
    }
    
    public function saveMeta($meta,$tbl){
        $db = Yii::$app->db;
        $db->createCommand('UPDATE '.$tbl.' SET meta = '.$meta.' WHERE id=1')->execute();
    }
    
    public static function sanitizeUrl($url){
        $url = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $url);
	$url = strtolower(trim($url, '-'));
        $url = str_replace(['--','//'], ['-','/'], $url);
	$url = preg_replace("/[_|+ -]+/", '-', $url);
        if(strpos($url,'/')!==0){
            $url = '/'.$url; 
        }
        //$url              = strtolower($url);
        //$url              = preg_replace('/[^(\x20-\x7f)]*/s','',$url);
        //$url              = preg_replace("/[^A-Za-z0-9/]+/", '', $url);
        //$url              = str_replace([' ',',',':','?','!','&','.'], ['-','','','','','',''], $url);
        //filter_var($url,FILTER_SANITIZE_URL);
        return $url;
    }
    
    public function removeAllBehaviour(){
        $this->detachBehavior('user');
        $this->detachBehavior('timestamp');
        $this->detachBehavior('ip');
    }
}
