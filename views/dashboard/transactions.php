<?php
$this->title = "Transactions";
?>

<?php \yii\widgets\Pjax::begin(); ?>

<div class="transactions-page">




    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Транзакции</h3>
                </div>
                <div class="panel-body">

                    <ul class="nav-main nav-justified">
                        <li>
                            <a class="btn btn-default" href = '?type=all'>Все операции</a>
                        </li>
                        <li>
                            <a class="btn btn-primary" href = '?type=deposit'>Депозиты</a>
                        </li>
                        <li>
                            <a class="btn btn-danger" href = '?type=withdraw'>Выплаты</a>
                        </li>
                        <li>
                            <a class="btn btn-success" href = '?type=mining'>Доход от майнинга</a>
                        </li>
                        <li>
                            <a class="btn btn-warning" href = '?type=referal'>Реферальные отчисления</a>
                        </li>
                    </ul>
                    <div class="row">

                        <div class="col-md-12">

                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-condensed" id="data_table" style="display: none;">
                                            <thead>
                                            <tr>
                                                <th>N</th>
                                                <th>Тип</th>
                                                <th>Дата создания</th>
                                                <th>Дата обновления</th>
                                                <th>Сумма в криптовалюте</th>
                                                <th>Сумма в $ (На момент транзакции)</th>
                                                <th>Статус</th>
                                                <th>Описание</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($transactions AS $k => $item):?>

                                                <?php
                                                switch($item->type)
                                                {
                                                    case 'deposit':
                                                        $desc = 'Покупка ' . \app\models\Minner::getHashById($item->minner_id,$item->amount1) .' мощностей майнера '.\app\models\Minner::getDisplayNameById($item->minner_id);
                                                        break;
                                                    case 'withdraw':
                                                        $desc = 'Вывод средств';
                                                        break;
                                                    case 'mining':
                                                        $desc = 'Начисление на баланс средств, намайненных за день майнером '.\app\models\Minner::getDisplayNameById($item->minner_id);
                                                        break;
                                                    case 'referal':
                                                        $desc = 'Реферальные отчисления от покупки мощностей пользователем  '.$item->txn_id.' майнера '.\app\models\Minner::getDisplayNameById($item->minner_id);
                                                        break;
                                                }
                                                switch($item->status)
                                                {
                                                    case '0':
                                                        $color_class = 'info';
                                                        break;
                                                    case '1':
                                                        $color_class = 'success';
                                                        break;
                                                    case '2':
                                                        $color_class = 'warning';
                                                        break;
                                                    case '3':
                                                        $color_class = '';
                                                        break;
                                                    case '4':
                                                        $color_class = 'danger';
                                                        break;
                                                }
                                                ?>


                                            <tr class="<?=$color_class;?>">
                                                <td class="<?=$item->type?>"><?=(int)(count($transactions) - $k)?></td>
                                                <td class="<?=$item->type?> text-center"><?=$item->type?></td>
                                                <td><?=$item->date_start?></td>
                                                <td><?=$item->date_last?></td>
                                                <td><?=$item->amount2 . ' ' . $item->currency2?></td>
                                                <td><?=$item->amount1 . ' $'?></td>
                                                <td><?=$status[$item->status]?></td>
                                                <td><?=$desc?></td>
                                            </tr>
                                            <?php endforeach;?>
                                            </tbody>
                                        </table>
                                        <script>
                                            (function () {
                                                var a = setInterval(function () {
                                                    if (document.readyState === 'complete') {
                                                        $('#data_table').DataTable( {
                                                            "order": [[ 2, "desc" ]]
                                                        } );
                                                        $('#data_table').show('slide',200)
                                                        clearInterval(a);
                                                    }
                                                },100)
                                            })();
                                        </script>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php \yii\widgets\Pjax::end(); ?>
