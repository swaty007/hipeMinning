<section id="slider-wrapp">
    <div id="wrapper" class="wrapper" >

        <?php ?>

        <script src="/web/js/modernizr.js" type="text/javascript" charset="utf-8"></script>
        <script src="/web/js/cute/cute.slider.js" type="text/javascript" charset="utf-8"></script>
        <script>
            Cute.ModuleLoader.css3d_files    =  ['/web/js/cute/cute.css3d.module.js'];
            Cute.ModuleLoader.canvas_files  =  ['/web/js/cute/cute.canvas.module.js'];
            Cute.ModuleLoader.dom2d_files  =  ['/web/js/cute/cute.2d.module.js'];
        </script>
        <script src="/web/js/cute/cute.transitions.all.js" type="text/javascript" charset="utf-8"></script>




        <div id="slider" class="cute-slider" data-width="800" data-height="350" data-force="" data-overpause="false">
            <ul data-type="slides">

                <li data-delay="3" data-trans3d="tr5,tr64,tr49,tr37,tr26" data-trans2d="">
                    <img src="/web/images/slider/Banner3.png" data-thumb="/web/images/slider/Banner3.png"/>
                    <!--                    <div data-type="info" class="info1" data-align="right">-->
                    <!--                        <div>-->
                    <!--                            <h1 class="title">Tired of boring flat sliders?</h1>-->
                    <!--                            <p class="text">-->
                    <!--                                Looking for a unique slider? Here's what you want, with Cute Slider you will be able to show your slides with awesome 3D &amp; 2D transitions.-->
                    <!--                            </p>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                </li>
                <li data-delay="3" data-trans3d="tr64,tr49,tr37,tr26,tr5" data-trans2d="">
                    <img src="/web/cute-theme/blank.jpg" data-src="/web/images/slider/Banner1.png" data-thumb="/web/images/slider/Banner1.png"/>
                    <!--                    <div data-type="info" class="info1" data-align="right">-->
                    <!--                        <div>-->
                    <!--                            <h1 class="title">Tired of boring flat sliders?</h1>-->
                    <!--                            <p class="text">-->
                    <!--                                Looking for a unique slider? Here's what you want, with Cute Slider you will be able to show your slides with awesome 3D &amp; 2D transitions.-->
                    <!--                            </p>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                </li>
                <li data-delay="3" data-trans3d="tr64,tr49,tr37,tr26,tr5" data-trans2d="">
                    <img src="/web/cute-theme/blank.jpg" data-src="/web/images/slider/Banner2.png" data-thumb="/web/images/slider/Banner2.png"/>
                    <!--                    <div data-type="info" class="info1" data-align="right">-->
                    <!--                        <div>-->
                    <!--                            <h1 class="title">Tired of boring flat sliders?</h1>-->
                    <!--                            <p class="text">-->
                    <!--                                Looking for a unique slider? Here's what you want, with Cute Slider you will be able to show your slides with awesome 3D &amp; 2D transitions.-->
                    <!--                            </p>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                </li>
                <li data-delay="3" data-trans3d="tr64,tr49,tr37,tr26,tr5" data-trans2d="">
                    <img src="/web/cute-theme/blank.jpg" data-src="/web/images/slider/Banner4.png" data-thumb="/web/images/slider/Banner4.png"/>
                    <!--                    <div data-type="info" class="info1" data-align="right">-->
                    <!--                        <div>-->
                    <!--                            <h1 class="title">Tired of boring flat sliders?</h1>-->
                    <!--                            <p class="text">-->
                    <!--                                Looking for a unique slider? Here's what you want, with Cute Slider you will be able to show your slides with awesome 3D &amp; 2D transitions.-->
                    <!--                            </p>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                </li>


                <!--                <li data-delay="3" data-trans3d="tr64,tr49,tr37,tr26,tr5" data-trans2d="">-->
                <!--                    <img src="/web/cute-theme/blank.jpg" data-src="/web/images/slider/Banner5.png" data-thumb="/web/images/slider/Banner5.png"/>-->
                <!--                    <div data-type="info" class="info1" data-align="right">-->
                <!--                        <div>-->
                <!--                            <h1 class="title">Tired of boring flat sliders?</h1>-->
                <!--                            <p class="text">-->
                <!--                                Looking for a unique slider? Here's what you want, with Cute Slider you will be able to show your slides with awesome 3D &amp; 2D transitions.-->
                <!--                            </p>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                </li>-->
            </ul>

            <ul data-type="controls">
                <li data-type="slideinfo" data-effect="fade"> </li>
                <li data-type="circletimer" data-color="#333333" data-stroke="13"> </li>
                <li data-type="next"> </li>
                <li data-type="previous"> </li>
                <li data-type="slidecontrol" data-thumbalign="up"> </li>
            </ul>

        </div>

    </div>
    <script>
        var slider = new Cute.Slider();
        slider.setup("slider" , "wrapper",{"pauseAutoPlayOnHover": false });
        //        slider.pause();

    </script>
</section>