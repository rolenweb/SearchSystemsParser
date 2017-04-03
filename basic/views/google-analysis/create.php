<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GoogleAnalysis */

$this->title = 'Create Google Analysis';
$this->params['breadcrumbs'][] = ['label' => 'Google Analyses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="google-analysis-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
