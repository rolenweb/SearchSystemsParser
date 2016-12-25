<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PositionParser */

$this->title = 'Create Position Parser';
$this->params['breadcrumbs'][] = ['label' => 'Position Parsers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="position-parser-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
