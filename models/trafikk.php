<?php

class Trafikk extends AppModel {

    var $name = "Trafikk";
    // Do not use a database table
    var $useTable = false;

    function fetch() {
        // the URL to fetch
        $url = 'http://www.vegvesen.no/trafikk/xml/search.xml?searchFocus.counties=2&searchFocus.counties=9&searchFocus.counties=6&searchFocus.counties=20&searchFocus.counties=4&searchFocus.counties=12&searchFocus.counties=15&searchFocus.counties=17&searchFocus.counties=18&searchFocus.counties=5&searchFocus.counties=3&searchFocus.counties=11&searchFocus.counties=14&searchFocus.counties=16&searchFocus.counties=8&searchFocus.counties=19&searchFocus.counties=10&searchFocus.counties=7&searchFocus.counties=1&searchFocus.messageType=17&searchFocus.messageType=19&searchFocus.messageType=20&searchFocus.messageType=18&searchFocus.messageType=38&searchFocus.messageType=22&searchFocus.messageType=23&searchFocus.messageType=21&searchFocus.sortOrder=3';
        // use curl to fetch it
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        
        // digest the file and spit it out as json
        App::import('Xml');
        $xml =& new XML($response);
        $xml = Set::reverse($xml);
        $json = json_encode($xml);
        return $json;
    }

}

?>