<?php
configure::write('debug',0); // surpress all debug output on json views
header('Content-type: text/x-json'); // set content-type
echo $content_for_layout; ?>