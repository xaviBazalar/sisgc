<?php 
//este upload no usa ajax solo iframe + JS 
//comentarios estare atento :D saludos Capa.
if (isset($_POST['name']))
{
    $file_name = $_POST['name']; //con este nombre identificams a nuestra archivo file que viene en $_FILES
    $file_temp = $_FILES[$file_name]['tmp_name']; //archivo temporal
    $file_move = $_FILES[$file_name]['name']; //moveoms el archivo
    if (move_uploaded_file($file_temp,$file_move))
    {
        $file_msg = "archivo  <strong>$file_move</strong> en servidos";
    } else {
        $file_msg = "error, archivo  <strong>$file_move</strong> no fue cargado";
    }
?>