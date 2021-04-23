<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "minners".
 *
 * @property integer $id
 * @property string $name
 * @property string $currency
 * @property string $hashrate
 * @property string $cost
 * @property string $hardware
 */
class Minner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'minners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hashrate'], 'integer'],
            [['cost'], 'number'],
            [['name'], 'string', 'max' => 30],
            [['hardware'], 'string', 'max' => 50],
            [['currency'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'currency' => 'Currency',
            'hashrate' => 'Hashrate',
            'cost' => 'Cost',
            'hardware' => 'Hardware',
        ];
    }

    public function getPrice()
    {
        return $this->cost/($this->hashrate/1000);
    }

    public function getHash($price)
    {
        return (int)($price/($this->cost/($this->hashrate/1000)));
    }

    static public function getHashById($id,$price)
    {
        $m = Minner::find()->where(['id' => $id])->one();

        return (int)($price/($m->cost/($m->hashrate/1000)));
    }

    static public function getDisplayNameById($id)
    {
        $m = Minner::find()->where(['id' => $id]);

        if(!$m->count())
        {
            return '[LOST MINER, PLEASE CONTRACT ADMINISTRATOR]';
        }

        $m = $m->one();

        return $m->name.' '.$m->hardware;
    }
}
