<?php

namespace common\component;

use Yii;
use yii\db\ActiveRecord;
use yii\base\Event;
use yii\base\ErrorException;
use yii\db\BaseActiveRecord;
use yii\log\FileTarget;
use common\helpers\UtilityHelper;
//Models namespaces
use common\models\Content;
use common\models\ContentMeta;
use common\models\ContentSolr;
use api\models\EventApi;
use common\models\EventSolr;
//use common\models\TagSolr;
//use common\models\EventVenueSolr;
use common\models\EventMeta;
use api\models\VenueApi;
use common\models\VenueMeta;
use common\models\VenueSolr;
use common\models\VenueSolrDump;
use common\models\Event as EventBase;
use common\models\EventSeo;
use common\models\Venue as VenueBase;
use common\models\VenueSeo;
use common\models\Content as ContentBase;
use common\models\ContentSeo;
use common\models\SpecialPage;
use common\models\SpecialPageSeo;
use common\models\Question;
use common\models\QuestionEntity;
use common\models\EntityType;
use common\models\SeoTemplate;
use common\models\CriticsUserSeo;
use common\models\CriticsReviewsSeo;
use common\models\ReviewSolrDump;
use common\component\WhAkamai;

class TcEventAction {

    public static function EventActionAfterTrancInsert($event) {
        $data = array();
        $modelClass = get_class($event->sender);
        $data_attribute = $event->sender->getAttributes();
        //$data_toarray= $event->sender->toArray();
        //$data=self::ToArrayAttributeDataMerger($data_toarray,$data_attribute);
        try {
            switch ($modelClass) {
                case 'api\models\ContentApi':
                case 'common\models\Content':
                    $model = ContentMeta::findOne($data_attribute['id']);
                    if ($model) {
                        $model->updateMeta();
                        ContentSeo::singleUpdate($model->id);
                    }
                    break;

                case 'common\models\Event':
                case 'api\models\EventApi':
                    $model = EventMeta::findOne($data_attribute['id']);
                    if ($model) {
                        $model->updateMeta();
                        EventSeo::singleUpdate($model->id);
                    }
                    break;

                case 'common\models\Venue':
                case 'api\models\VenueApi':
                    $model = VenueMeta::findOne($data_attribute['id']);
                    if ($model) {
                        $model->updateMeta();
                        VenueSeo::singleUpdate($model->id);
                    }
                    break;

                case 'common\models\CriticsUser':
                    CriticsUserSeo::singleUpdate($data_attribute['id']);
                    $model = \common\models\CriticsUser::findOne(['id' => $data_attribute['id']]);
                    if ($model) {
                        \common\models\AuthorSolrDump::updateDelta($model->toArray());
                        $model->afterTrancInsertDo();
                    }
                    break;
                case 'common\models\CriticsReviews':
                    CriticsReviewsSeo::singleUpdate($data_attribute['id']);
                    $model = \common\models\CriticsReviews::findOne($data_attribute['id']);
                    if ($model) {
                        \common\models\ReviewSolrDump::updateDelta($model);
                        \common\models\ReviewSolrDump::updateRelation($model->entity_id);
                    }
                    break;

                case 'common\models\SeoTemplate':
                    $model = SeoTemplate::findOne($data_attribute['id']);
                    if ($model) {
                        $model->updateSeoMeta();
                    }
                    break;

                case 'common\models\SpecialPage':
                    SpecialPageSeo::singleUpdate($data_attribute['id']);
                    break;
                default:
            }
        } catch (\Exception $e) {
            $message = 'Error in ' . $modelClass . ' ' . $data['Id'] . ' ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine();
            $lockText = date('Y-m-d H:i:s') . ' - ' . $message . "\n";
            Yii::error($lockText, 'writemodel');
        }
    }

    public static function EventActionAfterTrancUpdate($event) {
        $data = array();
        $modelClass = get_class($event->sender);
        $data_attribute = $event->sender->getAttributes();
        //$data_toarray= $event->sender->toArray();
        //$data=self::ToArrayAttributeDataMerger($data_toarray,$data_attribute);
        try {
            switch ($modelClass) {
                case 'api\models\ContentApi':
                case 'common\models\Content':
                    $model = ContentMeta::findOne($data_attribute['id']);
                    if ($model) {
                        $model->updateMeta();
                        ContentSeo::singleUpdate($model->id);
                    }
                    break;

                case 'common\models\Event':
                case 'api\models\EventApi':
                    $model = EventMeta::findOne($data_attribute['id']);
                    if ($model) {
                        $model->updateMeta();
                        EventSeo::singleUpdate($model->id);
                    }
                    break;
                case 'common\models\Venue':
                case 'api\models\VenueApi':
                    $model = VenueMeta::findOne($data_attribute['id']);
                    if ($model) {
                        $model->updateMeta();
                        VenueSeo::singleUpdate($model->id);
                    }
                    break;

                case 'common\models\CriticsUser':
                    $model = \common\models\CriticsUser::findOne($data_attribute['id']);
                    if ($model) {
                        \common\models\AuthorSolrDump::updateDelta($model->toArray());
                        CriticsUserSeo::singleUpdate($data_attribute['id']);
                        $model->afterTrancUpdateDo();
                    }
                    break;
                case 'common\models\CriticsReviews':
                    $model = \common\models\CriticsReviews::findOne($data_attribute['id']);
                    if ($model) {
                        \common\models\ReviewSolrDump::updateDelta($model);
                        \common\models\ReviewSolrDump::updateRelation($model->entity_id);
                        CriticsReviewsSeo::singleUpdate($data_attribute['id']);
                    }
                    break;

                case 'common\models\SeoTemplate':
                    $model = SeoTemplate::findOne($data_attribute['id']);
                    if ($model) {
                        $model->updateSeoMeta();
                    }
                    break;
                case 'common\models\SpecialPage':
                    SpecialPageSeo::singleUpdate($data_attribute['id']);
                    break;
                case 'common\models\Constants':
                    \common\models\Constants::burstCache();
                    break;
                case 'common\models\City':
                    \common\models\City::burstCache();
                    break;
                default:
            }
        } catch (\Exception $e) {
            $message = 'Error in ' . $modelClass . ' ' . $data['Id'] . ' ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine();
            $lockText = date('Y-m-d H:i:s') . ' - ' . $message . "\n";
            Yii::error($lockText, 'writemodel');
        }
    }

