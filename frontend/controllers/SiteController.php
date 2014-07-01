<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use common\models\Uri;
use common\models\ScriptTest;
use common\models\Bookmark;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\helpers\BaseUrl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Security;
use yii\web\HttpException;
use yii\web\Request;
use yii\widgets\ActiveForm;
use yii\web\Response;
use PHPQRCode\QRcode;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'bookmark'],
                'rules' => [
                    [
                        'actions' => ['logout', 'bookmark'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionShort()
    {
        $uri = Yii::$app->request->post('uri');
        $uri = trim($uri);

        $uriId = NULL;

        if ($uri != '') {
            if (strpos($uri, 'http') === false) {
                $uri = 'http://' . $uri;
            }

            $exist = Uri::findOne(['uri' => $uri]);

            $id = NULL;

            if ($exist == NULL) {
                $uriId = Security::generateRandomKey(5);
                $existId = Uri::findOne(['uri_id' => $uriId]);

                $inc = 0;
                while ($existId != NULL) {
                    $uriId = Security::generateRandomKey(5);
                    $existId = Uri::findOne(['uri_id' => $uriId]);
                    $inc++;

                    if ($inc > 300) {
                        throw new HttpException(400, 'Bad request');
                    }
                }

                $currentTime = time();

                //TODO: check script repeating
                if ($inc > 0) {
                    $model = new ScriptTest();

                    $model->setAttributes([
                        'try' => $inc,
                        'created_at' => $currentTime,
                    ]);

                    $model->save();
                }

                $model = new Uri();

                $model->setAttributes([
                    'uri' => $uri,
                    'uri_id' => $uriId,
                    'created_at' => $currentTime,
                ]);

                $model->save();

                $id = $model->id;
            } else {
                $uriId = $exist->uri_id;

                $id = $exist->id;
            }

            if ($id && !Yii::$app->user->isGuest) {

                $exist = Bookmark::findOne(['uri_id' => $id, 'user_id' => Yii::$app->user->getId()]);

                if (!$exist) {
                    $modelBookmark = new Bookmark();

                    $modelBookmark->setAttributes([
                        'uri_id' => $id,
                        'user_id' => Yii::$app->user->getId(),
                        'created_at' => time(),
                    ]);

                    $modelBookmark->save();
                }
            }
        }

        $response = [
            'site' => Url::base(true),
            'uri' => $uriId,
            'qrcode' => $uriId . '.png',
        ];

        return Json::encode($response);
    }

    public function actionIndex()
    {
        $model = new LoginForm();

        if (Yii::$app->request->isAjax) {
            $model->load(Yii::$app->request->post());
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } elseif ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }

    public function actionRedirect()
    {
        $uriId = Yii::$app->request->get('uriId');

        $exist = Uri::findOne(['uri_id' => $uriId]);

        if ($exist) {
            $this->redirect($exist->uri);
        }

        return $this->render('index');
    }

    public function actionQrcode()
    {
        $uriId = Yii::$app->request->get('uriId');

        $exist = Uri::findOne(['uri_id' => $uriId]);

        if ($exist) {
            return QRcode::png(Url::base(true) . "/" . $exist->uri_id, false, 'L', 4, 3);
        }

        return false;
    }

    public function actionLogin()
    {
// TODO: refactor site/login
//        if (!\Yii::$app->user->isGuest) {
//            return $this->goHome();
//        }
//
//        $model = new LoginForm();
//        if ($model->load(Yii::$app->request->post()) && $model->login()) {
//            return $this->goBack();
//        } else {
//            return $this->render('login', [
//                'model' => $model,
//            ]);
//        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            $user = $model->signup();
            if ($user) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionBookmark()
    {
        $searchModel = new Bookmark();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('bookmark', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}
