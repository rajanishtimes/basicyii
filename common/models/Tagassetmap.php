<?php

namespace common\models;

use Yii;
use common\component\AppActiveRecord;

/**
 * This is the model class for table "tc_tagassetmap".
 *
 * @property string $Id
 * @property string $assetId
 * @property string $tagId
 * @property integer $createdBy
 * @property integer $updatedBy
 * @property string $createdOn
 * @property string $updatedOn
 * @property integer $status
 * @property string $ip
 *
 * @property Tags $tagId0
 * @property Asset $assetId0
 */
class Tagassetmap extends AppActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tagassetmap}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Id', 'assetId', 'tagId'], 'required'],
            [['Id', 'assetId', 'tagId', 'created_by', 'updated_by', 'status'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['ip'], 'string', 'max' => 15]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function fields() {
        return [
            'Id',
            'assetId',
            'tagId'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'assetId' => 'Asset Id',
            'tagId' => 'Tag Id',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'status' => 'Status',
            'ip' => 'Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagId()
    {
        return $this->hasOne(Tags::className(), ['Id' => 'tagId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssetId()
    {
        return $this->hasOne(Asset::className(), ['Id' => 'assetId']);
    }
}
