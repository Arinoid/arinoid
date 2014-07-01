<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 */
$this->title = 'Arinoid | Shorten URL';
?>
<div class="col-md-6 col-md-offset-3">
    <form id="short_form" class="form-inline">
        <div class="form-group">
            <label class="sr-only" for="link">URL</label>

            <input name="uri" type="text" class="form-control" size="40" autocomplete="off" id="uri"
                   placeholder="Enter link">
        </div>
        <button type="submit" class="btn btn-default">Shorten URL</button>

    </form>
    <div id="main-response">
        <?php //TODO: add design, copy to clipboard, qr-code ?>
        <a href="" id="short_uri" target="_blank"></a>
        <img id="qrcode" src="" alt="Loading"/>
    </div>
</div>

<?php if (Yii::$app->user->isGuest): ?>
    <div id="login-popup" class="background-color-5-aquamarine">
        Sign up and start right away!
        <br>
        FB - TW
        <br>
        - or -
        <br>

        <div class="row">
            <div class="col-lg-12">
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'enableAjaxValidation' => true,
                ]); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-default', 'name' => 'login-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <button id="login-popup-close" type="button" class="close">
            <span>&times;</span><span class="sr-only">Close</span>
        </button>
    </div>
<?php endif; ?>

<img src="" alt=""/>