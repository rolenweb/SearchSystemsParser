<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "google_analysis".
 *
 * @property integer $id
 * @property integer $keyword_id
 * @property string $domain
 * @property string $url
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class GoogleAnalysis extends \yii\db\ActiveRecord
{

    public $countDomain;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'google_analysis';
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
            [['keyword_id', 'domain_id', 'status', 'created_at', 'updated_at','position'], 'integer'],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'keyword_id' => 'Keyword ID',
            'domain' => 'Domain',
            'url' => 'Url',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getDomain()
    {
        return $this->hasOne(Domain::className(), ['id' => 'domain_id']);
    }

    public function getKeyword()
    {
        return $this->hasOne(Keyword::className(), ['id' => 'keyword_id']);
    }
}
