<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <div id="main-bookmark">
        <?php if (Yii::$app->user->isGuest) {
            echo Html::a('Login', '#', ['id' => 'login']);
        } else {
            echo Html::a('Logout', 'site/logout', ['data-method' => 'post']);
        }
        ?>
    </div>
    <div class="container">
        <?=
        Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<?php if (!Yii::$app->user->isGuest): ?>
    <div id="main-menu-popup" class="background-color-6-cyan">
        <span class="glyphicon glyphicon-list"></span>
    </div>
    <div id="main-menu" class="background-color-6-cyan">
        <div id="main-menu-avatar"></div>
        <ul class="list-group">
            <li class="list-group-item-text"><span class="glyphicon glyphicon-user"></span>Profile</li>
            <li class="list-group-item-text">
                <a href="/"><span class="glyphicon glyphicon-link"></span>Shorten URL</a>
            </li>
            <li class="list-group-item-text">
                <a href="/bookmark"><span class="glyphicon glyphicon-bookmark"></span>Bookmarks</a>
            </li>
            <li class="list-group-item-text"><span class="glyphicon glyphicon-tags"></span>Tags</li>
            <li class="list-group-item-text"><span class="glyphicon glyphicon-cog"></span>Settings</li>
        </ul>
    </div>
<?php endif; ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
