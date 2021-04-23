var ModalBuy = {// buy-money page
    modal_id: 0,
    init: function (_this) {
        var sbmBtn = $(_this);

        var id = sbmBtn.attr('data-id');
        if (this.modal_id !== 0 || this.modal_id !== id) {
            $('#modal_'+this.modal_id).remove();
        }
        this.modal_id = id;

        var amount = Number(parseFloat($('#calc_to_usd').val()));

        var cur = $( '#culture_main').val();
        var data = {
            value1: amount,
            currency2: cur,
            minner_id: id,
        };

        var rate = $('#'+id).attr('data-rate');
        var price_kh = $('#'+id).attr('data-price_kh');
        var modal_img = $('#'+id).find('.img-block img').attr('src')
        var modal_title_cur = $('#'+id).find('.buy-title-cur').text();
        var modal_title_hardware = $('#'+id).find('.buy-title-hardware').text();
        var html = `
        <div class="modal fade modal-buy-power" id="modal_${id}">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Купить мощностей</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div id="modal-minner"
                                 data-rate="${rate}"
                                 data-price_kh="${price_kh}"
                                 class="buy-block-item">
                                <div class="img-block">
                                    <img src="${modal_img}" alt="">
                                </div>
                                <h2 class="buy-title-cur">
                                    ${modal_title_cur}
                                </h2>
                                <div class="hashrate-block">
                                    <span id="buykHmodal-minner">100</span> <span id="HashMultimodal-minner">KH/s</span>
                                </div>
                                <h3 class="buy-title-hardware">
                                    ${modal_title_hardware}
                                </h3>
                                <hr class="separate">
                                <p class="text-income-title">Ваш доход: </p>
                                <p class="income-usd"><span id="incomemodal-minner_hour">$ 0.0</span> / час</p>
                                <p class="income-coin"><span id="coin_incomemodal-minner_hour">$ 0.0</span></p>
                                <p class="income-usd"><span id="incomemodal-minner_day">$ 0.0</span> / день</p>
                                <p class="income-coin"><span id="coin_incomemodal-minner_day">$ 0.0</span></p>
                                <p class="income-usd"><span id="incomemodal-minner">$ 0.0</span> / месяц</p>
                                <p class="income-coin"><span id="coin_incomemodal-minner">$ 0.0</span></p>
                                <p class="income-usd"><span id="incomemodal-minner_all">$ 0.0</span> / 2 года</p>
                                <p class="income-coin"><span id="coin_incomemodal-minner_all">$ 0.0</span></p>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div id="answer" class="answer">
                                <label class="control-label">Адресс Кошелька</label>
                                <p class="address form-copy-text" onclick="copyText(this);"></p>
                                <div class="row"><div class="col-md-6"> <div class="form-group">
                                          <label class="control-label">Сума в USD</label>
                                          <p class="text-am-cur"><span class="amount1"></span> <span class="currency1"></span></p>
                                       </div></div>
                                   <div class="col-md-6"><div class="form-group">
                                          <label class="control-label">Сума в валюте</label>
                                          <p class="text-am-cur"><span class="amount2"></span> <span class="currency2"></span></p>
                                       </div></div></div>
                                <div class="img-qr-block">
                                     <img src="" class="qrcode_url" />
                                </div>
                               <a class="status_url">Status_URL</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `;
        $('body').append(html);
        $('#modal_'+id).modal('show');
        var answer = $('#answer');

        if (amount != '') {
            sbmBtn.attr('disabled', 'disabled');//выключить повторное нажатие

            var windowOpen = $('<div />');
            $.ajax({
                type: "POST",
                url: "/web/dashboard/buy-power",
                data: data,
                success: function (msg) {
                    console.log(msg);
                    if (msg.msg.result === 'ok') {
                        answer.find('.address').text(msg.msg.address);
                        answer.find('.status_url').attr('href',msg.msg.status_url);
                        answer.find('.qrcode_url').attr('src',msg.msg.qrcode_url);
                        answer.find('.amount1').text(msg.transaction.amount1);
                        answer.find('.amount2').text(msg.transaction.amount2);
                        answer.find('.currency1').text(msg.transaction.currency1);
                        answer.find('.currency2').text(msg.transaction.currency2);
                        $('#modal_'+id).addClass('loaded');
                        changeKHandMath(msg.transaction.amount1,'modal-minner',rates)
                    }
                    else {
                        answer.after(msg.msg);
                    }

                }
            }).always(function(){
                sbmBtn.removeAttr('disabled'); //включить кнопку
            });

        }
    }
}
function copyText(_this, text) {
    if (text !== undefined) {
        var temp = $("<input>");
        $("body").append(temp);
        temp.val(text).select();
        document.execCommand("copy");
        temp.remove();
    } else {
        var temp = $("<input>");
        $("body").append(temp);
        temp.val($(_this).text()).select();
        document.execCommand("copy");
        temp.remove();
    }

    $(_this).tooltip('hide').data('bs.tooltip', false);
    $(_this).tooltip({
        trigger: 'manual',
        placement: 'top',
        title: 'Copied'
    }).tooltip('show');
    setTimeout(function () {
        $(_this).tooltip('hide');
    }, 600);
}


