<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\Uri $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="uri-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uri')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'uri_id')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'public')->textInput() ?>

    <div class="form-group">
        <?=
        Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn
        btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
