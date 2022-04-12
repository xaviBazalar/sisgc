<?
	include("funciones.php"); 
	//conectar a base de datos
    $linkP=Conectarse();
    $linkM=ConectarseMysql();
	
	//Variables comunes
	$chr='&nbsp;';
	$negid=791;
	$gestest='N';
	$giaid=1;
	$gessigno='1';
	
	//selecionamos y agrupamos data final
   	$query="SELECT id, phone, dni, disposition, agent, lastupdated, cb_datetime, periodo, campana 
	        FROM tmp_ripley_6";
    $result=pg_query($linkP,$query); 
    $num=pg_num_rows($result);
	for($i=0; $i<$num; $i++) 
	{
	 $row=pg_fetch_row($result);
	 $id			=$row[0];	 
	 $phone			=$row[1];
	 $dni			=$row[2];
	 $disposition	=$row[3];
	 $agent			=$row[4];
	 $lastupdated	=$row[5];
	 $cb_datetime	=$row[6];	 
	 $periodo		=$row[7];	 	 
	 $campana		=$row[8];	 	 	 
	 //
	 $gesfec=substr($lastupdated,0,10);
	 $gesfecsis=$gesfec;
	 //
	 
	 switch($disposition)
	 {
	  case -2: 	//No contesta, 		Resultado: “No Contacto”, Justificación: “Nro no contesta”, Tipo de Contacto: “N”, Observacion: “”
		   $gesrid=54; $grjaid=12; $gtcid=5; $gesobs=' ';
		   break;
	  case -3:	//Buzon de voz, 	Resultado: “No Contacto”, Justificación: “Nro no contesta”, Tipo de Contacto: “N”, Observacion: “” 
		   $gesrid=54; $grjaid=12; $gtcid=5; $gesobs=' ';
		   break;
	  case -4:	//Ocupado, 			Resultado: “No Contacto”, Justificación: “Ocupado”, Tipo de Contacto: “B”, Observacion: “” 
		   $gesrid=54; $grjaid=50; $gtcid=7; $gesobs=' ';
		   break;
	  case -5:	//Congestion, 		Resultado: “No Contacto”, Justificación: “Nro no contesta”, Tipo de Contacto: “N”, Observacion: “” 
		   $gesrid=54; $grjaid=12; $gtcid=5; $gesobs=' ';
		   break;
	  case -6:	//Maquina de FAX, 	Resultado: “No Contacto”, Justificación: “Fax”, Tipo de Contacto: “N”, Observacion: “” 
		   $gesrid=54; $grjaid=52; $gtcid=5; $gesobs=' ';
		   break;
	  case -7:	//Nro desconectado,	Resultado: “No Contacto”, Justificación: “Nro fuera de servicio”, Tipo de Contacto: “I”, Observacion: “” 
		   $gesrid=54; $grjaid=11; $gtcid=4; $gesobs=' ';
		   break;
	 }
	 
	 //
	 $clitid='';
	 $query="SELECT clitid 
	 		 FROM clientes_telefono 
			 WHERE clitnumero='$phone'";
     $result1=pg_query($linkP,$query); 
     $num1=pg_num_rows($result1);
  	 for($j=0; $j<$num1; $j++) 
	 {
	  $row1=pg_fetch_row($result1);
	  $clitid=$row1[0];
	 }
	 //
	 $cliid='';
	 $query="SELECT cliid 
	         FROM clientes 
			 WHERE clinumdoc='$dni'";
     $result1=pg_query($linkP,$query); 
     $num1=pg_num_rows($result1);
  	 for($j=0; $j<$num1; $j++) 
	 {
	  $row1=pg_fetch_row($result1);
	  $cliid=$row1[0];
	 }
	 //
     $query="SELECT ctaid 
	         FROM cuentas 
			 WHERE ( (proid BETWEEN 955 AND 981) OR (proid BETWEEN 995 AND 998) OR (proid BETWEEN 1016 AND 1019) OR proid=1013 ) AND						 
			 ctagrupo != '0' AND
			 cliid = $cliid AND 			
			 perid = $periodo";
     $result2=pg_query($linkP,$query); 
     $num2=pg_num_rows($result2);
  	 for($k=0; $k<$num2; $k++) 
	 {
	  $row2=pg_fetch_row($result2);
	  $ctaid=$row2[0];
 	  //
	  $query="INSERT INTO gestiones (ctaid, gesfec, gesobs, gesfecsis, gesrid, negid,
	                                  gestest, giaid, clitid, gtcid, grjaid, gessigno) 
							 VALUES  ($ctaid, '$gesfec', '$gesobs', '$gesfecsis', $gesrid, $negid,
							          '$gestest', $giaid, $clitid, $gtcid, $grjaid, $gessigno)"; 
      $result0=pg_query($linkP,$query); 
	  //		  
	  $query="UPDATE $campana SET cb_datetime = CURRENT_TIMESTAMP WHERE id=$id"; 
      $result0= mysql_query($query,$linkM); 
	 }
	}
	echo "<br><br>Transferencia de data concluida.<br><br>"; 

	//liberamos el resultado
	pg_free_result($result);
	pg_free_result($result0);
	pg_free_result($result1);		
	pg_free_result($result2);	
	pg_close($link);							
?>
