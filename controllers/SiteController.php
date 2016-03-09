<?php

namespace app\controllers;

use app\models\KeywordMedians;
use app\models\KeywordAnalyzer;
use app\models\KeywordCleaner;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class SiteController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $this->enableCsrfValidation = false;

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
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
     * @return array
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

    public function actionIndex()
    {
        $model = new KeywordCleaner();

        return $this->render('index', ['model' => $model]);
    }

    /**
     * @return mixed|string
     */
    public function actionKeyword()
    {
        if (Yii::$app->request->isAjax) {
            if (!empty(Yii::$app->request->post())) {
                $model = new KeywordCleaner();

                $postData = Yii::$app->request->post();

                $model->setPhrases($postData['phrases']);
                $model->setCount($postData['count']);
                $model->setLength($postData['length']);
                $model->setNegative($postData['negative']);

                $data = $model->getData();

                return $data;
            } else {
                return $this->render('index');
            }
        } else {
            return $this->render('index');
        }

    }

    /**
     * @return mixed|string
     */
    public function actionAnalyzer()
    {
        if (Yii::$app->request->isAjax) {
            if (!empty(Yii::$app->request->post())) {
                $model = new KeywordAnalyzer();

                $postData = Yii::$app->request->post();

                $model->setSentences($postData['sentences']);
                $model->setKeywordWords($postData['keywordWords']);

                $data = $model->getData();

                return $data;
            } else {
                return $this->render('index');
            }
        } else {
            return $this->render('index');
        }

    }

    /**
     * @return string
     */
    public function actionMedians()
    {
        if (Yii::$app->request->isAjax) {
            if (!empty(Yii::$app->request->post())) {
                $model = new KeywordMedians();

                $postData = Yii::$app->request->post();

                $model->setOurArticle($postData['ourArticle']);
                $model->setKeywordMedian($postData['keywordMedian']);
                $model->setArticles($postData['articles']);

                $data = $model->getData();

                return $data;
            } else {
                return $this->render('index');
            }
        } else {
            return $this->render('index');
        }

    }
}
