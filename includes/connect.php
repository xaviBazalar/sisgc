<?php
$link=mysql_connect("localhost", "root", "") or die(mysql_error());
$db=mysql_select_db("gestions",$link) or die(mysql_error());
?>
