<?php
namespace app\models;
use Yii;
use yii\base\Model;
use app\models\User;
/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password2;
    public $agree;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => 'app\models\User', 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password2', 'string', 'min' => 6],
            ['password2', 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords does not compare.'],
        ];
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        if ($this->password !== $this->password2) {
            return null;
        }
        $user = new User();

        $user->username = $this->username;
        $user->email = $this->email;
//        $user->balance = 0;
        $user->authorized = 0;
        $user->authorized_key = Yii::$app->security->generateRandomString(64);

        $user->setPassword($this->password);
        $user->generateAuthKey();

        if($user->validate() && $user->save())
        {
            Yii::$app->user->login($user,3600*24);


            $to = $user->email;
            $subject = "Активация аккаунта";
            $message = "
                        <html>
                        <head>
                         <title>Активация аккаунта</title>
                        </head>
                        <body>
                        <p><strong>Вы успешно зарегестрировались на ХАЙПЕ</strong></p>
                        <h2>Для авторизации вашего аккаунта перейдите по <a href='http://hipe.infinitum.tech/web/site/authorize?k=" . $user->authorized_key . "'>ссылке</a>.</h2>
                        </body>
                        </html>
                        ";
            $headers= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
            $headers .= "From: <someemail@hyip.info>\r\n";

            mail($to, $subject, $message, $headers);

            return $user;
        } else {
            return null;
        }
    }
}