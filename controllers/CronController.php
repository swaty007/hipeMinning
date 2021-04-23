<?php

namespace app\controllers;

use app\models\Currency;
use app\models\HourProfit;
use app\models\OwnedMinners;
use app\models\Transactions;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;


class CronController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
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




    public function actionCurrency()
    {
        $date = date('Y-m-d H',time()).':00:00';
        $prof = HourProfit::find()->orderBy('id DESC');

        if(!$prof->count() && $prof->one()->last_update == $date)
        {
//            exit;
        }

        Currency::updateValue();
        OwnedMinners::CalculatePerHour();
    }

    public function actionTest()
    {
        OwnedMinners::CalculatePerHour();
    }

}
