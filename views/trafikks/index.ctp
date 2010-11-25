<script src="js/mylibs/helpers/Array.js"></script>
<script src="js/mylibs/MapController.js"></script>
<script type="text/javascript">
    // the start button
    // =================================================
    jQuery(document).ready(function($){

        // just putting the filterArray into $.data() first as it's initial 
        // status is empty. Could perhaps be upgraded with a cookie feature
        var filterArray = $(document.body).data('filter', []);

        // unnecessary scripting 101? (website is 1 page!)
        $('.center').html('<div id="map_canvas" style="width: 500px;height:600px;"></div>');
    
        $('label').live('mouseover', function(){
            $(this).animate({
                color: "#000"
            }, 500);
        });
        $('label').live('mouseout', function(){
            $(this).animate({
                color: "#777"
            }, 500);
        });
        // load the data and start the chain reaction
        //fetchData();
        var BovanMap = new MapController();
        BovanMap.initialize();
    });
</script>