    public static function EventActionAfterPublish($event) {
        $data = array();
        $modelClass = get_class($event->sender);
        $data_attribute = $event->sender->getAttributes();
        try {
            switch ($modelClass) {
                case 'common\models\Content':
                    $model = Content::findOne($data_attribute['id']);
                    if ($model) {
                        ContentSolr::updateDelta($model);
                        $model->afterPublishDo();
                        Yii::info('Content with id' . $data_attribute['id'] . ' has been published and solr delta update', 'evene');
                    }
                    break;

                case 'common\models\Event':
                    $model = EventBase::findOne($data_attribute['id']);
                    if ($model) {
                        Yii::info('event delta going to update', 'evene');
                        EventSolr::updateDelta($model);
                        Yii::info('event relation delta going to update', 'evene');
                        EventSolr::updateRelation($model);
                        $model->afterPublishDo();
                        Yii::info('Event with id' . $data_attribute['id'] . ' has been published and solr delta update', 'evene');
                    }
                    break;
                case 'common\models\Venue':
                    $model = VenueBase::findOne($data_attribute['id']);
                    if ($model) {
                        Yii::info('venue delta going to update', 'evene');
                        VenueSolrDump::updateDelta($model);
                        Yii::info('review going to update', 'evene');
                        VenueSolrDump::updateRelation($model);
                        $model->afterPublishDo();
                        Yii::info('Venue with id ' . $data_attribute['id'] . ' has been published and solr delta update', 'evene');
                    }                 
                    break;
                default:
            }
        } catch (\Exception $e) {
            $message = 'Error in ' . $modelClass . ' ' . $data['id'] . ' ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine();
            $lockText = date('Y-m-d H:i:s') . ' - ' . $message . "\n";
            Yii::error($lockText, 'writemodel');
        }
    }

    public static function EventActionCreate($event) {

        $data = array();
        $modelClass = get_class($event->sender);
        $data_attribute = $event->sender->getAttributes();
        //$data_toarray= $event->sender->toArray();
        // $data=self::ToArrayAttributeDataMerger($data_toarray,$data_attribute);
        try {
            switch ($modelClass) {
                case 'common\models\Venue':
                    break;
                case "common\models\Event":
                    break;
                default:
            }
        } catch (\Exception $e) {
            $message = 'Error in ' . $modelClass . ' ' . $data['Id'] . ' ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine();
            $lockText = date('Y-m-d H:i:s') . ' - ' . $message . "\n";
            Yii::error($lockText, 'writemodel');
        }
    }

    public static function EventActionUpdate($event) {
        $data = array();
        $modelClass = get_class($event->sender);
        $data_attribute = $event->sender->getAttributes();
        //$data_toarray= $event->sender->toArray();
        //$data=self::ToArrayAttributeDataMerger($data_toarray,$data_attribute);
        try {
            switch ($modelClass) {
                case 'common\models\Venue':
                    break;
                case "common\models\Event":
                    break;
                default:
            }
        } catch (\Exception $e) {
            $message = 'Error in ' . $modelClass . ' ' . $data['Id'] . ' ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine();
            $lockText = date('Y-m-d H:i:s') . ' - ' . $message . "\n";
            Yii::error($lockText, 'writemodel');
        }
    }

