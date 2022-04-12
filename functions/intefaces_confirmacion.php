<?php

	
	include_once 'phpmailer/class.phpmailer.php';
	
	
	

	$mail = new PHPMailer();
	$mail->SetLanguage("es", "language/");
	$mail->From = "sistemas@altocontacto.com.pe";
	$mail->FromName = "Alto Contacto";
	
	$mail->AddAddress("ruben.catachura@bancocencosud.com.pe");
	$mail->AddAddress("pgonzales@altocontacto.com.pe");
	$mail->AddAddress("ribanez@altocontacto.com.pe");
	$mail->AddAddress("aaviles@altocontacto.com.pe");
	$mail->AddAddress("sistemas@altocontacto.com.pe");
	
	$mail->WordWrap = 200; // MÃ¡ximo caracteres de ancho
	$mail->IsHTML(true); // Establecer formato HTML
	$mail->Subject = "Interfaces ".date("Y-m-d")." - Alto Contacto";
	$contenido="Señores:<br/><br/> Se ha publicado la informacion del dia ".date("Y-m-d")."<br/></br>\n\n";
	
	


	$mail->AltBody = "Contenido en <b>html</b> del correo.";
	
	

	$fecha=date("Ymd");
	
	$contenido.="<table>";
	$filename="/var/www/html/sisgc/functions/actAE7_$fecha.zip";
	if(file($filename)){
		$mail->AddAttachment("/var/www/html/sisgc/functions/actAE7_$fecha.zip");
		$contenido.="<tr><td> actAE7_$fecha.zip </td></tr>";
		

	} else{ 
		
	}

	$filename="/var/www/html/sisgc/functions/600AE7_$fecha.zip";
	if(file($filename)){
		$mail->AddAttachment("/var/www/html/sisgc/functions/600AE7_$fecha.zip");
		$contenido.="<tr><td> 600AE7_$fecha.zip  </td></tr>";
		
	} else{ 
		
	}

	$filename="/var/www/html/sisgc/functions/dirAE7_$fecha.zip";
	if(file($filename)){
		$mail->AddAttachment("/var/www/html/sisgc/functions/dirAE7_$fecha.zip");
		$contenido.="<tr><td> dirAE7_$fecha.zip </td></tr>";
		
	} else{ 
		
	}

	$filename="/var/www/html/sisgc/functions/telAE7_$fecha.zip";
	if(file($filename)){
		$mail->AddAttachment("/var/www/html/sisgc/functions/telAE7_$fecha.zip");
		$contenido.="<tr><td> telAE7_$fecha.zip  </td></tr>";
	
	} else{ 
		
	}
	$contenido.="<tr><td>&nbsp;</td></tr><tr><td>Saludos</td></tr></table>";
	$contenido.='<p class="MsoNormal" style="background:white"><b><span lang="ES-MX" style="font-size:7.5pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#666666;font-style:normal">Gabriel Cama Mogollón&nbsp;</span></b><b><span style="font-size:7.5pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#666666;font-style:normal">| Sistemas | Alto Contacto S.A.C.<br>
Proyectos Tecnológicos</span></b><span style="font-size:7.5pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#666666;font-style:normal">&nbsp;&nbsp;<br>
</span><span lang="ES-MX" style="font-size:7.5pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#7f7f7f;font-style:normal">Av. La Molina 680 Piso 2 - La Molina | Lima -
Perú</span><span style="font-size:7.5pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#7f7f7f;font-style:normal"><br>
</span><span style="font-family:Wingdings;color:#7f7f7f;font-style:normal">)</span><span style="font-size:7.5pt;font-family:&quot;Verdana&quot;,&quot;sans-serif&quot;;color:#7f7f7f;font-style:normal">&nbsp;615-8888<br>
Email:&nbsp;<u><a href="mailto:aaviles@altocontacto.com.pe" target="_blank"><span style="color:#1155cc">sistemas@altocontacto.<wbr>com.pe</span></a></u></span><span lang="ES-MX" style="font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#222222;font-style:normal"><u></u><u></u></span></p>';
	$mail->Body ="$contenido";

	if(!$mail->Send()) {
	exit('No se pudo enviar el correo. Error: ' .$mail->ErrorInfo);
	} else {
	echo "Se enviÃ³ correctamente.";
	}	

	

?>
