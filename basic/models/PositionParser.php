<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

use app\models\Keyword;
/**
 * This is the model class for table "position_parser".
 *
 * @property integer $id
 * @property integer $keyword_id
 * @property integer $search_system_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class PositionParser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'position_parser';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keyword_id', 'search_system_id', 'created_at', 'updated_at'], 'integer'],
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
            'keyword_id' => 'Keyword ID',
            'search_system_id' => 'Search System ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getSearchSystem()
    {
        return $this->hasOne(SearchSystem::className(), ['id' => 'search_system_id']);
    }

    public function getKeyword()
    {
        return $this->hasOne(Keyword::className(), ['id' => 'keyword_id']);
    }

    public static function next($id)
    {
        $out = [];
        $position = self::findOne($id);
        $keyword = $position->keyword;

        $nextKeyword = Keyword::find()->where(
            [
                'and',
                    [
                        '>','id',$keyword->id
                    ],
                    [
                        'theme_keyword_id' => $keyword->theme_keyword_id
                    ]
            ]
        )->limit(1)->one();
        if (empty($nextKeyword)) {
            $out['error'][] = 'Keywords are finished';
            return;
        }

        $position->keyword_id = $nextKeyword->id;
        $position->save();
        return $out;
    }
}
