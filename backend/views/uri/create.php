<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Uri $model
 */

$this->title = 'Create Uri';
$this->params['breadcrumbs'][] = ['label' => 'Uris', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uri-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
