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
<body class="dashboard-wrap">
<?php $this->beginBody() ?>





<?//= Yii::$app->controller->action->id ?>
<?//= Yii::$app->controller->id ?>


<div class="container">
    <div class="row">


        <div class="col-md-3">
            <div class="menu-dashboard">

                    <div class="row">
                        <div class="col-md-12">


                            <div class="avatar-block">
                                <img src="https://scontent.fiev15-1.fna.fbcdn.net/v/t1.0-1/p160x160/15219463_1045697672225533_8714606605874238214_n.jpg?_nc_cat=0&oh=971cdbe7d1f6887b11f9b2d2686b4af0&oe=5BC8B14B"
                                        alt="">
                            </div>

                            <h3 class="name-text text-center">
                                <?= Yii::$app->user->identity->name." " ?>
                                <?= Yii::$app->user->identity->last_name ?>
                            </h3>
                            <p class="email-text text-center">
                                <?= Yii::$app->user->identity->email ?>
                            </p>


                            <ul class="nav nav-dashboard-menu">
                                <li>
                                    <a href="<?= Url::to(['/dashboard/index'])?>" class="<?php if (Yii::$app->controller->action->id == 'index') echo 'active' ?>">
                                        <i class="fa fa-ravelry"></i>
                                        <span class="menu-text">
                                            <?= Yii::t('global', 'Кабинет'); ?>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/dashboard/day-statistic'])?>" class="<?php if (Yii::$app->controller->action->id == 'day-statistic') echo 'active' ?>">
                                        <i class="fa fa-ravelry"></i>
                                        <span class="menu-text">
                                            <?= Yii::t('global', 'День статистик'); ?>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/dashboard/purchase'])?>" class="<?php if (Yii::$app->controller->action->id == 'purchase') echo 'active' ?>">
                                        <i class="fa fa-ravelry"></i>
                                        <span class="menu-text">
                                            <?= Yii::t('global', 'Баланс'); ?>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/dashboard/statistic'])?>" class="<?php if (Yii::$app->controller->action->id == 'statistic') echo 'active' ?>">
                                        <i class="fa fa-ravelry"></i>
                                        <span class="menu-text">
                                            <?= Yii::t('global', 'Статистика'); ?>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/dashboard/buy-power'])?>" class="<?php if (Yii::$app->controller->action->id == 'buy-power') echo 'active' ?>">
                                        <i class="fa fa-ravelry"></i>
                                        <span class="menu-text">
                                            <b><?= Yii::t('global', 'Покупка HASHRATE'); ?></b>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/dashboard/transactions'])?>" class="<?php if (Yii::$app->controller->action->id == 'transactions') echo 'active' ?>">
                                        <i class="fa fa-ravelry"></i>
                                        <span class="menu-text">
                                            <?= Yii::t('global', 'История операций'); ?>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/dashboard/profile'])?>" class="<?php if (Yii::$app->controller->action->id == 'profile') echo 'active' ?>">
                                        <i class="fa fa-ravelry"></i>
                                        <span class="menu-text">
                                            <?= Yii::t('global', 'Профиль'); ?>
                                        </span>
                                    </a>
                                </li>
                            </ul>


                    </div>
                </div>
            </div>


        </div>

        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <?= $content ?>
                </div>
            </div>
        </div>


    </div>
</div>






<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
