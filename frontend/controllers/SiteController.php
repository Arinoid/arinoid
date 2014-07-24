<?php
namespace frontend\controllers;

use common\models\User;
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
use yii\helpers\ArrayHelper;
use yii\helpers\BaseUrl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\validators\UrlValidator;
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
use Symfony\Component\Process\Process;

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
        $uri = rtrim($uri, '/');

        $uriId = NULL;

        $error = 200;
        if ($uri != '') {
            if (strpos($uri, 'http') === false) {
                $uri = 'http://' . $uri;
            }

            $error = 400;
            if ($this->isValidUrl($uri)) {
                $error = 0;

                $id = NULL;

                $exist = Uri::findOne(['uri' => $uri]);
                if ($exist == NULL) {
                    $uriId = Security::generateRandomKey(rand(4, 5));
                    $existId = Uri::findOne(['uri_id' => $uriId]);

                    $inc = 0;
                    while ($existId != NULL) {
                        $uriId = Security::generateRandomKey(rand(4, 5));
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
                    $this->saveBookmark($id, $uri);
                }
            }
        }

        $response = [
            'error' => $error,
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

            if ($model->email && $model->password) {
                User::create([
                    'username' => $model->email,
                    'email' => $model->email,
                    'password' => $model->password,
                ]);
            }

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
        $id = Yii::$app->request->get('uriId');

        $exist = Uri::findOne(['uri_id' => $id]);

        if ($exist) {
            $this->saveBookmark($exist->id, $exist->uri);

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
        return $this->render('bookmark');
    }

    public function actionGetBookmark()
    {
        $bookmarks = Bookmark::find()->orderBy('id desc')
            ->where('user_id=' . Yii::$app->user->getId())->all();

        $return = [];
        /** @var $bookmark Bookmark */
        foreach ($bookmarks as $bookmark) {
            $uri = Uri::findOne($bookmark->uri_id);

            $thumbnail = isset($bookmark->thumbnail) ? "thumbnail/{$uri->uri_id}.png" : NULL;

            $return[] = [
                'id' => $bookmark->id,
                'date' => date('Y-m-d H:i:s', $bookmark->created_at),
                'uri_id' => $uri->uri_id,
                'uri' => $uri->uri,
                'thumbnail' => $thumbnail,
                'title' => $bookmark->title,
                'description' => $bookmark->description,
                'site' => Url::base(true),
                'qrcode' => $uri->uri_id . '.png',
            ];
        }
        return Json::encode($return);
    }

    public function actionUpdateBookmark()
    {
        $id = Yii::$app->request->post('id');
        $description = Yii::$app->request->post('description');
        $remove = Yii::$app->request->post('remove');
        $refresh = Yii::$app->request->post('refresh');

        $attributes = [];

        $uri = Uri::findOne(['uri_id' => $id]);
        if ($uri) {
            $attributes['id'] = $uri->uri_id;

            $bookmark = Bookmark::findOne(['uri_id' => $uri->id, 'user_id' => Yii::$app->user->getId()]);
            if ($bookmark) {
                if ($description) {
                    $attributes['description'] = $description;
                }

                if ($refresh) {
                    $attributes['title'] = $this->getUriTitle($uri->uri);
                    $attributes['thumbnail'] = $this->getUriThumbnail($uri->uri);
                }

                if ($remove) {
                    $attributes['remove'] = true;

                    $bookmark->delete();
                } else {
                    $bookmark->setAttributes($attributes);

                    $bookmark->save();
                }
            }
        }

        if (ArrayHelper::getValue($attributes, 'thumbnail')) {
            $attributes['thumbnail'] = 'thumbnail/' . $uri->uri_id . '.png';
        }

        return Json::encode($attributes);
    }

    public function actionThumbnail()
    {
        $uriId = Yii::$app->request->get('uriId');

        $exist = Uri::findOne(['uri_id' => $uriId]);

        if ($exist) {
            $bookmark = Bookmark::findOne(['uri_id' => $exist->id, 'user_id' => Yii::$app->user->getId()]);

            if ($bookmark) {
                header("Content-type: image/png");

                echo $bookmark->thumbnail;
                Yii::$app->end();
            }
        }

        $this->redirect('/');
    }

    private function isValidUrl($url)
    {
        // First check: is the url just a domain name? (allow a slash at the end)
        $_domain_regex = "|^[A-Za-z0-9-]+([.][A-Za-z0-9-]+)*([.][A-Za-z]{2,})/?$|";
        if (preg_match($_domain_regex, $url)) {
            return true;
        }

        // Second: Check if it's a url with a scheme and all
        $_regex = '#^([a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))$#';
        if (preg_match($_regex, $url, $matches)) {
            // pull out the domain name, and make sure that the domain is valid.
            $_parts = parse_url($url);
            if (!in_array($_parts['scheme'], array('http', 'https'))) {
                return false;
            }

            // Check the domain using the regex, stops domains like "-example.com" passing through
            if (!preg_match($_domain_regex, $_parts['host'])) {
                return false;
            }

            // This domain looks pretty valid. Only way to check it now is to download it!
            return true;
        }

        return false;
    }

    private function saveBookmark($id, $uri)
    {
        $exist = Bookmark::findOne(['uri_id' => $id, 'user_id' => Yii::$app->user->getId()]);
        if ($exist == NULL) {
            $modelBookmark = new Bookmark();


            $modelBookmark->setAttributes([
                'uri_id' => $id,
                'user_id' => Yii::$app->user->getId(),
                'created_at' => time(),
                'title' => $this->getUriTitle($uri),
            ]);

            $modelBookmark->save();
        }
    }

    private function getUriTitle($uri)
    {
        $title = NULL;
        $str = @file_get_contents($uri);

        if (strlen($str) > 0) {
            preg_match('/<title>([^<]*)<[\/]title>/i', $str, $title);
            $title = trim(ArrayHelper::getValue($title, 1));
        }

        if (strlen($title) == 0) {
            $title = 'Title';
        }

        return $title;
    }

    private function getUriThumbnail($uri)
    {
        $thumbnail = null;

        $name = Security::generateRandomKey(rand(4, 5)) . '.png';

        $command = "wkhtmltoimage --width 1440 --height 810 {$uri} {$name} && convert {$name} -resize 10% {$name}";

        $process = new Process($command);

        $process->start();

        while ($process->isRunning()) {
            // waiting for process to finish
        }

        // Read the file
        $fp = fopen($name, 'r');
        $data = fread($fp, filesize($name));
        fclose($fp);
        unlink($name);

        return $data;
    }
}
