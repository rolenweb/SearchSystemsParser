<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SearchSystem */

$this->title = 'Create Search System';
$this->params['breadcrumbs'][] = ['label' => 'Search Systems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="search-system-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
