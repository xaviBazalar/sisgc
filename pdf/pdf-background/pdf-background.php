<?php
include('class.ezpdf.php');
include('class.backgroundpdf.php'); 
 
$pdf = new backgroundPDF('a4', 'portrait', 'image', array('img' => 'logo_sys3.jpg'));
$pdf->selectFont('Helvetica.afm'); 
$pdf->ezText('Background in PDF', 50); 
$pdf->ezText('', 12); 
$pdf->ezText('Ejemplo de PDF utilizando una imagen como Fondo.', 12); 
$pdf->ezNewPage(); 
$pdf->ezText('Pagina 2', 50); 
ob_end_clean();//linea muy importante
$pdf->ezStream();
?>