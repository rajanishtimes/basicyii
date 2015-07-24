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

  

    public static function WriteModelErrorLogging($message) {
        define('FPATH', dirname(dirname(__FILE__)) . '/');
        $filet = FPATH . 'log/writeModelErrorLog.txt';
        $lockText = date('Y-m-d H:i:s') . ' - ' . $message . "\n";
        file_put_contents($filet, $lockText, FILE_APPEND);
    }

    

}
