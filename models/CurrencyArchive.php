<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "currencies_archive".
 *
 * @property integer $id
 * @property string $key
 * @property string $price
 * @property string $date
 * @property double $rate
 */
class CurrencyArchive extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currencies_archive';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price', 'rate'], 'number'],
            [['date'], 'safe'],
            [['rate'], 'required'],
            [['key'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'price' => 'Price',
            'date' => 'Date',
            'rate' => 'Rate',
        ];
    }
}
