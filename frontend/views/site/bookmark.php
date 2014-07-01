<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\Bookmark $searchModel
 */

$this->title = 'Arinoid | Bookmarks';
?>

<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'uri_id',
        'user_id',
        'created_at',

        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>