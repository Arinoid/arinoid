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
            <img src="/img/defaultThumbnail.png" alt="thumbnail" class="bookmark-element-img" width="144" height="81">
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
            <ul class="list-group list-unstyled">
                <li class="list-group-item-text">
                    <a href="" class="bookmark-element-uri" target="_blank"></a>
                </li>
                <li class="list-group-item-text">
                    <a class="copyToClipboard" href=""><span class="glyphicon glyphicon-briefcase"></span>Copy to
                        clipboard</a>
                </li>
                <li class="list-group-item-text">
                    <a href="" class="toggleQrcode"><span class="glyphicon glyphicon-qrcode"></span>Show QR-code</a>

                    <div class="bookmark-element-qrcode-div">
                        <img src="#" class="bookmark-element-qrcode" alt="Loading">
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>