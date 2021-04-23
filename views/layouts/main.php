<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
//use yii\bootstrap\Nav;
//use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <script>
        less = {
            //            relativeUrls: true,
            rootpath: "/web/"
        };
    </script>
    <link href="/web/css/less/main.less" rel="stylesheet/less" type="text/css">

</head>
<body>
<?php $this->beginBody() ?>

    <header class="header clearfix">
    <nav id="w0" class="navbar">
        <div class="container navbar-container">
            <div class="navbar-header">
                <button id="menu-btn" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#w0-collapse"><span
                            class="sr-only">Toggle navigation</span>
                    <span id="bar-1" class="icon-bar"></span>
                    <span id="bar-2" class="icon-bar"></span>
                    <span id="bar-3" class="icon-bar"></span></button>
                <a style="" class="navbar-brand"
                   href="<?= Url::to(['/site/index']); ?>"><?php echo Html::img('@web/images/logo.png', ['alt' => 'logo']); ?></a>
            </div>
            <div id="w0-collapse" class="collapse navbar-collapse">
                <ul id="w1" class="navbar-nav navbar-right nav">

                    <li class="<?php if (Yii::$app->controller->id == 'dashboard') echo 'active' ?>">
                        <a href="<?= Url::to(['/dashboard/index']); ?>">
                            <?= Yii::t('global', 'Дашбоард'); ?>
                        </a></li>

                    <li class="<?php if (Yii::$app->controller->action->id == 'index') echo 'active' ?>">
                        <a href="<?= Url::to(['/site/index']); ?>">
                            <?= Yii::t('global', 'Главная'); ?>
                        </a></li>
                    <li class="<?php if (Yii::$app->controller->action->id == 'about') echo 'active' ?>">
                        <a href="<?= Url::to(['/site/about']); ?>">
                            <?= Yii::t('global', 'О нас'); ?>
                        </a></li>
                    <li class="<?php if (Yii::$app->controller->action->id == 'contact') echo 'active' ?>">
                        <a href="<?= Url::to(['/site/contact']); ?>">
                            <?= Yii::t('global', 'Контакты'); ?>
                        </a></li>
                    <li class="divider-line"></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <?= Yii::t('global', 'Настройки'); ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="eng" href="<?= Url::to(['/site/language','lang'=>'en-US']); ?>">
                                    EN
                                </a>
                            </li>
                            <li>
                                <a class="uk" href="<?= Url::to(['/site/language','lang'=>'ru-RU']); ?>">
                                    RU
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php if(Yii::$app->user->isGuest):?>
                        <li class="<?php if (Yii::$app->controller->action->id == 'register') echo 'active' ?>">
                            <a href="<?= Url::to(['/site/register']); ?>">
                                <?= Yii::t('global', 'Регистрация'); ?>
                            </a></li>
                        <li class="<?php if (Yii::$app->controller->action->id == 'login') echo 'active' ?>">
                            <a href="<?= Url::to(['/site/login']); ?>">
                                <?= Yii::t('global', 'Логин'); ?>
                            </a>
                        </li>
                    <?php else:?>
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <?= Yii::$app->user->identity->username?> <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?= Url::to(['/dashboard/transactions'])?>">
                                        <?= Yii::t('global', 'История операций'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/dashboard/profile'])?>">
                                        <?= Yii::t('global', 'Профиль'); ?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="<?= Url::to(['site/logout'])?>" data-method="post">
                                        <?= Yii::t('global', 'Выйти'); ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif;?>

                </ul>
            </div>
        </div>
    </nav>

    </header>


<?//= Yii::$app->controller->action->id ?>
<?//= Yii::$app->controller->id ?>


        <?= $content ?>


<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="top clearfix">

                    <div class="pull-left">
                        <a class="logo" href="<?= Url::to(['/site/index']); ?>"><?php echo Html::img('@web/images/logo.png', ['alt' => 'logo_mono']); ?></a>
                    </div>
                    <div class="pull-right">
                        <div class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="">
                                Language
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a  href="">
                                        English
                                    </a>
                                </li>
                                <li>
                                    <a href="">
                                        Russian
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="diviner-line"></div>

        <div class="row">
            <div class="col-md-2 col-sm-4">
                <ul class="nav nav-pills nav-stacked nav-block">
                    <li class="title">
                        <h3>Information</h3>
                    </li>
                    <li>
                        <a href="">Contacts</a>
                    </li>
                    <li>
                        <a href="">News</a>
                    </li>
                    <li>
                        <a href="">About Us</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-2 col-sm-4">
                <ul class="nav nav-pills nav-stacked nav-block">
                    <li class="title">
                        <h3>Legal Documents</h3>
                    </li>
                    <li>
                        <a href="">Terms & Conditions</a>
                    </li>
                    <li>
                        <a href="">AML</a>
                    </li>
                    <li>
                        <a href="">Privacy Policy</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-2 col-sm-4">
                <ul class="nav nav-pills nav-stacked nav-block">
                    <li class="title">
                        <h3>Support</h3>
                    </li>
                    <li>
                        <a href="">Customer Support</a>
                    </li>
                    <li>
                        <a href="">Fees & Limits</a>
                    </li>
                    <li>
                        <a href="">TSMwiki</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="diviner-line"></div>

        <div class="row">
            <div class="col-md-12">
                <p class="info pull-left">
                    Copyright © Token Stock Market  LLP 2018, 49 Station Road, Polegate, England
                </p>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
