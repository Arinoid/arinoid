<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 */
$this->title = 'Arinoid | Shorten URL';
?>
    <div class="col-md-6 col-md-offset-3">
        <form id="short_form" class="row">
            <div class="col-md-9">
                <label class="sr-only" for="link">URL</label>

                <input name="uri" type="text" class="form-control" autocomplete="off" id="uri"
                       placeholder="Enter link">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-default">Shorten URL</button>
            </div>
        </form>
    </div>

    <div class="col-md-6 col-md-offset-3" id="main-response">
        <div class="col-xs-12">
            <?php //TODO: add design, copy to clipboard, qr-code ?>
            <div class="row">
                <div class="col-xs-6">
                    <a href="" id="short_uri" target="_blank"></a>

                    <ul class="list-group list-unstyled">
                        <li class="list-group-item-text">
                            <a class="copyToClipboard" href=""><span class="glyphicon glyphicon-briefcase"></span>Copy
                                to
                                clipboard</a>
                        </li>
                        <li class="list-group-item-text">
                            <a id="sendByEmail" href=""><span class="glyphicon glyphicon-envelope"></span>Send by email</a>
                        </li>
                    </ul>

                </div>
                <div class="col-xs-6 text-right">
                    <img id="qrcode" src="" alt="Loading"/>
                </div>
            </div>
        </div>
    </div>


<?php if (Yii::$app->user->isGuest): ?>
    <div id="login-popup" class="background-color-6-cyan text-center">
        <div>
            Sign up and start right away!
        </div>
        <div id="login-popup-sn">
            <span id="login-popup-fb"></span><span id="login-popup-tw"></span>
        </div>
        <div id="login-popup-or"><span class="background-color-6-cyan">or</span></div>

        <div class="row">
            <div class="col-lg-12">
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'enableAjaxValidation' => true,
                    'fieldConfig' => [
                        'template' => '{input}{error}',
                    ],
                ]); ?>
                <?=
                $form->field($model, 'email', ['inputOptions' => [
                    'class' => 'form-control', 'placeholder' => 'Email'
                ]]) ?>
                <?=
                $form->field($model, 'password', ['inputOptions' => [
                    'class' => 'form-control', 'placeholder' => 'Password'
                ]])->passwordInput() ?>
                <div>
                <?= Html::submitButton('Login', ['class' => 'btn btn-default', 'name' => 'login-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <button id="login-popup-close" type="button" class="close">
            <span class="glyphicon glyphicon-remove"></span><span class="sr-only">Close</span>
        </button>
    </div>
<?php endif; ?>