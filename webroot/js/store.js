function fetchData(){
        $.ajax({
            type: "GET",
            url: "/vegvesen/data",
            dataType: "json",
            success: function(data) {
                
                /*$(xml).find('message').each(function(){
                    var tmpdata = [];
                    tmpdata['heading']     = $(this).find('heading').text();
                    tmpdata['id']          = $(this).find('messagenumber').text();
                    tmpdata['version']     = $(this).find('version').text();
                    tmpdata['ingress']     = $(this).find('ingress').text();
                    tmpdata['freetext'] = $(this).find('freetext').text();
                    tmpdata['type']        = $(this).find('messageType').text();
                    tmpdata['urgency']     = $(this).find('urgency').text();
                    tmpdata['roadType']    = $(this).find('roadType').text();
                    tmpdata['roadNumber']  = $(this).find('roadNumber').text();
                    tmpdata['validFrom']   = $(this).find('validFrom').text();
                    tmpdata['validTo'] = $(this).find('validTo').text();
                    tmpdata['counties'] = [];
                    $(this).find('string').each(function(){
                        tmpdata['counties'].push($(this).text());
                    });*/
                    /*data.push(tmpdata);*/
                //<coordinates>
                //
                //});
                $('body').data('trafikk', data.Searchresult['Result-array'].Result.Messages.Message);
                startMap();
            }
        });
}