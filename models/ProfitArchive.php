<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profit_archive".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $owned_minner_id
 * @property string $profit
 * @property string $date_creater
 *
 * @property OwnedMinners $ownedMinner
 * @property User $user
 */
class ProfitArchive extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profit_archive';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'owned_minner_id'], 'integer'],
            [['profit'], 'number'],
            [['date_creater'], 'safe'],
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
            'date_creater' => 'Date Creater',
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
