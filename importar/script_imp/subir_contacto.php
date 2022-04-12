<?php

ini_set('memory_limit', '-1');
set_time_limit(1800);
require_once 'Excel/reader.php';
include 'ConnecADO.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$archivo=$_GET['archivo'];
if($archivo==""){return false;}
$data->read('correos clientes.xls');
error_reporting(E_ALL ^ E_NOTICE);
//$db->debug=true;
$w=0;


$periodo=$_GET['periodo'];
$db->debug=true;
	//echo "<font color='red'>Inicio de importacion :</font>".$ti=date("H:i:s")."<br/>";
//echo $ti;
	//echo "<pre>";
//$fp = fopen('verif.txt', 'w');
//fwrite($fp, 'importando');
//fclose($fp);

	echo "<pre>";								  		
$d_nr=0;
				$d_nr_x=0;	
$db->StartTrans();									
foreach($data->sheets as $x => $y){//recorrido de las pestañas del archivo excel
            
           $n_tabla=  strtolower($data->boundsheets[$x]['name']);//definimos nombre de pestaña
 //------------------------------------------------------------------------
	$name= strtolower($data->boundsheets[$x]['name']);
	
	
        //echo "Datos Importados : ".$data->boundsheets[$x]['name']."<br/>";//nombre de las pestañas
       // echo "<table border='1'style='font-size:12px;'>";
		 echo $name;		
            	
               for ($i = 2,$h=1,$c=2; $i <= $data->sheets[$x]['numRows']; $i++,$h++,$c++) {// defino $i=2 para q no tome la primera fila q es la cabecera
			   	
			
					
							
                        for ($j = 1; $j <= $data->sheets[$x]['numCols']; $j++) {
							
							if($name=="hoja1"){ 
								
								if($data->sheets[$x]['cells'][$i][1]==""){
									//break;
								}
								
								if($j==2){
									$sql="Insert into contactos(idcliente,idparentesco,email,contacto,area) VALUES (";
									$sql.="'".$data->sheets[$x]['cells'][$i][$j]."','8',";
									continue;
								}
								
								if($j==3){
									$sql.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
									continue;
								}
								
								if($j==4){
									$sql.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
									continue;
								}
								
								if($j==5){
									switch($data->sheets[$x]['cells'][$i][$j]){
										case "Contabilidad":
											$sql.="'C')";
											break;
										case "Pago a Proveedores":
											$sql.="'PP')";
											break;
										case "Gerencia":
											$sql.="'G')";
											break;
										case "Tesoreria":
											$sql.="'T')";
											break;
										case "Ninguno":
											$sql.="'N')";
											break;
										case "Recepcion":
											$sql.="'R')";
											break;
											
									
									}
									
									
									$ok=$db->Execute($sql);
									if($ok){
										$error=true;	
									}else{
										$error=false;
										
									}
									continue;
								}
					
							}

						}
						
		
		

						
                    //echo "</tr>";
				}
        //echo "</table>";

	$w++;
}
$db->debug=true;
	//echo $d_nr."<br>";
		//echo $d_nr_x;
$db->CompleteTrans($error);
$db->Close();
?>
