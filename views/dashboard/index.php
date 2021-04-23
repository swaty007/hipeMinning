<?php
/* @var $this yii\web\View */


use yii\helpers\Html;

$this->title = 'index';
$this->params['breadcrumbs'][] = $this->title;
?>





    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">История Кошелька CoinPayments</h3>
                </div>
                <div class="panel-body">

                    <?php echo $this->render('_minners-buy-block',['currency_minning'=>$currency_minning,'currencies'=>$currencies]) ?>

                </div>
            </div>

        </div>
    </div>


