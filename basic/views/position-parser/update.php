<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PositionParser */

$this->title = 'Update Position Parser: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Position Parsers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="position-parser-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
