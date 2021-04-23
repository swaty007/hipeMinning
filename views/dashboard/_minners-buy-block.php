<section id="dash-minners-block">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-justified">

                <?foreach ($currency_minning as $q=>$coin): ?>
                    <li class="<?php if($q == 0) echo 'active';?>"><a href="#<?=$coin['key'];?>" data-toggle="tab">МАЙНИНГ <?=$coin['name'];?></a></li>
                <?php endforeach;?>

            </ul>
            <!-- Tab panes -->
            <div class="tab-content">

                <script>
                    var rates = [];
                    rates.page = 'index-dashboard';
                    <?php foreach ($currencies as $l=>$cur):?>
                    rates['<?=$l?>']=<?=$cur['rate_to_BTC'];?>;
                    <?php endforeach;?>
                    rates.btc_price = <?=$currencies['BTC']['price']; ?>;
                </script>

                    <?foreach ($currency_minning as $k=>$coin): ?>
                <div class="tab-pane fade <?php if($k == 0) echo 'in active';?>" id="<?=$coin['key'];?>">
                        <?php foreach($coin['minners'] as $j=>$minner):?>
                            <div class="row one-minner">
                            <?php echo $this->render('//site/__minner-block',['minner'=>$minner,'currencies'=>$currencies,'col_md'=>4]) ?>
                                <div class="col-md-8 col-sm-6 col-xs-12 calc-wrap">
                                    <div class="calc-block" id="cacl_block_<?=$minner->id?>" data-cur="<?=$minner->currency?>">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="block-header">
                                                    <div class="circle-label">Profit per all period</div>
                                                    <div class="circle-content usd">
                                                        $ 0.7045
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="block-header">
                                                    <div class="circle-label">Profit per all period</div>
                                                    <div class="circle-content btc">
                                                        $ 0.7045
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="block-row hour clearfix">
                                                    <div class="calc-col">
                                                        <div class="text">
                                                            Profit per hour
                                                        </div>
                                                        <div class="text">
                                                            $ 0.02348
                                                        </div>
                                                        <div class="text">
                                                            Hour
                                                        </div>
                                                    </div>
                                                    <div class="calc-col">
                                                        <div class="text">
                                                            Mined/day USD
                                                        </div>
                                                        <div class="text">
                                                            $ 0.02348
                                                        </div>
                                                    </div>
                                                    <div class="calc-col">
                                                        <div class="text">
                                                            Mined/day BTC
                                                        </div>
                                                        <div class="text">
                                                            $ 0.02348
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="block-row day clearfix">
                                                    <div class="calc-col">
                                                        <div class="text">
                                                            Profit per day
                                                        </div>
                                                        <div class="text">
                                                            $ 0.02348
                                                        </div>
                                                        <div class="text">
                                                            Day
                                                        </div>
                                                    </div>
                                                    <div class="calc-col">
                                                        <div class="text">
                                                            Mined/day USD
                                                        </div>
                                                        <div class="text">
                                                            $ 0.02348
                                                        </div>
                                                    </div>
                                                    <div class="calc-col">
                                                        <div class="text">
                                                            Mined/day BTC
                                                        </div>
                                                        <div class="text">
                                                            $ 0.02348
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="block-row week clearfix">
                                                    <div class="calc-col">
                                                        <div class="text">
                                                            Profit per week
                                                        </div>
                                                        <div class="text">
                                                            $ 0.02348
                                                        </div>
                                                        <div class="text">
                                                            Week
                                                        </div>
                                                    </div>
                                                    <div class="calc-col">
                                                        <div class="text">
                                                            Mined/day USD
                                                        </div>
                                                        <div class="text">
                                                            $ 0.02348
                                                        </div>
                                                    </div>
                                                    <div class="calc-col">
                                                        <div class="text">
                                                            Mined/day BTC
                                                        </div>
                                                        <div class="text">
                                                            $ 0.02348
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="block-row month clearfix">
                                                    <div class="calc-col">
                                                        <div class="text">
                                                            Profit per month
                                                        </div>
                                                        <div class="text">
                                                            $ 0.02348
                                                        </div>
                                                        <div class="text">
                                                            Month
                                                        </div>
                                                    </div>
                                                    <div class="calc-col">
                                                        <div class="text">
                                                            Mined/day USD
                                                        </div>
                                                        <div class="text">
                                                            $ 0.02348
                                                        </div>
                                                    </div>
                                                    <div class="calc-col">
                                                        <div class="text">
                                                            Mined/day BTC
                                                        </div>
                                                        <div class="text">
                                                            $ 0.02348
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                </div>
                    <?php endforeach;?>

            </div>

</section>