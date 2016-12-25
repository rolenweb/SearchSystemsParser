<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "search_system".
 *
 * @property integer $id
 * @property string $title
 * @property integer $created_at
 * @property integer $updated_at
 */
class SearchSystem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'search_system';
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
            [['created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getSearchResults()
    {
        return $this->hasMany(SearchResult::className(), ['search_system_id' => 'id'])->inverseOf('searchSystem');
    }

    public static function dd()
    {
        return ArrayHelper::map(self::find()
            ->asArray()
            ->all(), 'id','title');
    }

    public static function getIdByTitle($title)
    {
        return ArrayHelper::getValue(self::find()->where(['like','title',$title])->limit(1)->one(), 'id');
    }

    public static function searchByTitle($title)
    {
        return self::find()->where(['like','title',$title])->limit(1)->one();
    }

    public function getPositionParser()
    {
        return $this->hasMany(PositionParser::className(), ['search_system_id' => 'id'])->inverseOf('searchSystem');
    }
}
