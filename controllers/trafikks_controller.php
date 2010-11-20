<?php

class TrafikksController extends AppController {

    var $name = 'Trafikks';
    var $components = array('RequestHandler');
    var $helpers = array('Cache');

    function index() {
        
    }

    function data() {
        Configure::write('debug', 0);
        
        //$this->cacheAction = "10 minutes";
        $this->CacheAction = "5";
        if (($json = Cache::read('trafikk')) === false) {
            $json = $this->Trafikk->fetch();
            Cache::write('trafikk', $json);
        }

        $this->set('json', $json);
        $this->RequestHandler->respondAs('json');
        $this->layout = 'json';
        //header('Content-type: application/json');
    }

}

?>
