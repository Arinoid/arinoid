<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Uri $model
 */

$this->title = 'Update Uri: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Uris', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="uri-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
