


<script type="text/javascript">
    // Array Remove - By John Resig (MIT Licensed)
    // Makes it easier to remove items from an array
    Array.prototype.remove = function(from, to) {
        var rest = this.slice((to || from) + 1 || this.length);
        this.length = from < 0 ? this.length + from : from;
        return this.push.apply(this, rest);
    };
    
    // IE8 fix for indexOf
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
        
    function checkboxList()
    {   
        // Prepare it!
        // wrapping it around a label for accessibility (clicking on the labels should check/uncheck the checkbox)
        var template = '<p><input id="location-${checkboxId}" class="fylkeCheck" type="checkbox"value="${fylke}" /> <label for="location-${checkboxId}">${fylke}</label></p>';
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
                        fylker.push({fylke: fylke.string, checkboxId: index});  //pass in a unique id for the element 
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
    
    /*
     **/
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
                
                if(entry.counties.length > 0){
                    var appendTarget = (data[index].messageType.toLowerCase().indexOf('stengt') != -1) ?
                    1 : (data[index].messageType.toLowerCase().indexOf('kolonne') != -1) ?
                    2 : 3; // 1 closed roads, 2 partially closed, 3 general info
                    /*
                    if(!$('#'+appendTarget).length)
                    {
                         $('<h3 id="header-appendTarget">'+appendTarget+'</h3><div id="'+appendTarget+'"></div>').
                            appendTo("#messageList");
                    }*/
                    entry.roadType2 = (entry.roadType == 'Ev') ? 'E' : null;
                    accordionContent.push({ "category" : appendTarget, "html" : $.tmpl(template, entry)[0]});
                    //$.tmpl(template, entry).appendTo( "#"+appendTarget);
                }
                
        });
        var stengt = { "id" : 1, "content" : [], "title": "Stengte veier"},
            kolonne = { "id" : 2, "content" : [], "title": "Kolonnekj&oslash;ring"},
            generell = { "id" : 3, "content": [], "title" : "Generell/Annen info"};
            
        $(accordionContent).each(function(index,value) {
            
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
        $('<h3 id="header-'+stengt.id+'">'+stengt.title+'</h3><div id="content-'+stengt.id+'">'+stengt.content.join("\n")+'</div>').appendTo('#messageList');
        $('<h3 id="header-'+kolonne.id+'">'+kolonne.title+'</h3><div id="content-'+kolonne.id+'">'+kolonne.content.join("\n")+'</div>').appendTo('#messageList');
        $('<h3 id="header-'+generell.id+'">'+generell.title+'</h3><div id="content-'+generell.id+'">'+generell.content.join("\n")+'</div>').appendTo('#messageList');
        
        $( "#messageList" ).accordion({ autoHeight: false });
    }
    
    /* Fetches a JSON file with the data */
    /* stors it in jquery data and starts map when successful */
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
    
    /* starts the google map */
    /* requires data to be loaded to set markers properly */
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
            {
                var veiX = data[index].Coordinates.StartPoint.xCoord;
                var veiY = data[index].Coordinates.StartPoint.yCoord;
                var veiLatlng = new google.maps.LatLng(veiY, veiX);
                var marker = new google.maps.Marker({
                    position: veiLatlng, 
                    title: data[index].heading + " - " + data[index].messageType,
                    map: map
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

    jQuery(document).ready(function($){
        var filterArray = $(document.body).data('filter', []);

        $('.center').html('<div id="map_canvas" style="width: 360px;height:600px;"></div>');
        
        // load the data
        fetchData();
        
    });
</script>