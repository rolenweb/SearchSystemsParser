<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "webspider".
 *
 * @property integer $id
 * @property string $domain
 * @property string $url
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Webspider extends \yii\db\ActiveRecord
{

    const STATUS_WATING = 1;
    const STATUS_PARSED = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'webspider';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['domain', 'url'], 'string', 'max' => 255],
            [['domain','url'],'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'domain' => 'Domain',
            'url' => 'Url',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function parsed()
    {
        $this->status = self::STATUS_PARSED;
        $this->save();

    }
}
