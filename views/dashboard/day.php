<?php
/* @var $this yii\web\View */


use yii\helpers\Html;

$this->title = 'Statistic';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="statistic-page">

        <script>
            var setTic = function (n) {
                var d = new Date();
                d.setHours(n);
                return d;
            };
        </script>
        <?php foreach ($data as $key => $element):?>
        <div class="row">
            <div class="col-md-12">
                <div id="chartContainer<?=$key;?>" style="height: 670px; width: 100%;"></div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-condensed" id="data_table">
                            <thead>
                            <tr>
                                <th>Miner</th>
                                <th>Profit</th>
                                <th>Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($element as $j => $hour):?>
                                <?php if ($j != 'minner_info' && $hour['value'] != 0):?>
                                    <tr>
                                    <td style = "width:50%;"><?=$element['minner_info']['name'] . ' ' . $element['minner_info']['hardware']?></td>
                                    <td style = "width:40%;"><?=$hour['value']?></td>
                                    <td><?=($j < 10) ? '0'.$j.':00' : $j.':00'?></td>
                                    </tr>
                                <?php endif;?>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
<script>
    window.addEventListener("load", function () {
        var options = {
            theme: "light1",
            exportEnabled: true,
            animationEnabled: true,
            title:{
                text: "Майнинг за день <?=$element['minner_info']['currency'];?>"
            },
            subtitles: [{
//                text: "Click Legend to Hide or Unhide Data Series"
            }],
            axisX: {
                title: "Time"
            },
            axisY: {
                title: "Coins mined",
                titleFontColor: "#4F81BC",
                lineColor: "#4F81BC",
                labelFontColor: "#4F81BC",
                tickColor: "#4F81BC",
                includeZero: false
            },

            axisY2: {
                title: "Profit in USD",
                titleFontColor: "#C0504E",
                lineColor: "#C0504E",
                labelFontColor: "#C0504E",
                tickColor: "#C0504E",
                includeZero: false
            },
            toolTip: {
                shared: true
            },
            legend: {
                cursor: "pointer",
                itemclick: toggleDataSeries
            },
            data: [
                {
                type: "spline",//"column",//"area", //spline
                name: "<?=$element['minner_info']['name'] . ' ' . $element['minner_info']['hardware']?>",
                showInLegend: true,
                 xValueFormatString: "MMM HH:00",
                yValueFormatString: "0.#########0 <?=$element['minner_info']['currency']?>",
                dataPoints: [
                    <?php foreach ($element as $j => $item):?>
                    <?php if ($j != 'minner_info'):?>
                    { x: setTic(<?=$j?>),y:<?=$item['value']?>},
                    <?php endif;?>
                <?php endforeach;?>
                    ]
            },

            ]
        };
        $("#chartContainer<?=$key;?>").CanvasJSChart(options);

        function toggleDataSeries(e) {
            if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            e.chart.render();
        }

    });
</script>
        <?php endforeach;?>
    </div>
