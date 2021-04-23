<?php

namespace app\models;

use Yii;
use app\models\CoinPaymentsAPI;

/**
 * This is the model class for table "transactions".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $minner_id
 * @property integer $status
 * @property double $amount1
 * @property double $amount2
 * @property string $currency1
 * @property string $currency2
 * @property string $type
 * @property string $buyer_name
 * @property string $buyer_email
 * @property integer $txn_id
 * @property integer $confirms_needed
 * @property string $date_start
 * @property string $date_last
 */
class Transactions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transactions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'amount2', 'currency2'], 'required'],
            [['status', 'user_id', 'minner_id',  'confirms_needed'], 'integer'],
            [['amount1', 'amount2'], 'number'],
            [['date_start', 'date_last'], 'safe'],
            [['currency1', 'currency2'], 'string', 'max' => 20],
            [['buyer_name', 'buyer_email'], 'string', 'max' => 100],
            [['txn_id'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'minner_id' => 'Minner id',
            'amount1' => 'Amount1',
            'amount2' => 'Amount2',
            'type' => 'Type',
            'currency1' => 'Currency1',
            'currency2' => 'Currency2',
            'buyer_name' => 'Buyer Name',
            'buyer_email' => 'Buyer Email',
            'txn_id' => 'Txn ID',
            'confirms_needed' => 'Confirms Needed',
            'date_start' => 'Date Start',
            'date_last' => 'Date Last',
        ];
    }
    public function checkRate ()
    {
        $cps = new CoinPaymentsAPI();
        $result = $cps->GetRates();
        if ($result['error'] == 'ok') {
            return $result['result'];
//            print 'Number of currencies: '.count($result['result'])."\n";
//            foreach ($result['result'] as $coin => $rate) {
//                if (php_sapi_name() == 'cli') {
//                    print print_r($rate);
//                } else {
//                    print nl2br(print_r($rate, TRUE));
//                }
//            }
        } else {
            return 'Error: '.$result['error']."\n";
        }
    }
    public function createTransaction()
    {
        $cps = new CoinPaymentsAPI();
        $req = array(
            'amount' => $this->amount1,
            'currency1' => 'USD',
            'currency2' => $this->currency2,
            'address' => '', // leave blank send to follow your settings on the Coin Settings page
            'buyer_email' => $this->buyer_email, // leave blank send to follow your settings on the Coin Settings page
            'buyer_name' => $this->buyer_name, // leave blank send to follow your settings on the Coin Settings page
            'item_number' => '', // leave blank send to follow your settings on the Coin Settings page
            'custom' => $this->minner_id, // рандомное поле
            'invoice' => $this->user_id, //рандомное поле 2
            'item_name' => 'Test Item/Order Description',
            'ipn_url' => 'http://hipe.infinitum.tech/web/transactions/api-answer',
        );
        $result = $cps->CreateTransaction($req);
        $result['result']['txn_id'];
        $result['result']['address'];
        $result['result']['amount'];
        $result['result']['txn_id'];
        $result['result']['confirms_needed'];
        $result['result']['timeout'];
        $result['result']['status_url'];
        $result['result']['qrcode_url'];

        if ($result['error'] == 'ok') {
            $this->status = 0;
            $this->amount2 =  floatval($result['result']['amount']);
            $this->currency1 = 'USD';
            $this->txn_id = $result['result']['txn_id'];
            $this->confirms_needed = floatval($result['result']['confirms_needed']);
            return [
                'result' => 'ok',
                'status_url' => $result['result']['status_url'],
                'qrcode_url' => $result['result']['qrcode_url'],
                'address' => $result['result']['address']
                ];
        } else {
            return 'Error: '.$result['error']."\n";
        }
    }

    static public function miningTransaction($minner_id,$user_id,$currency,$count,$usd_count,$ds)
    {
        $t = new Transactions();

        $t->status = 1;
        $t->user_id = $user_id;
        $t->currency1 = 'USD';
        $t->minner_id = $minner_id;
        $t->currency2 = $currency;
        $t->amount1 = $usd_count;
        $t->amount2 = $count;
        $t->type = 'mining';
        $t->date_start = $ds;
        $t->date_last = $ds;

        if($t->save())
        {
            return true;
        } else {
            Yii::trace($t->errors);
            return false;
        }
    }
    static public function withdrawTransaction($user_id,$currency,$count)
    {
        $ub = User::find()->where(['id' => $user_id])->one()->getBalance($currency);

        if($ub < $count)
        {
            return ['status' => false, 'message' => 'Не хватает средств на счёте!'];
        }
        if(0 >= $count)
        {
            return ['status' => false, 'message' => 'Не может быть меньше минимальной выплаты'];
        }

        $t = new Transactions();

        $t->status = 2;
        $t->user_id = $user_id;
        $t->currency1 = 'USD';
        $t->minner_id = 0;
        $t->currency2 = $currency;
        $t->amount1 = $count*Currency::getPrice($currency);
        $t->amount2 = $count;
        $t->type = 'withdraw';

        if($t->save() && User::find()->where(['id' => $user_id])->one()->takeBalance($currency,$count))
        {
            return ['status' => true];
        } else {
            Yii::trace($t->errors);
            return ['status' => false, 'message' => 'Ошибка сервера'];
        }
    }

    static public function refererTransaction($referer_id,$referal_id,$minner_id,$count)
    {
        $t = new Transactions();

        $t->status = 1;
        $t->user_id = $referer_id;
        $t->currency1 = 'USD';
        $t->minner_id = $minner_id;
        $t->currency2 = 'USD';
        $t->amount1 = $count;
        $t->amount2 = $count;
        $t->txn_id = (string)$referal_id;
        $t->type = 'referal';

        if(User::find()->where(['id' => $referer_id])->one()->addBalance('USD',$count) && $t->save())
        {
            return true;
        } else {
            Yii::trace($t->errors);
            return false;
        }
    }
}
