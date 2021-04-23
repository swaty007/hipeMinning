<?php
/* @var $this yii\web\View */


use yii\helpers\Html;

$this->title = 'Statistic';
$this->params['breadcrumbs'][] = $this->title;
?>




<div class="statistic-page">

        <div class="row">
            <div class="col-md-12">

                <?php foreach ($data as $key => $element):?>
                    <div class="row">
                        <div id="chartContainer<?=$key;?>" style="height: 670px; width: 100%;"></div>
                    </div>
                    <script>
                        window.addEventListener("load", function () {
                            var options = {
                                theme: "light1",
                                exportEnabled: true,
                                animationEnabled: true,
                                title:{
                                    text: "История курса <?=$key;?>"
                                },
                                subtitles: [{
//                                    text: "Click Legend to Hide or Unhide Data Series"
                                }],
                                axisX: {
                                    title: "Time",
                                },
                                axisY: {
                                    title: "Coin price",
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
                                        name: "<?=$key;?>",
                                        showInLegend: true,
                                        lineColor: "#C0504E",
                                        xValueFormatString: "DD MMM,YY",
                                        yValueFormatString: "0.#########0 USD",
                                        dataPoints: [
                                            <?php foreach ($element as $j => $item):?>
                                            { x: new Date('<?=$item['date'];?>'),y:<?=$item['price'];?>},
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
        </div>

</div>
