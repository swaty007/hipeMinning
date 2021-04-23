<?php
/* @var $this yii\web\View */


use yii\helpers\Html;

$this->title = 'Buy Power';
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="buy-power clearfix">

<!--    <pre>--><?php //var_dump($currency_minning)?><!--</pre>-->

                        <div class="col-md-12">

                            <h1 class="panel-title">Купить мощностей</h1>

                                <script>
                                    var rates = [];
                                    rates.page = 'buy-power';
                                    <?php foreach ($currencies as $l=>$cur):?>
                                    rates['<?=$l?>']=<?=$cur['rate_to_BTC'];?>;
                                    <?php endforeach;?>
                                    rates.btc_price = <?=$currencies['BTC']['price']; ?>;

                                    var minners_ids = [];
                                    <?foreach ($currency_minning as $jds=>$coin): ?>
                                    <?php foreach($coin['minners'] as $jd=>$minner):?>
                                    minners_ids.push(<?=$minner->id?>);
                                    <?php endforeach;?>
                                    <?php endforeach;?>

                                    document.addEventListener("DOMContentLoaded", function () {

                                        var input_usd = $('#calc_to_usd');
                                        var input_cur = $('#calc_to_cur');

                                        function buy_power_loop (value,inputCur) {
                                            var cur = $('#culture_main').val();

                                            if (inputCur === true) {
                                                input_usd.val(value.toFixed(2)+ ' $');
                                            } else {
                                                input_cur.val(check_rate(value,cur,rates)+ ' '+ cur);
                                            }

                                            minners_ids.forEach(function (i) {
                                                var id = i;
                                                changeKHandMath(value,id,rates);
                                            });
                                        }

                                        input_cur.on('input', function () {
                                            var cur = $('#culture_main').val();
                                            var value_cur = parseFloat(this.value);
                                            var value = check_rate_USD(value_cur,cur,rates);
                                            buy_power_loop(value,true);
                                        });
                                        input_cur.on('change',function () {
                                            var cur = $('#culture_main').val();
                                            var value_cur = parseFloat(this.value);
                                            var value = check_rate_USD(value_cur,cur,rates);
                                            value = Number(value.toFixed(2));
                                            buy_power_loop(value,true);
                                            this.value = check_rate(value,cur,rates)+ ' '+ cur;
                                        });

                                        input_usd.on('input', function () {
                                            var value = parseFloat(this.value);
                                            buy_power_loop(value);
                                        });
                                        input_usd.on('change',function () {
                                            var value = parseFloat(this.value).toFixed(2);
                                            this.value = value + " $";
                                            buy_power_loop(value);
                                        });

                                        $( "#culture_main" ).on( "change", function() {
                                            buy_power_loop(parseFloat(input_usd.val()));
                                        });

                                        buy_power_loop(parseFloat(input_usd.val()).toFixed(2));
                                    });
                                </script>


                            <div class="row">
                                <div class="col-md-12">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="main-label" for="culture_main">Выберете валюту в которой хотите произвести оплату:</label>
                                                <select class="main-select" id="culture_main">
                                                    <?php foreach ($currencies as $k=>$cur):?>
                                                        <option value="<?=$k?>" <?php if($k=='BCH') echo 'selected="selected"';?>>
                                                            <?=$cur['name'];?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="main-label mar-bot" for="culture_main">Сумма в (USD):</label>
                                                <input class="main-input" id="calc_to_usd"
                                                       value="<?php if( $minner->id == $_GET['num_item'] ) {echo $_GET['value'];} else echo '2.50'?> $">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="main-label mar-bot" for="calc_to_cur">Сумма в валюте:</label>
                                                <input class="main-input" id='calc_to_cur'>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>



                            <div class="row">
                                <?foreach ($currency_minning as $k=>$coin): ?>

                                        <?php foreach($coin['minners'] as $j=>$minner):?>
                                        <div class="col-md-6 col-lg-4">

                                            <div id="<?=$minner->id?>"
                                                 data-rate="<?=number_format($currencies[$minner->currency]['rate'],20,'.',''); ?>"
                                                 data-price_kh="<?=number_format($minner->getPrice(),20,'.','')?>"
                                                 class="buy-block-item">

                                                <div class="img-block">
                                                    <img src="/web/images/coins/<?=$coin['key'];?>.png" alt="">
                                                </div>

                                                <h2 class="buy-title-cur">
                                                    <?=$coin['name']?> (<?=$minner->name?>)
                                                </h2>

                                                <div class="hashrate-block">
                                                    <span id="buykH<?=$minner->id?>">100</span> <span id="HashMulti<?=$minner->id?>">KH/s</span>
                                                </div>

                                                <h3 class="buy-title-hardware">
                                                     <?=$minner['hardware']?>
                                                </h3>

                                                <hr class="separate">

                                                <p class="text-income-title">Ваш доход: </p>

                                                <p class="income-usd"><span id="income<?=$minner->id?>_hour">$ 0.8</span> / час</p>
                                                <p class="income-coin"><span id="coin_income<?=$minner->id?>_hour">$ 0.8</span></p>

                                                <p class="income-usd"><span id="income<?=$minner->id?>_day">$ 0.8</span> / день</p>
                                                <p class="income-coin"><span id="coin_income<?=$minner->id?>_day">$ 0.8</span></p>

                                                <p class="income-usd"><span id="income<?=$minner->id?>">$ 0.8</span> / месяц</p>
                                                <p class="income-coin"><span id="coin_income<?=$minner->id?>">$ 0.8</span></p>

                                                <p class="income-usd"><span id="income<?=$minner->id?>_all">$ 0.8</span> / 2 года</p>
                                                <p class="income-coin"><span id="coin_income<?=$minner->id?>_all">$ 0.8</span></p>

                                                <div class="exchange_button_wrap">
                                                    <button class='exchange_button' data-id="<?=$minner->id?>" onclick='ModalBuy.init(this)'>Запросить кошелёк</button>
                                                </div>
                                            </div>

                                        </div>
                                        <?php endforeach;?>

                                <?php endforeach;?>
                            </div>


                        </div>
</div>




