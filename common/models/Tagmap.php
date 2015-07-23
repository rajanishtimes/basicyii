<?php

namespace common\models;

use Yii;
use common\component\AppActiveRecord;
/**
 * This is the model class for table "tc_tagmap".
 *
 * @property string $Id
 * @property string $entityId
 * @property integer $entityType
 * @property string $tagId
 * @property integer $createdBy
 * @property integer $updatedBy
 * @property string $createdOn
 * @property string $updatedOn
 * @property integer $status
 * @property string $ip
 */
class Tagmap extends AppActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tagmap}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entityId', 'entityType', 'tagId'], 'required'],
            [['Id', 'entityId', 'entityType', 'tagId', 'created_by', 'updated_by', 'status'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['ip'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'entityId' => 'Entity Id',
            'entityType' => 'Entity Type',
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
     * @inheritdoc
     */
    public function fields() {
        return [
            'Id',
            'entityId',
            'entityType',
            'tagId',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagName()
    {
        return $this->hasOne(Tags::className(), ['Id' => 'tagId']);
    }
}
