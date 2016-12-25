<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\SearchSystem;

/* @var $this yii\web\View */
/* @var $model app\models\PositionParser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="position-parser-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'keyword_id')->textInput() ?>

    <?= $form->field($model, 'search_system_id')->dropDownList(SearchSystem::dd())->label('Search System') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
