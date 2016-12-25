<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SearchResult;

/**
 * SearchResultSearch represents the model behind the search form about `app\models\SearchResult`.
 */
class SearchResultSearch extends SearchResult
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'keyword_id', 'search_system_id', 'created_at', 'updated_at'], 'integer'],
            [['domain_id', 'title', 'description', 'url'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SearchResult::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'keyword_id' => $this->keyword_id,
            'search_system_id' => $this->search_system_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'domain_id', $this->domain_id])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}
