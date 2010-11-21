<?php

class TrafikksController extends AppController {

    var $name = 'Trafikks';
    
    // Requesthandler is used for responding as JSON
    var $components = array('RequestHandler');
    
    // Caching is enabled to avoid pulling too many requests from the data
    var $helpers = array('Cache');

    // index doesn't do anything other than providing the 
    // default action
    function index() {
        
    }

    // Data provides the JSON file with all the data
    function data() {
        // Cache time = 5 minutes
        $this->CacheAction = "5 minutes";
        
        // Try to read cache
        if (($json = Cache::read('trafikk')) === false) {
            // get updated information if cache is expired
            $json = $this->Trafikk->fetch();
            Cache::write('trafikk', $json);
        }
        // make it available to the view
        $this->set('json', $json);
        // set view to JSON
        $this->RequestHandler->respondAs('json');
        $this->layout = 'json';
    }

}

?>
