<?php
class DboDummy extends DboSource {
 // Dummy dbo source for no database
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