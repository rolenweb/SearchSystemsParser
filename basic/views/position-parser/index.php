<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PositionParserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Position Parsers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="position-parser-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="text-right">
        <?= Html::a('Create Position Parser', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
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
                    return (empty($data->keyword) === false) ? $data->keyword->key : 'no set';
                }
                
            ],
            [
                'attribute'=>'search_system_id',
                'label' => 'Search system',
                'content'=>function($data){
                    return $data->searchSystem->title;
                }
                
            ],
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
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
