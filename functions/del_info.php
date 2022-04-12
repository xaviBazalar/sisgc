<?php
session_start();
$iduser=$_SESSION['iduser'];
$name=$_GET['archivo'];
ini_set('memory_limit', '-1');
set_time_limit(1800);

require_once 'Excel/reader.php';
include 'ConnecADO.php';

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read($name);
error_reporting(E_ALL ^ E_NOTICE);
$sql= array ();
$w=0;
	echo "<font color='red'>Inicio de importacion :</font>".$ti=date("H:i:s")."<br/>";


	echo "<pre>";								  		
$d_nr=0;
$d_nr_x=0;		
$s=1;		
$vali= array();
		
foreach($data->sheets as $x => $y){//recorrido de las pestañas del archivo excel
            
           $n_tabla=  strtolower($data->boundsheets[$x]['name']);//definimos nombre de pestaña
 //------------------------------------------------------------------------
	$name= strtolower($data->boundsheets[$x]['name']);
	
	
        echo "Datos Importados : ".$data->boundsheets[$x]['name']."<br/>";//nombre de las pestañas
       // echo "<table border='1'style='font-size:12px;'>";
				
									
               for ($i = 2,$h=1,$c=2; $i <= $data->sheets[$x]['numRows']; $i++,$h++,$c++) {// defino $i=2 para q no tome la primera fila q es la cabecera
						
					if($name=="clientes"){
					
                        for ($j = 1; $j <= $data->sheets[$x]['numCols']; $j++) {
								
								$db->Execute("select * from usuarios");
								/*if($j==1){
								
								
								
								$idcli=$data->sheets[$x]['cells'][$i][$j];
								$sql="DELETE g FROM gestiones g,cuentas c,clientes cl
								WHERE g.idcuenta=c.idcuenta AND c.idcartera='10' 
								AND cl.idcliente='$idcli'";
								$db->Execute($sql);
							
								$sql="DELETE cp FROM cuenta_periodos cp,cuentas c,clientes cl
								WHERE c.idcuenta=cp.idcuenta AND c.idcartera='10' 
								AND cl.idcliente='$idcli'";
								$db->Execute($sql);
								
								$sql="DELETE cp FROM cuenta_pagos cp,cuentas c,clientes cl
								WHERE c.idcuenta=cp.idcuenta AND c.idcartera='10' 
								AND cl.idcliente='$idcli'";
								$db->Execute($sql);
								
								$sql="DELETE t FROM tareas t,cuentas c,clientes cl
								WHERE c.idcliente=cl.idcliente AND cl.idcliente=t.idcliente AND c.idcartera='10' 
								AND cl.idcliente='$idcli'";
								$db->Execute($sql);
								
								$sql="DELETE c FROM cuentas c,clientes cl
								WHERE c.idcliente=cl.idcliente AND c.idcartera='10' 
								AND cl.idcliente='$idcli'";
								$db->Execute($sql);
								continue;
								}

								if($j!=1){
									continue;
								}*/
								
						}
						
					}

	
				}
        //echo "</table>";
	
	$w++;
}
	echo $d_nr."<br>";
		echo $d_nr_x;
			echo "<font color='red'>Inicio de importacion :</font>".$ti=date("H:i:s")."<br/>";

?>
