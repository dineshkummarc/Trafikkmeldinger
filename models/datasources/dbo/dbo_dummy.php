<?php
class DboDummy extends DboSource {
 
        function connect(){
                $this->connected = true;
                return true;
        }
 
        function disconnect(){
                $this->connected = false;
                return true;
        }
 
}
?>