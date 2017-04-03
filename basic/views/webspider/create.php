<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Webspider */

$this->title = 'Create Webspider';
$this->params['breadcrumbs'][] = ['label' => 'Webspiders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="webspider-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
