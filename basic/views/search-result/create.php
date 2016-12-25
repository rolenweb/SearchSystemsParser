<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SearchResult */

$this->title = 'Create Search Result';
$this->params['breadcrumbs'][] = ['label' => 'Search Results', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="search-result-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
