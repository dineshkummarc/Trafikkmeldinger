<?php
configure::write('debug',0); // surpress all debug output on json view
@ob_start ('ob_gzhandler');
header('Content-type: application/json; charset: UTF-8'); // set content-type
header('Cache-Control: must-revalidate');
header("Expires: " . gmdate('D, d M Y H:i:s', time() - 1) . ' GMT');
echo $content_for_layout; 
?>