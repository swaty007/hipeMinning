<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "currencies".
 *
 * @property string $key
 * @property string $name
 * @property double $difficult
 * @property double $price
 * @property double $rate
 * @property double $rate_to_BTC
 * @property double $reward
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currencies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'difficult', 'rate', 'reward'], 'required'],
            [['difficult', 'rate','price', 'reward', 'rate_to_BTC'], 'number'],
            [['key'], 'string', 'max' => 10],
            [['algoritm','name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'key' => 'Key',
            'name' => 'Name',
            'difficult' => 'Difficult',
            'price' => 'Price',
            'rate' => 'Rate',
            'rate_to_BTC' => 'Rate to BTC',
            'reward' => 'Reward',
            'algoritm' => 'Algoritm',
        ];
    }

    public static function updateValue()
    {
        $transaction = new Transactions();
        $rate_curl = $transaction->checkRate();

        $dataBTC = json_decode(file_get_contents('https://api.blockchain.info/stats'));
        $usdPriceBTC = $dataBTC->market_price_usd;
        $USDRate = floatval($rate_curl['USD']['rate_btc']);
        $USDPrice = $usdPriceBTC*$USDRate;
        $usdPriceBTC = number_format($usdPriceBTC/$USDPrice,10,'.','');

        foreach (Currency::find()->all() as $currency)
        {
            $currency->rate_to_BTC = floatval($rate_curl[$currency->key]['rate_btc']);
            $cur_price =  number_format($usdPriceBTC*$currency->rate_to_BTC,5,'.','');
            if ($cur_price != 0) {
                $currency->price = $cur_price;
            }
            switch ($currency->key)
            {
                case 'BTC':
                    $currency->difficult = $dataBTC->difficulty;
                    $currency->rate = ((60*$currency->reward*1000)/($currency->difficult*pow(2,32)))*$currency->price; //kH in min
//                N = (t*R*H)/(D*2^32)
                    break;
                case'ETH':
//                $data = file_get_contents('https://chainz.cryptoid.info/btc/api.dws?q=getdifficulty');
                    $data = file_get_contents('https://chainradar.com/api/v1/eth/status');
                    $data = json_decode($data);

                    $currency->difficult = $data->difficulty;
                    $currency->rate = (((1000*$currency->reward)/$currency->difficult)*1*60)*$currency->price; //kH in min
//                Reward = ((hashrate * block_reward) / current_difficulty) * (1 - pool_fee) * 3600
                    break;
                case'ZEC':
                    $data = file_get_contents('https://chainradar.com/api/v1/zec/status');
                    $data = json_decode($data);
                    $currency->difficult = $data->difficulty;

                    $currency->rate = ((($currency->reward*1000)/($currency->difficult*pow(2,13)))*1*60)*$currency->price; //kH in min
//                    Reward = ((hashrate * block_reward) / current_difficulty * 2^13) * (1 - pool_fee) * 3600
                    break;
                case'LTC':
                    $data = file_get_contents('https://chainz.cryptoid.info/ltc/api.dws?q=getdifficulty');
                    $data = json_decode($data);
                    $currency->difficult = $data;

                    $currency->rate = (((1000*$currency->reward)/($currency->difficult*pow(2,32)))*1*60)*$currency->price; //kH in min
//                    Reward = ((hashrate * block_reward) / (current_difficulty * 2^32)) * (1 - pool_fee) * 3600
                    break;
                case'DASH':
                    $data = file_get_contents('https://chainz.cryptoid.info/dash/api.dws?q=getdifficulty');
                    $data = json_decode($data);
                    $currency->difficult = $data;

                    $currency->rate = (((1000*$currency->reward)/($currency->difficult*pow(2,32)))*1*60)*$currency->price; //kH in min
//                    Reward = ((hashrate * block_reward) / current_difficulty) * (1 - pool_fee) * 3600
                    break;
                default:
                    break;
            }
            $currency->save();
            $data_archive = new CurrencyArchive();
            $data_archive->key = $currency->key;
            $data_archive->price = $currency->price;
            $data_archive->rate = $currency->rate;
            $data_archive->save();
        }
    }

    static public function getPrice($str)
    {
        return (float)Currency::find()->where(['key' => $str])->one()->price;
    }

    static public function isHaveCur($str)
    {
        return Currency::find()->where(['key' => $str])->count();
    }
}
