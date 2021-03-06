<?php

namespace app\controllers;

use app\models\CurrencyArchive;
use app\models\HourProfit;
use app\models\OwnedMinners;
use app\models\User;
use app\models\UsersBalances;
use Symfony\Component\CssSelector\Parser\Handler\HashHandler;
use Yii;
use app\models\Minner;
use app\models\Currency;
use app\models\Transactions;
use yii\data\ActiveDataProvider;

class DashboardController extends \yii\web\Controller
{
    public $layout = 'dashboard';
    public function beforeAction($action)
    {
//        Yii::$app->controller->enableCsrfValidation = false;
        $session = Yii::$app->session;
        $session->open();
        if ($session->has('language'))
        {
            $language =  $session->get('language');
            \Yii::$app->language = $language;
        }
        $session->close();

        if(!Yii::$app->user->isGuest && Yii::$app->user->identity->authorized == 0 || Yii::$app->user->isGuest)
        {
            return $this->redirect('/web/site/auth');
        }

        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }





    public function actionIndex()
    {
        $array = [];
        $keys = Minner::find()->select('currency')->distinct()->all();

        foreach ($keys AS $key)
        {
            $array[] = $key->currency;
        }

        $currency_minning = Currency::find()->where(['IN','key',$array])->asArray()->all();
        foreach ($currency_minning as $k => $cur_m)
        {
            $currency_minning[$k]['minners'] = Minner::find()->where(['currency' => $cur_m['key']])->all();
        }


        $currencies = [];
        foreach (Currency::find()->all() as $currency)
        {
            $currencies[$currency->key] = ['rate'=>$currency->rate,'rate_to_BTC'=>$currency->rate_to_BTC,'price'=>$currency->price,'algoritm'=>$currency->algoritm,'name'=>$currency->name];
        }

        return $this->render('index',['currency_minning'=>$currency_minning,'currencies'=>$currencies]);
    }

    public function actionPurchase()
    {

        $id = Yii::$app->user->getId();
        $balance = UsersBalances::find()->where(['user_id'=>$id])->all();


        $currencies = [];
        foreach (Currency::find()->all() as $currency)
        {
            $currencies[$currency->key] = ['rate'=>$currency->rate,'rate_to_BTC'=>$currency->rate_to_BTC,'price'=>$currency->price,'algoritm'=>$currency->algoritm,'name'=>$currency->name];
        }

        return $this->render('purchase',['balance' => $balance,'currencies' => $currencies]);
    }


    public function actionStatistic()
    {
        $id = Yii::$app->user->getId();
        $currencies = [];

//        $query = CurrencyArchive::find()->orderBy('date DESC')->where(['!=', 'key', 'USD']);
//        $provider = new ActiveDataProvider([
//            'query' => $query,
//            'pagination' => [
//                'pageSize' => 50000,
//            ],
//            'sort' => [
//                'defaultOrder' => [
//
//                ]
//            ],
//        ]);
//        $provider->getModels();exit;
//        echo var_dump($provider->getModels());
//        echo var_dump($provider->getTotalCount());
//        echo var_dump($provider->getCount());
//        exit;

//        echo var_dump(CurrencyArchive::find()->orderBy('date DESC')->where(['!=', 'key', 'USD'])->asArray()->all());exit;
        foreach(CurrencyArchive::find()->orderBy('date DESC')->where(['!=', 'key', 'USD'])->all() as $c=>$key)
        {
            $currencies[$key->key][$c] = ['price'=>$key->price,'date'=>$key->date];
        }
//        echo var_dump($currencies);exit;
        return $this->render('statistic',['data'=>$currencies]);
    }


    public function actionDayStatistic()
    {
        $id = Yii::$app->user->getId();
        $minnders_associations = OwnedMinners::find()->select('minner_id')->distinct()->where(['user_id' => $id])->all();
        $data = [];

        if(count($minnders_associations))
        {
            foreach($minnders_associations as $minner)
            {
                $data[$minner->minner_id]['minner_info'] = Minner::find()->where(['id'=>$minner->minner_id])->asArray()->one();
                for($i = 1; $i < 25; $i ++)
                {
                    if(!isset($data[$minner->minner_id][$i]))
                    {
                        $data[$minner->minner_id][$i]= [];
                    }

                    $data[$minner->minner_id][$i]['value'] = HourProfit::find()->select('SUM(profit) as prf')->where(['AND',['user_id'=>$id],['minner_id'=>$minner->minner_id],['hour_tic'=>$i]])->asArray()->one()['prf'];


                    if(is_null($data[$minner->minner_id][$i]['value']))
                    {
                        $data[$minner->minner_id][$i]['value'] = 0;
                    }
                }
            }
        }

        return $this->render('day',['data'=>$data]);
    }

