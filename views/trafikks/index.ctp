
<div id="fylkesList">


</div>
<div id="messageList">

</div>
<script type="text/javascript">
    
    function checkboxList()
    {   
        // Prepare it!
        var template = '<p><input class="fylkeCheck" type="checkbox"  value="${fylke}" /> ${fylke}</p>';
        var data = $(document.body).data('trafikk');
        var fylker = [];
        var fylkeTest = [];
        
        // Extract it!
        $(data).each(function(index, value){
            $(data[index].ActualCounties).each(function(i,fylke){
                if (!(fylkeTest.indexOf(fylke.string) >= 0))
                {
                    // if the string isn't null, prepare it for template
                    if(fylke.string != null)
                    {
                        fylker.push({fylke: fylke.string});   
                        fylkeTest.push(fylke.string);
                    }
                            
                }
            });
            
        });
        // Sort it!
        fylker.sort(function(a, b){
            var fA=a.fylke.toLowerCase(), fB=b.fylke.toLowerCase()
            if (fA < fB) //sort string ascending
                return -1 
            if (fA > fB)
                return 1
            return 0 //default return value (no sorting)
        });
        // Add it!
        $(fylker).each(function(index, value){
            $.tmpl(template, value).appendTo("#fylkesList");
        });
    
        // Enable it!
        $('.fylkeCheck').live('change', function(){
            if(this.checked)
            {
                // add more items to map and messagelist
            }
            else
            {
                    // remove items from map nd messagelist
            }
    });
}
    
/*
**/
function messageList()
{
var template =  '<div id="msg-${messagenumber}" class="infodiv urgency-${urgency}">'+
    '<div class="road-${roadType}">'+
    '<h4>${roadType2} ${roadNumber}</h4>'+
    '<h5>${counties}</h5>'+
    '</div>'+
    '<h3>${heading}</h3>'+
    '<p>${ingress}</p>'+
    '</div>';
var data = $(document.body).data('trafikk');
$(data).each(function(index, value){
    var entry = data[index];
    entry.counties = [];
    entry.roadType2 = (entry.roadType == 'Ev') ? 'E' : null;
    $(entry.ActualCounties).each(function(i, county){
        entry.counties.push(county.string);
    });
    $.tmpl(template, entry).appendTo( "#messageList");
});
}
    
/* Fetches a JSON file with the data */
/* stors it in jquery data and starts map when successful */
function fetchData(){
$.ajax({
    type: "GET",
    url: "/vegvesen/data",
    dataType: "json",
    success: function(data) {
        $('body').data('trafikk', data.Searchresult['Result-array'].Result.Messages.Message);
        startMap();
        messageList();
        checkboxList();
    }
});
}
    
/* starts the google map */
/* requires data to be loaded to set markers properly */
function startMap()
{
//this gets invoked after store.js loads
var myLatlng = new google.maps.LatLng(65.000, 16.500 );
var myOptions = {
    zoom: 4,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
};
var map = new google.maps.Map(document.getElementById("map_canvas"),  myOptions);

var data = $(document.body).data('trafikk');

$(data).each(function(index, value){
    var veiX = data[index].Coordinates.StartPoint.xCoord;
    var veiY = data[index].Coordinates.StartPoint.yCoord;
    var veiLatlng = new google.maps.LatLng(veiY, veiX);
    var marker = new google.maps.Marker({
        position: veiLatlng, 
        title: data[index].heading + " - " + data[index].messageType,
        map: map
    });
            
    var infowindow = new google.maps.InfoWindow({
        content: '<div class="mapPopup"><h3>'+data[index].heading+'</h3>'+
            '<p class="messageType">'+data[index].messageType+'</p>'+
            '<p>'+data[index].ingress+'</p></div>'
    });
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map,marker);
    });
});
}

jQuery(document).ready(function($){
$('.right').html('<div id="map_canvas" style="width: 500px;height:700px;"></div>');
        
// load the data
fetchData();
        
});
</script>