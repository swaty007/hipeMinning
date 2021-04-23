<section id="our-advantages">
    <div class="container">
        <div class="row">


                    <?php foreach ($minners as $minner): ?>
                        <?php echo $this->render('//site/__minner-block',['minner'=>$minner,'currencies'=>$currencies]) ?>
                    <?php endforeach; ?>

        </div>
    </div>
</section>