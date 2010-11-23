


<script type="text/javascript">
    // Array Remove - By John Resig (MIT Licensed)
    // Makes it easier to remove items from an array
    // =================================================
    Array.prototype.remove = function(from, to) {
        var rest = this.slice((to || from) + 1 || this.length);
        this.length = from < 0 ? this.length + from : from;
        return this.push.apply(this, rest);
    };
    
// IE8 fix for indexOf
// =================================================
if(!Array.indexOf){
    Array.prototype.indexOf = function(obj){
        for(var i=0; i<this.length; i++){
            if(this[i]==obj){
                return i;
            }
        }
        return -1;
    }
}
    
var map;

// the checkboxList is fired when data is fetched, and will
// iterate over all data and add the counties that are found to the
// list
// =================================================
function checkboxList()
{   
    // Prepare it!
    // wrapping it around a label for accessibility (clicking on the labels should check/uncheck the checkbox)
    var template =  '<div><input id="location-${checkboxId}" class="fylkeCheck" type="checkbox"value="${fylke}" /> '+
        '<label for="location-${checkboxId}">${fylke}</label>'+
        '<ul class="tooltip"><li>(<span class="red">${stengt}</span> / <span class="blue">${total}</span>)<ul>'+
        '<li><h3>${fylke}</h3></li>'+
        '<li>Stengte veier: <span class="red">${stengt}</span></li>'+
        '<li>Kolonnekjøring: <span class="yellow">${kolonne}</span></li>'+
        '<li>Totale meldinger: <span class="blue">${total}</span></li>'+
        '</ul></li></ul></div>';
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
                    fylker.push({fylke: fylke.string, checkboxId: index, tooltip: null});  //pass in a unique id for the element 
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
        // but let's get the tooltip first, now that we have a nice array
        var tooltipData = labelTooltip(value.fylke);
        // and move it over to the value 
        value.stengt = tooltipData.stengt;
        value.kolonne = tooltipData.kolonne;
        value.total = tooltipData.total;
            
        $.tmpl(template, value).appendTo("#fylkesList");
    });
    
    // Enable it!
    $('.fylkeCheck').live('change', function(){
        var filter = $(document.body).data('filter');
        if(this.checked)
        {
            if(filter.indexOf(this.value) == -1)
            {
                filter.push(this.value);
                $(document.body).data('filter', filter);
            }
                    
        }
        else
        {
            if(filter.indexOf(this.value) >= 0)
            {
                filter.remove(filter.indexOf(this.value));
                $(document.body).data('filter', filter);
            }
            // remove items from map nd messagelist
        }
        // update
        messageList();
        drawMarkers();
    });
}
    
// labelTooltip returns tooltip data for use alongside the checkboxlist-label
function labelTooltip(fylke)
{
    var data = $(document.body).data('trafikk');
        
    // the 3 vars which will be returned
    var stengtCount = 0;
    var kolonneCount = 0;
    var count = 0;
        
    // iterate and count hits
    $(data).each(function(index, value){
        $(data[index].ActualCounties).each(function(i,v){
            if ((fylke.indexOf(v.string) >= 0))
            {
                // if the string isn't null, prepare it for template
                if(v.string != null)
                { 
                    (data[index].messageType.toLowerCase().indexOf('stengt') >= 0) ?
                        stengtCount++ :null; // count closed roads
                    (data[index].messageType.toLowerCase().indexOf('kolonne') >= 0) ?
                        kolonneCount++ : null; // count kolonne
                    count++; // and add a total count
                }
                            
            } 
            else if(v.String) // if road is across 2 counties, add it as normal
            {
                $(v.String).each(function(j,str) {
                    if(fylke.indexOf(str) >= 0)
                    {
                        (data[index].messageType.toLowerCase().indexOf('stengt') >= 0) ?
                            stengtCount++ :null;
                        (data[index].messageType.toLowerCase().indexOf('kolonne') >= 0) ?
                            kolonneCount++ : null;
                        count++;
                    }
                });
            }
        });
    })
        
    // return it
    return { stengt: stengtCount, kolonne: kolonneCount, total : count };
        
}
    
