<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ThemeKeyword */

$this->title = 'Update Theme Keyword: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Theme Keywords', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="theme-keyword-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