function setWallet(currency, _this,e) {
    e.preventDefault();
    var submitBtn = $(_this);
    var item = $('#bal_'+currency);
    var data = {
        wallet: item.find('input.wallet_inp').val(),
        currency: currency
    };
    submitBtn.attr('disabled', 'disabled');//выключить повторное нажатие
    {

        $.ajax({
            type: 'POST',
            url: '/web/dashboard/set-wallet',
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

function changeKHandMath(value_inp,id,rates) {
    var _id = '#'+id;

    var price_kh = $(_id).attr('data-price_kh');
    var rate = $(_id).attr('data-rate');
    var buyKhMount = (value_inp/price_kh);
    var kHMountLength = LengthOfNum(buyKhMount.toFixed(0));
    var HashMultiBlock = $('#HashMulti'+id);
    var buyKH = $('#buykH'+id);
    if (id === 4) {
        HashMultiBlock.text('Sol');
        buyKH.text((buyKhMount*1000).toFixed(2));
    }
    else if(kHMountLength > 10) {
        HashMultiBlock.text('TH/s');
        buyKH.text((buyKhMount/1000000000).toFixed(4));
    } else if (kHMountLength >7) {
        HashMultiBlock.text('GH/s');
        buyKH.text((buyKhMount/1000000).toFixed(2));
    } else if (kHMountLength >4) {
        HashMultiBlock.text('MH/s');
        buyKH.text((buyKhMount/1000).toFixed(2));
    } else {
        HashMultiBlock.text('KH/s');
        buyKH.text(buyKhMount.toFixed(2));
    }

    var val_hour = (60*rate*buyKhMount);
    var val_day = (24*60*rate*buyKhMount);
    var val_mounth = (30*24*60*rate*buyKhMount);

    val_hour = val_hour.toFixed(myToFixed(val_hour));
    val_day = val_day.toFixed(myToFixed(val_day));
    val_mounth = val_mounth.toFixed(myToFixed(val_mounth));

    $('#income'+id).text('$ '+val_mounth);
    $('#income'+id+'_day').text('$ '+val_day);
    $('#income'+id+'_hour').text('$ '+val_hour);

    if(rates) {
        if (rates.page == 'buy-power') {
            // доход в долларах
            var cur = $( "#culture_main" ).val();
            $('#coin_income' + id).text(check_rate((24 * 30 * 60 * rate * buyKhMount), cur, rates).toFixed(8) + ' ' + cur);
            $('#coin_income' + id + '_day').text(check_rate((24 * 60 * rate * buyKhMount), cur, rates).toFixed(8) + ' ' + cur);
            $('#coin_income' + id + '_hour').text(check_rate((60 * rate * buyKhMount), cur, rates).toFixed(8) + ' ' + cur);

            var all_time = (24*30*24*60*rate*buyKhMount);
            $('#income'+id+'_all').text('$ '+  all_time.toFixed(myToFixed(all_time)));
            $('#coin_income' + id + '_all').text(check_rate(all_time, cur, rates).toFixed(8) + ' ' + cur);
        } else if (rates.page == 'index-dashboard') {
            var cur = $( "#cacl_block_"+id ).attr('data-cur');
            $('#cacl_block_'+id).find('.circle-content.usd').text(check_rate((24*30*24*60 * rate * buyKhMount), 'USD', rates).toFixed(8) + ' USD');
            $('#cacl_block_'+id).find('.circle-content.btc').text(check_rate((24*30*24*60 * rate * buyKhMount), cur, rates).toFixed(8) + ' ' + cur);

            function allDays(_class,time) {
                $('#cacl_block_'+id).find(_class).each(function (i, elem) {
                    $(elem).find('.calc-col:nth-child(1) .text:nth-child(2)').text(check_rate((time * rate * buyKhMount), cur, rates).toFixed(8) + ' ' + cur);
                    $(elem).find('.calc-col:nth-child(2) .text:nth-child(2)').text(check_rate((time * rate * buyKhMount), 'USD', rates).toFixed(10) + ' USD');
                    $(elem).find('.calc-col:nth-child(3) .text:nth-child(2)').text(check_rate((time * rate * buyKhMount), 'BTC', rates).toFixed(10) + ' BTC');
                });
            }
            allDays('.hour',60);
            allDays('.day',60*24);
            allDays('.week',60*24*7);
            allDays('.month',60*24*30);
        }
    }
}
function myToFixed(n) {
    switch (LengthOfNum(n)) {
        case 1:
            return 5;
            break;
        case 2:
            return 4;
            break;
        case 3:
            return 3;
            break;
        case 4:
            return 2;
            break;
        default:
            return 2;
            break;
    }
}

function LengthOfNum(n)           //Функция возвращает количество цифр
{                                 //  в записи натурального числа n
    var count = 0;
    do {n /= 10; count++} while (n >= 1);   // n/=10 - это тоже, что и n=n/10
    return count;
}

function check_rate(val,cur,rates) {
    var res = (val/rates.btc_price)/rates[cur];

    return res;
}
function check_rate_USD(val,cur,rates) {
    var res = (val*rates.btc_price)*rates[cur];

    return res;
}

function changeHrefValue (id,_this) {
    var _id = '#'+id;
    var _src = $(_this).attr('href');
    var value = $(_id).find('.price input').val();
    $(_this).attr('href',  _src + '&value='+value);
}

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min)) + min;
}