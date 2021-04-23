<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>




<?php //echo $this->render('_slider') ?>
<h2 class="text-center">Наши майнері</h2>

<?php if(!Yii::$app->user->isGuest){ echo $this->render('our-advantages',['minners'=>$minners,'currencies'=>$currencies]);} ?>








<?= $this->render('features'); ?>
