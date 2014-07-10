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

<div id="bookmark-table">
    <div id="bookmark-element" class="bookmark-element">
        <div>
            <img class="bookmark-element-img" src="" width="144" height="81">
        </div>
        <div>
            <div class="bookmark-element-title">

            </div>
            <div class="grab-text"></div>
            <div>
                <time class="bookmark-element-date"></time>
            </div>
        </div>
        <div>
            <a href="" class="bookmark-element-link" target="_blank"></a>

            <div class="grab-text"></div>
        </div>
        <div>
            <span class="glyphicon glyphicon-qrcode"></span>
        </div>
    </div>
</div>