<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tc_audit_trail".
 *
 * @property string $key
 * @property string $userid
 * @property string $entityid
 * @property string $entityType
 * @property string $logtype
 * @property string $comment
 */
class AuditTrail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%audit_trail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['key', 'userid', 'entityid'], 'integer'],
            [['comment'], 'string'],
            [['entityType', 'logtype'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'key' => Yii::t('app', 'Key'),
            'userid' => Yii::t('app', 'Userid'),
            'entityid' => Yii::t('app', 'Entityid'),
            'entityType' => Yii::t('app', 'Entity Type'),
            'logtype' => Yii::t('app', 'Logtype'),
            'comment' => Yii::t('app', 'Comment'),
        ];
    }
}
