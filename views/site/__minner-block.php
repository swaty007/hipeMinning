<?php
use yii\helpers\Url;
?>

<?php if ($col_md): ?>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?php else: ?>
<div class="col-md-3 col-sm-6 col-xs-12">
    <?php endif; ?>
    <div id="<?=$minner->id?>"
         class="block"
         data-rate="<?=number_format($currencies[$minner->currency]['rate'],20,'.',''); ?>"
         data-price_kh="<?=number_format($minner->getPrice(),20,'.','')?>"
    >
        <h2>МАЙНИНГ <?=$currencies[$minner->currency]['name']?></h2>
        <div class="img-block">
            <img src="/web/images/coins/<?=$minner->currency?>.png" alt="">
        </div>
        <div class="algorithm">
            <p><?=$minner->name?> МАЙНЕР</p>
        </div>
        <div class="listperi">
            <p class="title">Условия</p>
            <p><i class="glyphicon glyphicon-plus"></i>Минимальный Хэшрейт: <strong><i>100 KH/s</i></strong></p>
            <p><i class="glyphicon glyphicon-plus"></i>Алгоритм: <strong><i><?=$currencies[$minner->currency]['algoritm']?></i></strong></p>
            <p><i class="glyphicon glyphicon-plus"></i>Плата за обслуживание: <strong><i>5%</i></strong></p>
            <p><i class="glyphicon glyphicon-plus"></i>Оборудование: <strong><i><?=$minner->hardware?></i></strong></p>
            <p><i class="glyphicon glyphicon-plus"></i>Автоматические начисления в <kbd><?=$minner->currency?></kbd></p>
            <p><i class="glyphicon glyphicon-plus"></i>2 года контракт</p>
            <p class="title">Доход</p>
            <p class="income"><kbd id="income<?=$minner->id?>">$ 0.8</kbd> в месяц</p>
        </div>
        <div class="price">
            <p class="title">$</p>
            <input class="spinner" id="spinner<?=$minner->id?>" name="value" value="2.5">
            <p>за <span id="buykH<?=$minner->id?>">100</span> <span id="HashMulti<?=$minner->id?>">KH/s</span></p>
        </div>
        <a class="btn-coin" href="<?=Url::to(['/dashboard/buy-power','num_item'=>$minner->id]); ?>" onclick="changeHrefValue(<?=$minner->id?>,this)">
            купить
        </a>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var price = <?=$currencies[$minner->currency]['price']; ?>; //in USD

            var spinner = $( "#spinner<?=$minner->id?>" ).spinner({
                min: 2.50,
                step: 0.10,
                start: 2.5
            });
            spinner.on( "spin", function( event, ui ) {
                if(typeof rates !== 'undefined') {changeKHandMath(ui.value,<?=$minner->id?>,rates);}
                else {changeKHandMath(ui.value,<?=$minner->id?>);}

            } );
            spinner.on( "change", function( event ) {
                var value = +this.value;
                if(typeof rates !== 'undefined') {changeKHandMath(value,<?=$minner->id?>,rates);}
                else {changeKHandMath(value,<?=$minner->id?>);}
            } );

            if(typeof rates !== 'undefined') {changeKHandMath(spinner.spinner( "value" ),<?=$minner->id?>,rates);}
            else {changeKHandMath(spinner.spinner( "value" ),<?=$minner->id?>);}
        });
    </script>
</div>