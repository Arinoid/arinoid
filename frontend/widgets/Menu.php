<?php

namespace frontend\widgets;

use Yii;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Menu extends Widget
{
    public $items;

    private $url;

    public function init()
    {
        $action = Yii::$app->requestedAction->id;

        $this->url = $action;
        if ($action == 'index') {
            $this->url = '/';
        }

        parent::init();

        echo $this->renderPopup();
        echo $this->renderMenu();
    }

    private function renderList()
    {
        $lists = '';
        foreach ($this->items as $list) {
            $iconClass = ArrayHelper::getValue($list, 'icon');
            $spanOptions = ArrayHelper::getValue($list, 'options');
            Html::addCssClass($spanOptions, 'glyphicon glyphicon-' . $iconClass);
            $icon = Html::tag('span', '', $spanOptions);

            $label = ArrayHelper::getValue($list, 'label');
            $url = ArrayHelper::getValue($list, 'url');

            $options['class'] = $this->isItemActive($list);
            if (!$url) {
                $options['class'] = 'blank';
            }

            $item = Html::a($icon . $label, $url, $options);

            Html::addCssClass($itemOptions, 'list-group-item-text');
            $lists .= Html::tag('li', $item, $itemOptions);
        }

        return $lists;
    }

    private function renderMenu()
    {
        $avatar = Html::tag('div', '', ['id' => 'main-menu-avatar']);
        $ul = Html::tag('ul', $this->renderList(), ['class' => 'list-group list-unstyled']);

        return Html::tag('div', $avatar . $ul, ['id' => 'main-menu', 'class' => 'background-color-6-cyan']);
    }

    private function renderPopup()
    {
        $span = Html::tag('span', '', ['class' => 'glyphicon glyphicon-list']);

        return Html::tag('div', $span, ['id' => 'main-menu-popup', 'class' => 'background-color-6-cyan']);
    }

    private function isItemActive($item)
    {
        $active = NULL;
        if ($this->url == $item['url']) {
            $active = 'active';
        }

        return $active;
    }

}