// messageList is an addon which provides a different overview over closed 
// roads and information in addition to the map view
// =================================================
function messageList()
{
    // start with resetting the list
    $('#messageList').empty();
    $('#messageList').accordion('destroy');
        
    var accordionContent = [];
    var template =  '<div><div id="msg-${messagenumber}" class="infodiv urgency-${urgency}">'+
        '<div class="road-${roadType}">'+
        '<h4>${roadType2} ${roadNumber}</h4>'+
        '<h5>${counties}</h5>'+
        '</div>'+
        '<h3>${heading}</h3>'+
        '<p>${ingress}</p>'+
        '</div></div>'; // added extra div which gets stripped (workaround)
    var data = $(document.body).data('trafikk');
    
    // iterate over the data retrieved
    $(data).each(function(index, value){
            
        // lazy shorthand
        var entry = data[index];
        entry.counties = [];
        // check counties for match
        $(entry.ActualCounties).each(function(i, county){

            //using the filter
            var filter = $(document.body).data('filter');
            // if road is in more than 1 county, iterate
            if($.isArray(county.String ))
            {
                $(county.String).each(function(j,str) {
                    // see if county exists in filter
                    if(filter.indexOf(str) < 0)
                        return false; // return false if it doesn't'
                    else    
                        entry.counties.push(str);
                });
            }
            else
            {
                // same as above, without array
                if(filter.indexOf(county.string) < 0)
                    return false;
                else
                    entry.counties.push(county.string);
            }
        });
        // if counties is added (it passed the filter)
        if(entry.counties.length > 0){
            // define the category
            console.log(data[index].messageType);
            var appendTarget = (data[index].messageType.toLowerCase().indexOf('stengt') != -1) ?
                1 : (data[index].messageType.toLowerCase().indexOf('kolonne') != -1) ?
                2 : 3; // 1 closed roads, 2 partially closed, 3 general info

            // add an E to europe-roads (E6 very popular in Norway)
            entry.roadType2 = (entry.roadType == 'Ev') ? 'E' : null;
            accordionContent.push({ "category" : appendTarget, "html" : $.tmpl(template, entry)[0]});
                    
        }
                
    });
    // information we'll use to generate accordion tabs
    var stengt = { "id" : 1, "content" : [], "title": "Stengte veier"},
    kolonne = { "id" : 2, "content" : [], "title": "Kolonnekj&oslash;ring"},
    generell = { "id" : 3, "content": [], "title" : "Generell/Annen info"};
            
    $(accordionContent).each(function(index,value) {
        // putting each item found earlier into the accordion tab
        switch(value.category)
        {
            case 1:
                stengt.content.push(value.html.innerHTML);
                break;
            case 2:
                kolonne.content.push(value.html.innerHTML);
                break;
            default:
                generell.content.push(value.html.innerHTML);
        }
    });
    if((stengt.content.length + kolonne.content.length + generell.content.length) > 0)
    {            
        // then a lazy man's appendTo containing accordion
        if(stengt.content.length > 0)
            $('<h3 id="header-'+stengt.id+'">'+stengt.title+'</h3><div id="content-'+stengt.id+'">'+stengt.content.join("\n")+'</div>').appendTo('#messageList');
        if(kolonne.content.length > 0)
            $('<h3 id="header-'+kolonne.id+'">'+kolonne.title+'</h3><div id="content-'+kolonne.id+'">'+kolonne.content.join("\n")+'</div>').appendTo('#messageList');
        if(generell.content.length > 0)
            $('<h3 id="header-'+generell.id+'">'+generell.title+'</h3><div id="content-'+generell.id+'">'+generell.content.join("\n")+'</div>').appendTo('#messageList');
            
        // which becomes an accordion right about.. now..
        $( "#messageList" ).accordion({ autoHeight: false });
    }
}
    