    public function actionBuyPower()
    {
        if (Yii::$app->request->isAjax)
        {

            Yii::$app->response->format = 'json';
            $id = Yii::$app->user->getId();

            $amount = (double)Yii::$app->request->post('value1', '');
            $curr = (string)Yii::$app->request->post('currency2', '');
            $minner_id = (int)Yii::$app->request->post('minner_id', '');

            if (!Currency::isHaveCur($curr)) {
                return ['msg' => ['result' => 'Currency FAIL']];
            }

            $transaction = new Transactions();
            $transaction->amount1 = $amount;
            $transaction->currency2 = $curr;
            $transaction->user_id = $id;
            $transaction->minner_id = $minner_id;
            $transaction->buyer_name = Yii::$app->user->identity->username;
            $transaction->buyer_email = Yii::$app->user->identity->email;
            $answer = $transaction->createTransaction();
            if ($answer['result'] == 'ok') {
                $transaction->save();
                return ['msg' => $answer, 'transaction' => $transaction];
            } else {
                return ['msg' => $answer];
            }
        }

        $array = [];
        $keys = Minner::find()->select('currency')->distinct()->all();

        foreach ($keys AS $key)
        {
            $array[] = $key->currency;
        }

        $currency_minning = Currency::find()->where(['IN','key',$array])->asArray()->all();
        foreach ($currency_minning as $k => $cur_m)
        {
            $currency_minning[$k]['minners'] = Minner::find()->where(['currency' => $cur_m['key']])->all();
        }


        $currencies = [];
        foreach (Currency::find()->all() as $currency)
        {
            $currencies[$currency->key] = ['rate'=>$currency->rate,'rate_to_BTC'=>$currency->rate_to_BTC,'price'=>$currency->price,'algoritm'=>$currency->algoritm,'name'=>$currency->name];
        }

        return $this->render('buypower',['currency_minning'=>$currency_minning,'currencies'=>$currencies]);
    }

    public function actionTransactions()
    {
        $type = Yii::$app->request->get('type','all');

        $str_status = [0 => 'Pending', 1 => 'Success', 2 => 'On moderation' , 3 => 'In progress', 4 => 'Denied'];

        $result = Yii::$app->user->identity->getUserTransactions($type);

        return $this->render('transactions',['transactions' => $result, 'status' => $str_status]);
    }

    public function actionNewWithdraw()
    {
        Yii::$app->response->format = 'json';

        $post = Yii::$app->request->post();
        return Transactions::withdrawTransaction(Yii::$app->user->getId(),(string)$post['currency'],(float)$post['amount']);
    }

    public function actionSetWallet()
    {
        Yii::$app->response->format = 'json';

        $post = Yii::$app->request->post();

        return Yii::$app->user->identity->setWallet((string)$post['currency'],(string)$post['wallet']);
    }

    public function actionChangePassword()
    {
        Yii::$app->response->format = 'json';

        $post = Yii::$app->request->post();

        return Yii::$app->user->identity->changePassword((string)$post['password1'],(string)$post['password2']);
    }

    public function actionUpdateProfile()
    {
        Yii::$app->response->format = 'json';

        $post = Yii::$app->request->post();

        $u = User::find()->where(['id' => Yii::$app->user->getId()]);
        if ($u->count())
        {
            $u = $u->one();
            $u->name = $post['name'];
            $u->last_name = $post['last_name'];
            $u->address = $post['address'];
            $u->birthday = $post['birthday'];
            $u->city = $post['city'];
            $u->country_id = $post['country_id'];

            if($u->save())
            {
                return ['status'=> true];
            } else {
                return ['status'=> false, 'message' => '???? ?????????????? ??????????????????'];
            }
        }

        return ['status'=> false, 'message' => '???? ???????????????? ????????????'];;
    }
    public function actionProfile()
    {
        $array = [];
        $keys = Minner::find()->select('currency')->distinct()->all();

        foreach ($keys AS $key)
        {
            $array[] = $key->currency;
        }

        $id = Yii::$app->user->getId();

        $wallets = [];
        foreach (UsersBalances::find()->where(['user_id'=>$id])->all() as $wallet)
        {
            $wallets[$wallet->currency] = ['wallet'=>$wallet->wallet];
        }

        $u = User::find()->where(['id' => $id])->asArray()->one();
        return $this->render('profile',['wallets'=>$wallets,'keys'=>$array,'user'=>$u]);
    }

}
