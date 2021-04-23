<?php
/* @var $this yii\web\View */


use yii\helpers\Html;

$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profile-page">

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?=$this->title?></h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">

                            <div id="wallets-accordion" class="panel panel-primary">
                                <div class="panel-heading" data-toggle="collapse" data-parent="#wallets-accordion" href="#collapseWallet">
                                        <h3 class="panel-title">Кошельки</h3>
                                </div>
                                <div id="collapseWallet" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <?php foreach ($keys as $key):?>
                                        <div class="form-group clearfix" id="bal_<?=$key;?>">
                                            <div class="row">
                                                <label for="UserWallet" class="col-sm-3 control-label control-label"><?=$key;?>
                                                    Кошелёк
                                                </label>
                                                <div class="col-sm-9">
                                                    <input class="form-control wallet_inp" type="text" value="<?=$wallets[$key]['wallet'];?>">
                                                    <span class="help-block">
                                                        Смена адреса кошелька блокирует вывод средств на срок 2 недели после подтверждения, исходя из соображений безопасности
                                                    </span>
                                                </div>
                                                <div class="form-group clearfix">
                                                    <div class="col-md-12 text-center">
                                                        <button class="btn btn-primary" onclick="setWallet('<?=$key;?>',this,event)">задать кошелек</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="withdraw_answer"></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div id="profile-accordion" class="panel panel-primary">
                                <div class="panel-heading" data-toggle="collapse" data-parent="#profile-accordion" href="#collapseProfile">
                                    <h3 class="panel-title">Контактнные данные</h3>
                                </div>
                                <div id="collapseProfile" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="clearfix form-horizontal">

                                        <?php foreach ($user as $key=>$item):?>
                                            <?php if($key == 'name' || $key == 'last_name' || $key == 'address' || $key == 'city'):?>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-3" for="f_<?=$key?>"> <?=$key?></label>
                                                    <div class=" col-sm-9">
                                                        <input class="form-control" id="f_<?=$key?>" name="<?=$key?>" type="text"
                                                               placeholder=" <?=$key?>" value="<?=$item?>">
                                                    </div>
                                                </div>
                                                <? elseif ($key == 'country_id'):?>

                                                <? elseif ($key == 'birthday'):?>
                                                <div class="form-group">
                                                    <label class="control-label col-sm-3" for="f_<?=$key?>"> <?=$key?></label>
                                                    <div class=" col-sm-9">
                                                        <input class="form-control" id="f_<?=$key?>" name="<?=$key?>" type="text"
                                                               placeholder=" <?=$key?>" value="<?=$item?>">
                                                    </div>
                                                </div>
                                                <script>
                                                    (function () {
                                                        var a = setInterval(function () {
                                                            if (document.readyState === 'complete') {
                                                                $( "#f_<?=$key?>" ).datepicker({
                                                                    changeMonth: true,
                                                                    changeYear: true,
                                                                    dateFormat: 'yy-mm-dd',
                                                                    minDate: new Date(1960, 1 - 1, 1),
                                                                    maxDate: "+1m +1w"
                                                                });
                                                                clearInterval(a);
                                                            }
                                                        },100)
                                                    })();
                                                </script>
                                                <?php endif;?>
                                        <?php endforeach;?>
                                        <div class="form-group">
                                            <div class="col-md-12 text-center">
                                                <button class="btn btn-primary" onclick="updateProfile(this, event)">Сохранить</button>
                                            </div>
                                        </div>
                                    </div>
                                  <div class="prof_upd_answ"></div>
                                    <script>
                                        function updateProfile(_this,e) {
                                            e.preventDefault();
                                            var submitBtn = $(_this);
                                            var data = {
                                                name: $('#f_name').val(),
                                                last_name: $('#f_last_name').val(),
                                                city: $('#f_city').val(),
                                                address: $('#f_address').val(),
                                                birthday: $('#f_birthday').val(),
                                            };
                                            submitBtn.attr('disabled', 'disabled');//выключить повторное нажатие
                                            {

                                                $.ajax({
                                                    type: 'POST',
                                                    url: '/web/dashboard/update-profile',
                                                    data: data,
                                                    success: function (res) {
                                                        var alert = $('#answer_alert').clone().removeUniqueId();
                                                        if (res.status == true) {
                                                            alert.find('.text span').text('Профиль успешно обновлен.');
                                                            $('.prof_upd_answ').append(alert).find('.alert').removeClass('hide');
                                                        }
                                                        if (res.status == false) {
                                                            alert.find('.text').html(res.message);
                                                            $('.prof_upd_answ').append(alert).find('.alert').removeClass('hide')
                                                                .removeClass('alert-success').addClass('alert-danger');

                                                        }
                                                    }
                                                }).always(function () {
                                                    submitBtn.removeAttr('disabled'); //включить кнопку
                                                });
                                            }
                                        }
                                    </script>
                                </div>
                                </div>
                            </div>

                            <div id="password-accordion" class="panel panel-primary">
                                <div class="panel-heading" data-toggle="collapse" data-parent="#password-accordion" href="#collapsePassword">
                                    <h3 class="panel-title">Пароль</h3>
                                </div>
                                <div id="collapsePassword" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <div class="form-horizontal">
                                            <div class="form-group clearfix">
                                                <label for="password_1" class="col-sm-3 control-label control-label">
                                                    Пароль
                                                </label>
                                                <div class="col-sm-9">
                                                    <input id="password_1" class="form-control" type="password" value="">
                                                </div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <label for="password_2" class="col-sm-3 control-label control-label">
                                                    Повторите пароль
                                                </label>
                                                <div class="col-sm-9">
                                                    <input id="password_2" class="form-control" type="password" value="">
                                                </div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <div class="col-md-12 text-center">
                                                    <button class="btn btn-primary" onclick="setPassword(this,event)">Сохранить</button>
                                                </div>
                                            </div>
                                            <div class="withdraw_answer_pass"></div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                function setPassword(_this,e) {
                                    e.preventDefault();
                                    var submitBtn = $(_this);
                                    var data = {
                                        password1: $('#password_1').val(),
                                        password2: $('#password_2').val()
                                    };
                                    submitBtn.attr('disabled', 'disabled');//выключить повторное нажатие
                                    {
                                        $.ajax({
                                            type: 'POST',
                                            url: '/web/dashboard/change-password',
                                            data: data,
                                            success: function (res) {
                                                $('#password_1').val('');
                                                $('#password_2').val('');
                                                var alert = $('#answer_alert').clone().removeUniqueId();
                                                if (res.status == true) {
                                                    alert.find('.text span').text('Пароль успешно изменен.');
                                                    $('.withdraw_answer_pass').append(alert).find('.alert').removeClass('hide');
                                                }
                                                if (res.status == false) {
                                                    alert.find('.text').html(res.message);
                                                    $('.withdraw_answer_pass').append(alert).find('.alert').removeClass('hide')
                                                        .removeClass('alert-success').addClass('alert-danger');
                                                }
                                            }
                                        }).always(function () {
                                            submitBtn.removeAttr('disabled'); //включить кнопку
                                        });
                                    }
                                }
                            </script>

                        </div>
                    </div>
                </div>
            </div>

            <div id="answer_alert" class="alert alert-dismissible alert-success fade in hide" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <div class="text">
                    <strong>Поздравляем</strong>
                    <span>Кошелек успешно изменен.</span>
                </div>
            </div>

        </div>
    </div>

</div>