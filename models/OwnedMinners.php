<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "owned_minners".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $minner_id
 * @property string $amount
 * @property integer $hashrate
 * @property string $date_created
 *
 *
 * @property HourProfit[] $hourProfits
 * @property Minner $minner
 * @property User $user
 * @property ProfitArchive[] $profitArchives
 */
class OwnedMinners extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'owned_minners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'minner_id', 'hashrate'], 'integer'],
            [['amount'], 'number'],
            [['date_created'], 'safe'],
            [['minner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Minner::className(), 'targetAttribute' => ['minner_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'minner_id' => 'Minner ID',
            'amount' => 'Amount',
            'hashrate' => 'Hashrate',
            'date_created' => 'Date Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHourProfits()
    {
        return $this->hasMany(HourProfit::className(), ['owned_minner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMinner()
    {
        return $this->hasOne(Minner::className(), ['id' => 'minner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfitArchives()
    {
        return $this->hasMany(ProfitArchive::className(), ['owned_minner_id' => 'id']);
    }

    public static function CalculatePerHour()
    {
        $date = date('Y-m-d H',time()).':00:00';
        $prof = HourProfit::find()->orderBy('id DESC');
        if(!$prof->count())
        {
            $tik = 1;
        } else {
            $tik = $prof->one()->hour_tic + 1;
            if($prof->one()->last_update == $date)
            {
//                exit;
            }
        }

        foreach (OwnedMinners::find()->all() as $minner)
        {
            $currency = Minner::find()->where(['id'=> $minner->minner_id])->one()->currency;
            $rate = Currency::find()->where(['key'=> $currency])->one()->rate;

            $profit_h = $minner->hashrate*$rate*60;

            $hour_prof = new HourProfit();
            $hour_prof->user_id = $minner->user_id;
            $hour_prof->owned_minner_id = $minner->id;
            $hour_prof->profit = $profit_h;
            $hour_prof->hour_tic = $tik;
            $hour_prof->last_update = $date;
            $hour_prof->minner_id = $minner->minner_id;
            $hour_prof->save();
        }

        if($tik == 24)
        {
            foreach (OwnedMinners::find()->all() as $minner)
            {
                $day_prof = HourProfit::find()->select('SUM(profit) as sum')->where(['owned_minner_id'=>$minner->id])->one()->sum;

                $archive = new ProfitArchive();
                $archive->user_id = $minner->user_id;
                $archive->owned_minner_id = $minner->id;
                $archive->profit = $day_prof;
                $archive->save();

                $c = Minner::find()->where(['id' => $minner->minner_id])->one()->currency;

                $sum = $day_prof/(float)Currency::getPrice($c);

                $bal = UsersBalances::find()->where(['user_id' => $minner->user_id])->andWhere(['currency' => $c]);

                if(!$bal->count())
                {
                    $bal = new UsersBalances();
                    $bal->user_id = $minner->user_id;
                    $bal->currency = $c;
                    $bal->value = 0;
                } else {
                    $bal = $bal->one();
                }


                $bal->value+=$sum;

                if(!$bal->save())
                {
                    Yii::trace($bal->errors);
                }

                Transactions::miningTransaction($minner->minner_id,$minner->user_id,$c,$sum,$day_prof,$date);
            }

            HourProfit::deleteAll([]);
        }
        exit;
    }
}
