<?php

namespace app\models;

use Yii;

use yii\behaviors\TimestampBehavior;

use app\models\Domain;
/**
 * This is the model class for table "search_result".
 *
 * @property integer $id
 * @property integer $keyword_id
 * @property integer $search_system_id
 * @property string $domain_id
 * @property string $title
 * @property string $description
 * @property string $url
 * @property integer $created_at
 * @property integer $updated_at
 */
class SearchResult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'search_result';
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
            [['keyword_id', 'search_system_id', 'domain_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'description', 'url'], 'string'],
            
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
            'domain_id' => 'Domain ID',
            'title' => 'Title',
            'description' => 'Description',
            'url' => 'Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getDomain()
    {
        return $this->hasOne(Domain::className(), ['id' => 'domain_id']);
    }

    public function getSearchSystem()
    {
        return $this->hasOne(SearchSystem::className(), ['id' => 'search_system_id']);
    }

    public function getKeyword()
    {
        return $this->hasOne(Keyword::className(), ['id' => 'keyword_id']);
    }

    

    public static function saveContent($content,$ssid,$keyid)
    {
        $out = [];
        
        foreach ($content as $item) {

            if (empty($item->title)) {
                $out['error'][] = 'The title is null';
            }
            if (empty($item->htmlSnippet)) {
                $out['error'][] = 'The description is null';
            }
            if (empty($item->link)) {
                $out['error'][] = 'The url is null';
            }

            if (empty($item->title) === false && empty($item->htmlSnippet) === false && empty($item->link) === false) {
                $parse_url = parse_url($item->link);
                if (empty($parse_url['host'])) {
                    $out['error'][] = 'The host is null';
                    
                }else{
                    $domainId = Domain::getIdByTitle($parse_url['host']);
                    if (empty($domainId)) {
                        $out['error'][] = 'The domain ID is null';
                    }else{
                        $new = new self;
                        $new->keyword_id = $keyid;
                        $new->search_system_id = $ssid;
                        $new->domain_id = $domainId;
                        $new->title = $item->title;
                        $new->description = $item->htmlSnippet;
                        $new->url = $item->link;
                        if ($new->save()) {
                            # code...
                        }else{
                            foreach ($new->getErrors() as $er) {
                                $out['error'][] = $er[0];
                            }
                        }
                    }
                }
            }
        }
        return $out;
    }
}
