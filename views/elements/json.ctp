<?php
    header('Content-type: application/json');
    header('X-JSON: ' . $js->object($response));
    // Convert the PHP array to JSON and echo it
    echo t($data);
?>