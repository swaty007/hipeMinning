<?php
if($auth == 'email') {
    $this->title = "Auth";
} else if ($auth == 'reg') {
    $this->title = "Need register";
}
use yii\helpers\Url;
?>


<?php if($auth == 'email'):?>
    Check your email for account actiavation
 <? elseif ($auth == 'reg'):?>
    At first you need to register <a href="<?= Url::to(['/site/register']); ?>">Register</a> or  <a href="<?= Url::to(['/site/login']); ?>">Login</a>
<?php endif; ?>

