<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchResultSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Search Results';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="search-result-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            
            [
                'attribute'=>'keyword_id',
                'label' => 'Keyword',
                'content'=>function($data){
                    return $data->keyword->key;
                }
                
            ],
            [
                'attribute'=>'search_system_id',
                'label' => 'Search System',
                'content'=>function($data){
                    return $data->searchSystem->title;
                }
                
            ],
            'title:html',
            'description:html',
            'url:url',
            [
                'attribute'=>'created_at',
                'label' => 'Created',
                'content'=>function($data){
                    return date("d/m/Y",$data->created_at);
                }
                
            ],
            [
                'attribute'=>'updated_at',
                'label' => 'Updated',
                'content'=>function($data){
                    return date("d/m/Y",$data->updated_at);
                }
                
            ],

    
        ],
    ]); ?>
</div>
