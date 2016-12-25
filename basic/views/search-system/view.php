<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SearchSystem */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Search Systems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="search-system-view">

    <h3><?= Html::encode($this->title) ?></h3>

    

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
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

</div>
