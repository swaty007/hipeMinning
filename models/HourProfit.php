<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hour_profit".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $owned_minner_id
 * @property string $profit
 * @property string $last_update
 * @property integer $hour_tic
 * @property integer $minner_id
 * @property OwnedMinners $ownedMinner
 * @property User $user
 */
class HourProfit extends \yii\db\ActiveRecord
{
    public $sum;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hour_profit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'owned_minner_id', 'hour_tic'], 'integer'],
            [['profit'], 'number'],
            [['last_update'], 'safe'],
            [['hour_tic'], 'required'],
            [['owned_minner_id'], 'exist', 'skipOnError' => true, 'targetClass' => OwnedMinners::className(), 'targetAttribute' => ['owned_minner_id' => 'id']],
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
            'owned_minner_id' => 'Owned Minner ID',
            'profit' => 'Profit',
            'last_update' => 'Last Update',
            'hour_tic' => 'Hour Tic',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwnedMinner()
    {
        return $this->hasOne(OwnedMinners::className(), ['id' => 'owned_minner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}