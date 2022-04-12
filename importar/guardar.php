<?php
$name = $_GET['name'];
header("Content-disposition: attachment; filename=$name");
header("Content-type: application/octet-stream");
readfile($name); 

?>