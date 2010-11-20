<cake:nocache>
<?php
configure::write('debug',0); // surpress all debug output on json views
header('Content-type: text/x-json');
?>
</cake:nocache>
<?php echo $content_for_layout; ?>