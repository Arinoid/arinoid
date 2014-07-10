<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use frontend\widgets\Menu;

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
            echo Html::a('Logout', 'logout', ['data-method' => 'post']);
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
    <?=
    Menu::widget([
        'items' => [
            ['label' => 'Profile', 'url' => null, 'icon' => 'user'],
            ['label' => 'Shorten URL', 'url' => '/', 'icon' => 'link'],
            ['label' => 'Bookmarks', 'url' => 'bookmark', 'icon' => 'bookmark'],
            ['label' => 'Tags', 'url' => null, 'icon' => 'tags'],
            ['label' => 'Settings', 'url' => null, 'icon' => 'cog'],
        ]
    ])
    ?>
<?php endif; ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
