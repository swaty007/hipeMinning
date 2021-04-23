<?php
/* @var $this yii\web\View */


use yii\helpers\Html;

$this->title = 'Purchase';
$this->params['breadcrumbs'][] = $this->title;
?>


    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">История Кошелька CoinPayments</h3>
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12">

                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-condensed balance-table">
                                    <thead>
                                    <tr>
                                        <th>КРИПТОВАЛЮТА</th>
                                        <th>БАЛАНС</th>
                                        <th>ЭКВИВАЛЕНТ В BTC</th>
                                        <th colspan="2">
                                            <select id="culture">
                                                <?php foreach ($currencies as $k => $cur): ?>
                                                    <option value="<?= $k ?>" <?php if ($k == 'USD') echo 'selected="selected"'; ?>>
                                                        <?= $cur['name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php foreach ($balance as $bal): ?>
                                        <tr onclick="check_value(<?= $bal->value ?>,'<?= $bal->currency ?>',this)" data-cur2="USD">
                                            <td>
                                                <img src="/web/images/coins/<?= $bal->currency ?>.png"
                                                     alt="<?= $bal->currency ?>" style="max-height: 24px;">
                                                <?= $currencies[$bal->currency]['name'] ?>
                                            </td>
                                            <td><?= $bal->value ?>  <?= $bal->currency ?></td>
                                            <td><?= number_format($bal->value * $currencies[$bal->currency]['rate_to_BTC'], 15, '.', ''); ?>
                                                BTC
                                            </td>
                                            <td class="btc"><?= number_format($bal->value * $currencies[$bal->currency]['price'], 15, '.', ''); ?>
                                                USD
                                            </td>
                                            <td>
                                                <a class="detailBtn" href="#">detailBtn</a>
                                            </td>
                                        </tr>

                                        <tr class="details" id="bal_<?=$bal->currency?>">
                                            <td colspan="5">
                                                <div class="details">
                                                    <div class="col-md-4">
                                                        <h2 class="info-title">Баланс  <?= $currencies[$bal->currency]['name'] ?></h2>
                                                        <p class="info-details"><?= $bal->currency ?> : <span><?= $bal->value ?></span></p>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <?php $var_value = number_format( $bal->value ,15, '.', '');?>
                                                        <div style="margin-top: 20px;" class="clearfix form-horizontal">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">Сумма на вывод</label>
                                                                <div class="col-md-5">
                                                                    <input class="spinner" value="<?=$var_value;?>">
                                                                </div>

                                                                <button class="btn btn-success col-md-4"
                                                                        <?php if($bal->wallet == null):?>
                                                                        data-toggle="tooltip" data-placement="top" title="Сперва нужно ввести кошелек" disabled
                                                                        <?php endif;?>
                                                                        onclick="getWithdraw('<?=$bal->currency?>',this,event)">запросить выплату</button>
                                                            </div>

                                                        <script>
                                                            document.addEventListener("DOMContentLoaded", function () {

                                                                var value_p = parseFloat('<?=$var_value?>');

                                                                var spinner = $("#bal_<?=$bal->currency?> input.spinner").spinner({
                                                                    min: 0,
                                                                    step:  (value_p/50),
                                                                    start: value_p,
                                                                    max: value_p
                                                                });
                                                            });
                                                        </script>


                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">Номер кошелька</label>
                                                                <div class="col-md-5">
                                                                    <input class="form-control wallet_inp" type="text" placeholder="Кошелек" value="<?=$bal->wallet?>">
                                                                </div>

                                                                <button class="col-md-4 btn btn-primary" onclick="setWallet('<?=$bal->currency?>',this,event)">задать кошелек</button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="withdraw_answer">

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div id="answer_alert" class="alert alert-dismissible alert-success fade in hide" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                                </button>
                                <div class="text">
                                    <strong>Поздравляем</strong>
                                    <span>Запрос на выплату успешно выполнен.</span>
                                </div>
                            </div>

                            <script>

                                function getWithdraw(currency,_this,e) {
                                    e.preventDefault();
                                    var submitBtn = $(_this);
                                    var item = $('#bal_'+currency);
                                    var data = {
                                        amount: (item.find('input.spinner').spinner("value")),
                                        currency: currency
                                    };
                                    submitBtn.attr('disabled', 'disabled');//выключить повторное нажатие
                                    {

                                        $.ajax({
                                            type: 'POST',
                                            url: '/web/dashboard/new-withdraw',
                                            data: data,
                                            success: function (res) {
                                                var alert = $('#answer_alert').clone().removeUniqueId();
                                                if (res.status == true) {
                                                    item.find('.withdraw_answer').append(alert).find('.alert').removeClass('hide');
                                                }
                                                if (res.status == false) {
                                                    alert.find('.text').html(res.message);
                                                    item.find('.withdraw_answer').append(alert).find('.alert').removeClass('hide')
                                                        .removeClass('alert-success').addClass('alert-danger');

                                                }
                                            }
                                        }).always(function () {
                                            submitBtn.removeAttr('disabled'); //включить кнопку
                                        });
                                    }
                                }

                                var rates = [];
                                <?php foreach ($currencies as $l=>$cur):?>
                                rates['<?=$l?>'] = {
                                    rate_btc:<?=$cur['rate_to_BTC'];?>,
                                    price:<?=$currencies[$l]['price']; ?>
                                };
                                <?php endforeach;?>
                                document.addEventListener("DOMContentLoaded", function () {

                                    var hideAll = function () {
                                        $('table.balance-table').find('.details').slideUp();
                                    };
                                    hideAll();
                                    $('table.balance-table a.detailBtn').on('click',function (e) {
                                        e.preventDefault();
                                        var n = $(this).parents('tr').index();
                                        if ($(this).parents('tbody').children('tr').eq(n+1).is(':hidden')) {
                                            hideAll();
                                        }
                                        $(this).parents('tbody').children('tr').eq(n+1).slideToggle(300).find('div.details').slideToggle(300);
                                    });

                                    $("#culture").on("change", function () {
                                        var cur = $(this).val();
                                        var table = $(this).closest('table');
                                        table.find('tbody tr:not(.details)').each(function () {
                                            $(this).attr('data-cur2', cur);
                                            var value = $(this).click();
                                        });
                                    });

                                });
                                function check_value(val, cur, _this) {
                                    if(rates[cur].rate_btc !== undefined) {
                                    var cur2 = $(_this).attr('data-cur2');
                                    var res = (val * rates[cur].price); //price in USD
                                    var res2 = (val * rates[cur].rate_btc).toFixed(15); //price in BTC
                                    var res3 = res2 / rates['ZEC'].rate_btc; //price in BTC
                                    var res4 = (res2 / rates[cur2].rate_btc).toFixed(15);

                                    $(_this).find('td.btc').text(res4 + ' ' + cur2);
                                    }
                                }

                            </script>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