    public static function EventActionBeforePublish($event) {
        $data = array();
        $modelClass = get_class($event->sender);
        $data_attribute = $event->sender->getAttributes();
        $return_value = false;
        try {
            switch ($modelClass) {
                case 'common\models\Content':
                    $model = ContentBase::findOne(['id' => $data_attribute['id']]);
                    if ($model) {
                        $event->isValid = $model->validateForPublish();
                    }
                    break;

                case 'common\models\Event':
                    $model = EventBase::findOne(['id' => $data_attribute['id']]);
                    if ($model) {
                        $event->isValid = $model->validateForPublish();
                    }
                    break;
                case 'common\models\Venue':
                    $model = VenueBase::findOne(['id' => $data_attribute['id']]);
                    if ($model) {
                        $event->isValid = $model->validateForPublish();
                    }
                    break;

                case 'common\models\Question':
                    $model = Question::findOne(['id' => $data_attribute['id']]);
                    if ($model) {
                        $event->isValid = $model->validateForPublish();
                    }
                    break;

                default:
            }
        } catch (\Exception $e) {
            $message = 'Error in ' . $modelClass . ' ' . $data['Id'] . ' ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine();
            $lockText = date('Y-m-d H:i:s') . ' - ' . $message . "\n";
            Yii::error($lockText, 'writemodel');
        }
        return $return_value;
    }

    public static function EventActionSoftDelete($event) {
        //echo get_class($event->sender) . ' is inserted';
        $data = array();
        $modelClass = get_class($event->sender);
        \yii::trace('event handler publish function :' . $modelClass . __METHOD__, 'event');
        $data_attribute = $event->sender->getAttributes();
        // $data_toarray= $event->sender->toArray();
        //$data=self::ToArrayAttributeDataMerger($data_toarray,$data_attribute);
        try {

            switch ($modelClass) {
                case 'api\models\ContentApi':
                case 'common\models\Content':
                    $model = Content::findOne($data_attribute['id']);
                    if ($model) {
                        ContentSolr::deleteContentSolr($model);
                        QuestionEntity::deleteAll(['entity_type' => EntityType::CONTENT_ENTITY_ID, 'entity_id' => $model->id]);
                    }
                    break;

                case 'common\models\Event':
                    $model = EventBase::findOne($data_attribute['id']);
                    if ($model) {
                        EventSolr::deleteEventSolr($model);
                        EventSolr::updateRelation($model);
                        QuestionEntity::deleteAll(['entity_type' => EntityType::EVENT_ENTITY_ID, 'entity_id' => $model->id]);
                    }
                    break;

                case 'common\models\Venue':
                    $model = VenueBase::findOne($data_attribute['id']);
                    if ($model) {
                        VenueSolrDump::deleteVenueSolr($model);
                        VenueSolr::deleteVenueSolr($model);
                    }
                    break;

                default:
            }
        } catch (\Exception $e) {
            $message = 'Error in publish Flow ' . $modelClass . ' ' . $data['Id'] . ' ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine();
            $lockText = date('Y-m-d H:i:s') . ' - ' . $message . "\n";
            Yii::error($lockText, 'writemodel');
            //self::WriteModelErrorLogging($message);
        }
    }

    public static function EventActionUnpublish($event) {
        //$data=array();
        $modelClass = get_class($event->sender);
        $data_attribute = $event->sender->getAttributes();
        //$data_toarray= $event->sender->toArray();
        //$data=self::ToArrayAttributeDataMerger($data_toarray,$data_attribute);


        try {

            switch ($modelClass) {
                case 'common\models\Content':
                    $model = Content::findOne($data_attribute['id']);
                    if ($model) {
                        ContentSolr::deleteContentSolr($model);
                    }
                    break;

                case 'common\models\Event':
                    $model = EventBase::findOne($data_attribute['id']);
                    if ($model) {
                        EventSolr::deleteEventSolr($model);
                        EventSolr::updateRelation($model);
                    }
                    break;
                case 'common\models\Venue':
                    $model = VenueBase::findOne($data_attribute['id']);
                    if ($model) {
                        VenueSolrDump::deleteVenueSolr($model);
                        VenueSolr::deleteVenueSolr($model);
                    }
                    break;
                default:
            }
        } catch (\Exception $e) {

            $message = 'Error in publish Flow ' . $modelClass . ' ' . $data['Id'] . ' ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine();
            $lockText = date('Y-m-d H:i:s') . ' - ' . $message . "\n";
            Yii::error($lockText, 'writemodel');
            //self::WriteModelErrorLogging($message);
        }
    }

    public static function WriteModelErrorLogging($message) {
        define('FPATH', dirname(dirname(__FILE__)) . '/');
        $filet = FPATH . 'log/writeModelErrorLog.txt';
        $lockText = date('Y-m-d H:i:s') . ' - ' . $message . "\n";
        file_put_contents($filet, $lockText, FILE_APPEND);
    }

    public static function ToArrayAttributeDataMerger($toArray, $attribute) {
        $toArray['createdBy'] = $attribute['createdBy'];
        $toArray['updatedBy'] = $attribute['updatedBy'];
        $toArray['updatedOn'] = date("Y-m-d H:i:s");
        $toArray['createdOn'] = date("Y-m-d H:i:s");
        $toArray['ip'] = $attribute['ip'];

        return $toArray;
    }

    public static function checkPublishStatus($modelClass) {
        $allowedClass = array('common\models\Venue', 'common\models\EventSave', 'common\models\Event', 'common\models\Restaurant', 'common\models\Movie', 'common\models\Theatre');
        if (in_array($modelClass, $allowedClass)) {
            return true;
        } else {
            return false;
        }
    }

}
