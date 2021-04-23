<?php

namespace app\models;
use \yii\db\ActiveRecord;
use \yii\web\IdentityInterface;
use \Yii;

class User extends ActiveRecord implements IdentityInterface
{
    public $accessToken;


    /**
     * @inheritdoc
     *
     * @property string $username
     * @property string $email
     * @property string $password
     * @property string $authkey
     * @property integer $authorized
     * @property integer $id
     * @property integer $city
     * @property integer $country_id
     * @property string $authorized_key
     * @property string $name
     * @property string $last_name
     * @property string $address
     * @property string $birthday
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'authkey'], 'required'],
            [['authorized','country_id'],'number'],
            [['username', 'email', 'authorized_key','name','last_name','city'], 'string', 'max' => 100],
            [['address'], 'string', 'max' => 150],
            [['password', 'authkey'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'authkey' => 'Authkey',
            'authorized' => 'Authorized',
            'authorized_key' => 'Authorized Key',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return (self::find()->where(['id' => $id])->count()) ? new static(self::find()->where(['id' => $id])->one()) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $u = self::find()->where(['username' => $username]);

        if($u->count())
        {
            return $u->one();
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authkey;
    }

    public function setPassword($password)
    {
        $this->password = md5($password);
    }

    public function generateAuthKey()
    {
        $this->authkey = \Yii::$app->security->generateRandomString(32);
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authkey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

    public function getUserTransactions($type = 'all',$limits = '',$order = 'id DESC')
    {
        if(Yii::$app->user->isGuest)
        {
            return [];
        }

        $aq = Transactions::find()->where(['user_id' => Yii::$app->user->getId()])->limit((string)$limits)->orderBy($order);
        $aq2 = Transactions::find()->where(['user_id' => Yii::$app->user->getId()])->limit((string)$limits)->orderBy($order);

        switch ($type)
        {
            case 'all':
                $part1 = $aq2->select('amount1,amount2,date_start,date_last,status,user_id,id,currency1,currency2,type,minner_id')->andWhere(['<>','type','mining'])->all();
                $part2 = $aq->select('SUM(amount1) AS amount1,SUM(amount2) AS amount2,date_start,date_last,status,user_id,id,currency1,currency2,type,minner_id')->andWhere(['type' => 'mining'])->groupBy(['minner_id','date_last','currency2'])->all();
                return array_merge($part2,$part1);
                break;
            case 'deposit':
                return $aq->andWhere(['type' => 'deposit'])->all();
                break;
            case 'withdraw':
                return $aq->andWhere(['type' => 'withdraw'])->all();
                break;
            case 'mining':
                return $aq->select('SUM(amount1) AS amount1,SUM(amount2) AS amount2,date_start,date_last,status,user_id,id,currency1,currency2,type,minner_id')->andWhere(['type' => 'mining'])->groupBy(['minner_id','date_last','currency2'])->all();
                break;
            case 'referal':
                return $aq->andWhere(['type' => 'referal'])->all();
                break;
            default:
                return [];
                break;
        }
    }

    public function addBalance($c,$a)
    {
        if($a <= 0)
        {
            return false;
        }

        $b = UsersBalances::find()->where(['user_id' => $this->id])->andWhere(['currency' => $c]);

        if(!$b->count())
        {
            $b = new UsersBalances();
            $b->user_id = $this->id;
            $b->currency = $c;
            $b->value = $a;
        } else {
            $b = $b->one();
            $b->value+=$a;
        }

        if($b->save())
        {
            return true;
        } else {
            return false;
        }
    }

    public function getWallet($c)
    {
        $b = UsersBalances::find()->where(['user_id' => $this->id])->andWhere(['currency' => (string)$c]);

        if(!$b->count())
        {
            return '';
        }

        return is_string($b->one()->wallet) ? $b->one()->wallet : '';
    }

    public function setWallet($c,$w)
    {
        if($w == '')
        {
            return ['status' => false, 'message' => 'Кошелек не может быть пустым'];
        }

        $b = UsersBalances::find()->where(['user_id' => $this->id])->andWhere(['currency' => (string)$c]);

        if(!$b->count())
        {
            $b = new UsersBalances();
            $b->user_id = $this->id;
            $b->currency = $c;
            $b->value = 0;
        } else {
            $b = $b->one();
        }

        $b->wallet = (string)$w;

        if($b->save())
        {
            return ['status' => true];
        } else {
            return ['status' => false, 'message' => 'Не смогли сохранить изменения'];
        }
    }

    public function changePassword($p1,$p2)
    {
        $u = User::find()->where(['id' => (int)Yii::$app->user->getId()])->one();

        if ($p1 === $p2)
        {
            $u->setPassword($p1);
            if($u->validate() && $u->save())
            {
                return ['status' => true];
            } else {
                return  ['status' => false, 'message' => 'Не удалось сохранить'];
            }
        } else {
            return ['status' => false, 'message' => 'Пароли не одинаковые'];
        }
    }

    public function takeBalance($c,$a)
    {
        if($a <= 0)
        {
            return false;
        }

        $b = UsersBalances::find()->where(['user_id' => $this->id])->andWhere(['currency' => $c]);

        if(!$b->count())
        {
            return false;
        }

        $b = $b->one();

        if($b->value < $a)
        {
            return false;
        }

        $b->value-=$a;

        if($b->save())
        {
            return true;
        } else {
            return false;
        }
    }

    public function getBalance($c = null)
    {
        if($c == null)
        {
            $a = [];

            foreach(Currency::find()->all() AS $cur)
            {
                $hm = Minner::find()->where(['currency' => $cur->name]);

                if($hm->count())
                {
                    $a[$cur->name] = $this->getBalance($cur->name);
                }
            }

            return $a;
        } else {
            $b = UsersBalances::find()->where(['user_id' => $this->id])->andWhere(['currency' => $c]);

            if($b->count())
            {
                return $b->one()->value;
            } else {
                return 0;
            }
        }
    }
}
