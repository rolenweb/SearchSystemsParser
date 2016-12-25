<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ThemeKeyword */

$this->title = 'Create Theme Keyword';
$this->params['breadcrumbs'][] = ['label' => 'Theme Keywords', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="theme-keyword-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
