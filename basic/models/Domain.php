<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "domain".
 *
 * @property integer $id
 * @property string $title
 * @property string $registrar
 * @property integer $creation_date
 * @property integer $created_at
 * @property integer $updated_at
 */
class Domain extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'domain';
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
            [['creation_date', 'created_at', 'updated_at'], 'integer'],
            [['title', 'registrar'], 'string', 'max' => 255],
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
            'registrar' => 'Registrar',
            'creation_date' => 'Creation Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getSearchResults()
    {
        return $this->hasMany(SearchResult::className(), ['domain_id' => 'id'])->inverseOf('domain');
    }

    public static function getIdByTitle($title)
    {
        $domain = self::find()->where(['like','title',$title])->limit(1)->one();
        if (empty($domain)) {
            $new = new self;
            $new->title = $title;
            if ($new->save()) {
                return $new->id;
            }
        }else{
            return ArrayHelper::getValue($domain, 'id');    
        }
        return;
    }
}
