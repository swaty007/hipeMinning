<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users_balances".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $currency
 * @property string $value
 * @property string $wallet
 */
class UsersBalances extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_balances';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'currency'], 'required'],
            [['user_id'], 'integer'],
            [['value'], 'number'],
            [['currency'], 'string', 'max' => 50],
            [['wallet'], 'string', 'max' => 255],
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
            'currency' => 'Currency',
            'value' => 'Value',
            'wallet' => 'Wallet',
        ];
    }
}
