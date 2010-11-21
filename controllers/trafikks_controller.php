<?php

class TrafikksController extends AppController {

    var $name = 'Trafikks';
    var $components = array('RequestHandler');
    var $helpers = array('Cache');

    // index doesn't do anything
    function index() {
        
    }

    function data() {
        $this->CacheAction = "5 minutes";
        if (($json = Cache::read('trafikk')) === false) {
            $json = $this->Trafikk->fetch();
            Cache::write('trafikk', $json);
        }

        $this->set('json', $json);
        $this->RequestHandler->respondAs('json');
        $this->layout = 'json';
    }

}

?>
