<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PositionParser */

$this->title = $model->keyword->key;
$this->params['breadcrumbs'][] = ['label' => 'Position Parsers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="position-parser-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p class="text-right">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'Keyword',
                'value' => $model->keyword->key,
            ],
            [
                'label' => 'Search System',
                'value' => $model->searchSystem->title,
            ],
            [
                'label' => 'Created',
                'value' => date("d/m/Y",$model->created_at),
            ],
            [
                'label' => 'Updated',
                'value' => date("d/m/Y",$model->updated_at),
            ],
        ],
    ]) ?>

</div>
