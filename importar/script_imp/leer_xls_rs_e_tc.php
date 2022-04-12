<?php
//return false;
session_start();
ini_set('memory_limit', '-1');
set_time_limit(1800);
echo "<pre style='font-size:11px;'>";
require_once 'Excel/reader.php';
include 'ConnecADO.php';
$name=$_GET['archivo'];
$id_periodo=$_SESSION['periodo'];
$idcartera=$_GET['idcartera'];
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read($name);

foreach($data->sheets as $x => $y){//recorrido de las pestañas del archivo excel
            
	$name= strtolower($data->boundsheets[$x]['name']);
     //echo $data->boundsheets[$x]['name']."<br/><br/>";//nombre de las pestañas
	// $db->Execute("UPDATE cuentas c, cuenta_periodos cp SET cp.idestado=0,cp.usureg='22' WHERE c.idcuenta=cp.idcuenta AND cp.idestado=1 AND cp.idperiodo=$id_periodo AND c.idcartera=51");
 	//sleep(60);
	$s=1;
	$l=1;
	$gestiones=array();
	$flag_gestiones=array(); 
	$update_gestiones=array();
	$fecha_g=date("Y-m-d");
	$hora_g=date("H:i:s");
    for ($i = 2,$h=1; $i <= $data->sheets[$x]['numRows']; $i++,$h++) {// defino $i=2 para q no tome la primera fila q es la cabecera
			    
                for ($j = 1; $j <= $data->sheets[$x]['numCols']; $j++) {
					if($j==$data->sheets[$x]['numCols']){
						if($data->sheets[$x]['cells'][$i][1]==""){continue;}
																
								$flag_gestiones[0][$s]="select idcuenta from rs_entrega_tc where idcuenta='".trim($data->sheets[$x]['cells'][$i][1])."-1' and idperiodo=$id_periodo";
								$update_gestiones[0][$s]="update rs_entrega_tc 
													set resultado='".trim($data->sheets[$x]['cells'][$i][2])."',
														motivo='".trim($data->sheets[$x]['cells'][$i][3])."',
														q_atiende='".trim($data->sheets[$x]['cells'][$i][4])."',
														descripcion='".trim($data->sheets[$x]['cells'][$i][5])."',
														obs='".trim($data->sheets[$x]['cells'][$i][6])."'
													where idcuenta='".trim($data->sheets[$x]['cells'][$i][1])."-1' and idperiodo=$id_periodo";

								$sql="INSERT INTO sis_gestion.rs_entrega_tc 
										( 
										idcuenta, 
										idperiodo, 
										resultado, 
										motivo, 
										q_atiende, 
										descripcion, 
										obs
										)
										VALUES
										( 
										'".trim($data->sheets[$x]['cells'][$i][1])."-1', 
										'$id_periodo', 
										'".trim($data->sheets[$x]['cells'][$i][2])."', 
										'".trim($data->sheets[$x]['cells'][$i][3])."', 
										'".trim($data->sheets[$x]['cells'][$i][4])."', 
										'".trim($data->sheets[$x]['cells'][$i][5])."', 
										'".trim($data->sheets[$x]['cells'][$i][6])."'
										);
									";
								$gestiones[$s]=$sql;
								++$s;	
					}
				}

								
	}
				//var_dump($gestiones);
				//return false;
				$ok=true;
				$t=1;
				//$db->debug=true;
				$ist=0;$ups=0;$err=0;
				$db->StartTrans();
						for($i=1;$i<=count($gestiones);$i++){
							$flag=$db->Execute($flag_gestiones[0][$i]);
							if($flag->fields['idcuenta']==""){
								++$ist;
								$ok=$db->Execute($gestiones[$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
							}else{
								++$ups;
								$ok=$db->Execute($update_gestiones[0][$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
							}
						}
					
				$db->CompleteTrans($ok);
				echo "<b style='font-size:14px;'><center>Resultado Entrega TC </center></br> &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) </b><br/><hr/>";
				return false;
	
}






?>
