<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\modules\media\uploader;

use Yii;
//use Closure;
use yii\base\Model;
use yii\base\InvalidConfigException;

/**
 * AttributeBehavior automatically assigns a specified value to one or multiple attributes of an ActiveRecord object when certain events happen.
 *
 * To use AttributeBehavior, configure the [[attributes]] property which should specify the list of attributes
 * that need to be updated and the corresponding events that should trigger the update. For example,
 * Then configure the [[value]] property with a PHP callable whose return value will be used to assign to the current
 * attribute(s). For example,
 *
 * ~~~
 * use yii\behaviors\AttributeBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => AttributeBehavior::className(),
 *             'attributes' => [
 *                 ActiveRecord::EVENT_BEFORE_INSERT => 'attribute1',
 *                 ActiveRecord::EVENT_BEFORE_UPDATE => 'attribute2',
 *             ],
 *             'value' => function ($event) {
 *                 return 'some value';
 *             },
 *         ],
 *     ];
 * }
 * ~~~
 *
 * @author Luciano Baraglia <luciano.baraglia@gmail.com>
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class UploadMethods extends Model
{
    public $model = 'common\models\Asset';
    //use UploadTrait;
    /**
     * @var array list of converters that are to be automatically executed to convert request.
     */
    public $converters = [
        'common\modules\media\uploader\converter\FileUploadConverter',
        'common\modules\media\uploader\converter\KalturaConverter',
        'common\modules\media\uploader\converter\YoutubeConverter',
    ];
    
    /**
     * 
     * @param arry $data
     * @return array
     * @throws InvalidConfigException
     */
    public function upload($data=[],$path='')
    {
        foreach ($this->converters as $i => $convert) {
            \yii::trace('running converter'.$convert);
            $this->converters[$i] = $convert = Yii::createObject($convert);
            if (!$convert instanceof UploaderInterface) {
                throw new InvalidConfigException(get_class($convert) . ' must implement common\modules\media\uploader\UploaderInterface');
            }
            
            $mediadata = $convert->convert($this->model,$data,$path);
            if ($mediadata !== null) {
                return $mediadata;
            }
        }
    }
}
