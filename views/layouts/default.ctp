<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Trafikkmeldinger</title>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <?php echo $this->Html->css('main'); ?>
        <?php echo $this->Html->css('jquery-ui-1.8.6.custom'); ?>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
        <script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js"></script>
    </head>
    <body>
        <div class="wrapper">
            <div class="header">
                <h1>Trafikkmeldinger</h1>
            </div>
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
        <?php echo $content_for_layout; ?>
    </body>

</html>