<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\Uri $searchModel
 */

$this->title = 'Uris';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uri-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Uri', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'uri',
            'uri_id',
            'created_at',
            'public',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
