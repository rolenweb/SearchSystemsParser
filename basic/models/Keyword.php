<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "keyword".
 *
 * @property integer $id
 * @property string $key
 * @property integer $theme_keyword_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Keyword extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'keyword';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['theme_keyword_id', 'created_at', 'updated_at'], 'integer'],
            [['key'], 'string', 'max' => 255],
            [['key'], 'unique'],
        ];
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'theme_keyword_id' => 'Theme Keyword ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getTheme()
    {
        return $this->hasOne(ThemeKeyword::className(), ['id' => 'theme_keyword_id']);
    }

    public function getSearchResults()
    {
        return $this->hasMany(SearchResult::className(), ['keyword_id' => 'id'])->inverseOf('keyword');
    }
}
