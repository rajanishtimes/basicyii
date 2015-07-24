<?php

namespace common\sammaye;

use Yii;

/**
 * This is the model class for table "tc_purge_urls".
 *
 * @property integer $id
 * @property string $url
 * @property string $response
 * @property integer $status
 * @property string $createdOn
 */
class PurgeUrl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tc_purge_urls';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['response'], 'string'],
            [['status'], 'integer'],
            [['createdOn','response'], 'safe'],
            //[['url'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'response' => 'Response',
            'status' => 'Status',
            'createdOn' => 'Created On',
        ];
    }
}