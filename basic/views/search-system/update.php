<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SearchSystem */

$this->title = 'Update Search System: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Search Systems', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="search-system-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
