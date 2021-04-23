<?php

namespace app\controllers;

use app\models\OwnedMinners;
use app\models\ProfitArchive;
use app\models\User;
use Yii;
use app\models\Minner;
use app\models\Currency;
use app\models\Transactions;

class TransactionsController extends \yii\web\Controller
{

    public function beforeAction($action)
    {
        Yii::$app->controller->enableCsrfValidation = false;
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function actionApiAnswer()
    {
        Yii::trace($_POST);
        $cp_merchant_id = 'e57b9cb3751973469dcf5ad64177784e';
        $cp_ipn_secret = 'MySuperPuperIPM';

        if (!isset($_POST['ipn_mode']) || $_POST['ipn_mode'] != 'hmac') {
            $this->errorAndDie('IPN Mode is not HMAC');
        }
        if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
            $this->errorAndDie('No HMAC signature sent.');
        }
        $request = file_get_contents('php://input');
        if ($request === FALSE || empty($request)) {
            $this->errorAndDie('Error reading POST data');
        }
        if (!isset($_POST['merchant']) || $_POST['merchant'] != trim($cp_merchant_id)) {
            $this->errorAndDie('No or incorrect Merchant ID passed');
        }
        $hmac = hash_hmac("sha512", $request, trim($cp_ipn_secret));
        if (!hash_equals($hmac, $_SERVER['HTTP_HMAC'])) {
            //if ($hmac != $_SERVER['HTTP_HMAC']) { <-- Use this if you are running a version of PHP below 5.6.0 without the hash_equals function
            $this->errorAndDie('HMAC signature does not match');
        }

        $txn_id = $_POST['txn_id'];  //The unique ID of the payment. Your IPN handler should be able to handle a txn_id composed of any combination of a-z, A-Z, 0-9, and - up to 128 characters long for future proofing.
        $item_name = $_POST['item_name']; //The name of the item that was purchased.
        $buyer_name = $_POST['buyer_name']; //The name of the buyer. Возможно єто поле использівать для сравнивания с ай ди
        $item_number = $_POST['item_number']; //This is a passthru variable for your own use. [visible to buyer]
        $amount1 = floatval($_POST['amount1']); //The total amount of the payment in your original currency/coin.
        $amount2 = floatval($_POST['amount2']); //The total amount of the payment in the buyer's selected coin.
        $currency1 = $_POST['currency1']; //The original currency/coin submitted in your button. Note: Make sure you check this, a malicious user could have changed it manually.
        $currency2 = $_POST['currency2']; //	The coin the buyer chose to pay with.
        $user_id = $_POST['invoice']; //	$id
        $minner_id = $_POST['custom']; //	$minner_id
        $status = intval($_POST['status']);
        $status_text = $_POST['status_text']; //A text string describing the status of the payment. (useful for displaying in order comments)

        Yii::trace("STATUS");
        Yii::trace($status);

        if ($status >= 100 || $status == 2) {
            // payment is complete or queued for nightly payout, success

            $txn = Transactions::find()->where(['txn_id' => $txn_id])->andWhere(['status' => 0]);
            if(!$txn->count()) {
                Yii::trace("E1");
                $this->errorAndDie('count = 0');
            }
            $txn = $txn->one();

            if ($currency1 != $txn->currency1 && $currency2 != $txn->currency2) {
                $this->errorAndDie('Original currency mismatch!');
                Yii::trace("E2");
            }
            if ($amount1 < $txn->amount1) {
                Yii::trace("E3");
                $txn->amount1 = $amount1;
            }
            if ($amount1 <= 0 || $amount2 <= 0) {
                Yii::trace("E4");
                $this->errorAndDie('Amount cant be zero!');
            }
            if($user_id != $txn->user_id)
            {
                Yii::trace("E5");
                $this->errorAndDie('User id not equaled!');
            }

            $txn->status = 1;

            if($txn->save())
            {
                $om = new OwnedMinners();
                $om->user_id = $user_id;
                $om->minner_id = $minner_id;
                $om->amount = $amount1;
                $om->hashrate = (int)Minner::find()->where(['id' => $om->minner_id])->one()->getHash($om->amount);

                if(!$om->save())
                {
                    Yii::trace("E7");
                    Yii::trace($om->errors);
                      $this->errorAndDie('Save error 1');
                }
                $u = User::find()->where(['id' => (int)$user_id])->one();

                if(isset($u->referer_id) && $u->referer_id > 0)
                {
                    Transactions::refererTransaction($u->referer_id,$user_id,$minner_id,$txn->amount1*0.05);

                    $r = User::find()->where(['id' => $u->referer_id])->one();

                    if(isset($r->referer_id) && $r->referer_id > 0)
                    {
                        Transactions::refererTransaction($r->referer_id,$user_id,$minner_id,$txn->amount1*0.025);
                    }
                }
            } else {
                Yii::trace("E6");
                Yii::trace($txn->errors);
                  $this->errorAndDie('Save error 2');
            }

        } else if ($status < 0) {
            //payment error, this is usually final but payments will sometimes be reopened if there was no exchange rate conversion or with seller consent
        } else {
            //payment is pending, you can optionally add a note to the order page
        }
        die('IPN OK');
    }
    
    private function errorAndDie($error_msg) {
            $cp_debug_email = 'swaty0007@gmail.com';
            if (!empty($cp_debug_email)) {
                $report = 'Error: '.$error_msg."\n\n";
                $report .= "POST Data\n\n";
                foreach ($_POST as $k => $v) {
                    $report .= "|$k| = |$v|\n";
                }
                mail($cp_debug_email, 'CoinPayments IPN Error', $report);
            }
        die('IPN Error: '.$error_msg);
    }
}
