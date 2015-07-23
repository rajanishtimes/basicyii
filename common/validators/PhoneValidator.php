<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\validators;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\JsExpression;
use yii\helpers\Json;
use yii\validators\Validator;
use yii\validators\ValidationAsset;

/**
 * RegularExpressionValidator validates that the attribute value matches the specified [[pattern]].
 *
 * If the [[not]] property is set true, the validator will ensure the attribute value do NOT match the [[pattern]].
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class PhoneValidator extends Validator
{
    /**
     * @var string the regular expression to be matched with
     */
    //public $pattern='/^[234567]\d{4,8}$/';
     //public $pattern='/^[01234567]\d{9,10}$/';
     public $pattern='/^\+91\d{9,12}$/';
    /**
     * @var boolean whether to invert the validation logic. Defaults to false. If set to true,
     * the regular expression defined via [[pattern]] should NOT match the attribute value.
     */
    public $not = false;


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->pattern === null) {
            throw new InvalidConfigException('The "pattern" property must be set.');
        }
        if ($this->message === null) {
            $this->message = Yii::t('yii', '{attribute} is invalid. Only number, Length must be between 9 to 12, Should start with +91.');
        }
    }

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        
        
        //Yii::info("phone validate :".print_r($value,true));
         if(is_array($value))
         {
             $value=$value[0];
         }
         //Yii::info("phone validate aft:".print_r($value,true));
         if(!empty($value))
         {
             $valid = !is_array($value) &&
            (!$this->not && preg_match($this->pattern, $value)
            || $this->not && !preg_match($this->pattern, $value));
          // Yii::info("in phone validate aft:");
           
        return $valid ? null : [$this->message, []];
           
         }
         else
         {
              //Yii::info("in phone validate data:");
             return null;
             
         }
             
        
    }

    /**
     * @inheritdoc
     */
    public function clientValidateAttribute($object, $attribute, $view)
    {
        $pattern = $this->pattern;
        $pattern = preg_replace('/\\\\x\{?([0-9a-fA-F]+)\}?/', '\u$1', $pattern);
        $deliminator = substr($pattern, 0, 1);
        $pos = strrpos($pattern, $deliminator, 1);
        $flag = substr($pattern, $pos + 1);
        if ($deliminator !== '/') {
            $pattern = '/' . str_replace('/', '\\/', substr($pattern, 1, $pos - 1)) . '/';
        } else {
            $pattern = substr($pattern, 0, $pos + 1);
        }
        if (!empty($flag)) {
            $pattern .= preg_replace('/[^igm]/', '', $flag);
        }

        $options = [
            'pattern' => new JsExpression($pattern),
            'not' => $this->not,
            'message' => Yii::$app->getI18n()->format($this->message, [
                'attribute' => $object->getAttributeLabel($attribute),
            ], Yii::$app->language),
        ];
        if ($this->skipOnEmpty) {
            $options['skipOnEmpty'] = 1;
        }

        ValidationAsset::register($view);

        return 'yii.validation.regularExpression(value, messages, ' . Json::encode($options) . ');';
    }
}