// Fetches a JSON file with the data 
// stors it in jquery data and starts map when successful 
// this should be run first!
// =================================================
function fetchData(){
    $.ajax({
        type: "GET",
        url: "trafikks/data",
        dataType: "json",
        success: function(data) {
            $('body').data('trafikk', data.Searchresult['Result-array'].Result.Messages.Message);
            // after the data is loaded, start the :*/
            startMap();     // map
            checkboxList(); // checkboxList
        }
    });
}
    
// starts the google map 
// requires data to be loaded to set markers properly 
// to be able to work with it later, we just store it in $.data()
// =================================================
function startMap()
{
    // We're going to Norway
    var myLatlng = new google.maps.LatLng(65.000, 16.500 );
    var myOptions = {
        zoom: 4,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    // start map
    var map = new google.maps.Map(document.getElementById("map_canvas"),  myOptions);
    // set some bounds
    var bounds = new google.maps.LatLngBounds();
        
    // store it in the body
    $(document.body).data('map', map);
    $(document.body).data('bounds', bounds);
        
}
    
// Draw markers draws markers based on the filter set
// To avoid ending up with lots of elements, they are 
// stored in data(), so they can be retrieved and removed from the map.
// =================================================
function drawMarkers()
{
    // fetch any markers and remove them from the map before drawing
    // new markers
    var markersArray = $(document.body).data('markers');
    if(markersArray)
    {
        for(var i = 0;i< markersArray.length;i++)
        {
            try {
                markersArray[i].setMap(null);    
            }
            catch(err)
            {
                console.log("error!");
                console.log(err);
            }
        }
        markersArray.length = 0;
    }
    else
    {
        markersArray = [];
    }
        
    var data = $(document.body).data('trafikk');
    var map = $(document.body).data('map');
    //var bounds = $(document.body).data('bounds');
    // no way of resetting as I found, so make new
    var bounds = new google.maps.LatLngBounds();
        
    //using the filter to see what to draw  
    var filter = $(document.body).data('filter');
        
    $(data).each(function(index, value){
        // filter function
        $(data[index].ActualCounties).each(function(i, county){

                
            // if road is in more than 1 county, iterate
            if($.isArray(county.String ))
            {
                $(county.String).each(function(j,str) {
                    // see if county exists in filter
                    // with added check to see if there's more to the array
                    data[index].include = (filter.indexOf(str) < 0 && county.String.length > j) ? false : true;
                });
            }
            else
            {
                // same as above, without array
                data[index].include = (filter.indexOf(county.string) < 0) ? false: true;
            }
        });
            
        if(data[index].include)
        {   var iconStop = new google.maps.MarkerImage('img/icon-stop.png',
            new google.maps.Size(20, 20));
            var iconWarn = new google.maps.MarkerImage('img/icon-warn.png',
            new google.maps.Size(20, 20));
            var iconInfo = new google.maps.MarkerImage('img/icon-info.png',
            new google.maps.Size(20, 20));
                    
            var veiX = data[index].Coordinates.StartPoint.xCoord;
            var veiY = data[index].Coordinates.StartPoint.yCoord;
            var veiLatlng = new google.maps.LatLng(veiY, veiX);
            var marker = new google.maps.Marker({
                position: veiLatlng, 
                title: data[index].heading + " - " + data[index].messageType,
                map: map, 
                icon: (data[index].messageType.toLowerCase().indexOf('stengt') != -1) ?
                    iconStop : (data[index].messageType.toLowerCase().indexOf('kolonne') != -1) ?
                    iconWarn : iconInfo
            });
            bounds.extend(veiLatlng);
            markersArray.push(marker);
            
            var infowindow = new google.maps.InfoWindow({
                content: '<div class="mapPopup"><h3>'+data[index].heading+'</h3>'+
                    '<p class="messageType">'+data[index].messageType+'</p>'+
                    '<p>'+data[index].ingress+'</p></div>'
            });
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map,marker);
            });
        }
    });
    $(document.body).data('markers',markersArray);
    if(filter.length > 0)
        map.fitBounds(bounds);
}


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
    fetchData();
        
});
</script>