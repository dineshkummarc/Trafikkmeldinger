<!doctype html>  
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
    <head>
        <?php echo $html->charset('utf-8'); ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Trafikkmeldinger</title>
        <meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no" />
        <meta name="description" content="Trafikkmeldinger fra Statens Vegvesen på Google Maps. ">
        <meta name="author" content="Børge Antonsen">
        <link rel="shortcut icon" href="/favicon.ico">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="stylesheet" href="css/style.css?v=2">
        <script src="js/libs/modernizr-1.6.min.js"></script>
        <?php echo $this->Html->css('main'); ?>
        <?php echo $this->Html->css('jquery-ui-1.8.6.custom'); ?>
        </head>
        <body>
            <div id="container">
                <header>

                </header>
                <div class="left">
                    <h2>Velg et fylke eller to</h2>
                    <div id="fylkesList">

                    </div>
                </div>
                <div class="center">

                </div>
                <div class="right">
                    <div id="messageList">

                    </div>
                </div>
            </div>
            <footer>
                <p>Data hentes fra <a href="http://www.vegvesen.no">Statens Vegvesen</a> og brukes p&aring; eget ansvar!</p>
            </footer>
            <div class="about">
                <span class="title">Info</span>
                <h2>About</h2>
                <p>This project was made by me, B&oslash;rge Antonsen over a weekend, and then some.</p>
                <p>Please consider this to be beta, and don't depend on it. For more accurate data please visit the link in bottom left corner.</p>
                <p>For more information visit my <a href="http://github.com/bovan">github</a> page.</p>
            </div>
            <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
            <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
            <script>!window.jQuery && document.write(unescape('%3Cscript src="js/libs/jquery-1.4.4.js"%3E%3C/script%3E'))</script>
            <script src="js/plugins.js"></script>
            <script src="js/script.js"></script>
            <!--[if lt IE 7 ]>
            <script> //fix any <img> or .png_bg background-images
                $.getScript("js/libs/dd_belatedpng.js",function(){ DD_belatedPNG.fix('img, .png_bg'); });
            </script>
            <![endif]-->

            <!-- yui profiler and profileviewer - remove for production -->
            <script src="js/profiling/yahoo-profiling.min.js"></script>
            <script src="js/profiling/config.js"></script>
            <!-- end profiling code -->

            <script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>
            <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js"></script>
            <script type="text/javascript">
                $('.about .title').click(function(){
                    $('.about').animate({
                        top: ($('.about').css('top') == "-280px")? '10px' : "-280px"
                    }, 1000);
                });
            </script>
            <?php echo $content_for_layout; ?>
        </body>

        </html>