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
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read($name);

foreach($data->sheets as $x => $y){//recorrido de las pestañas del archivo excel
            
	$name= strtolower($data->boundsheets[$x]['name']);
    //echo $data->boundsheets[$x]['name'];//nombre de las pestañas
	$dato=array();
	$dato_up=array(); 
	$flag_cta=array(); 
	$up_cta=array();
	$s=1;
    for ($i = 2,$h=1; $i <= $data->sheets[$x]['numRows']; $i++,$h++) {// defino $i=2 para q no tome la primera fila q es la cabecera

                for ($j = 1; $j <= 5; $j++) {
					if($data->sheets[$x]['cells'][$i][1]==""){break;}
					

					$impmin=$data->sheets[$x]['cells'][$i][3];
					$imptot=$data->sheets[$x]['cells'][$i][4];			
					
					$flag="select idcuenta from cuenta_periodos where idcuenta='".trim($data->sheets[$x]['cells'][$i][1])."-1' and idperiodo=".$id_periodo;
					$flag_cta[$s]=$flag;
					$up_cta[$s]="Update cuenta_periodos set impmin='".$impmin."',imptot='".$imptot."' where idcuenta='".trim($data->sheets[$x]['cells'][$i][1])."-1' and idperiodo=".$id_periodo;
					$s++;
									
									
							
							
							
						
				}
				//var_dump($fecha);
								
	}
	$ok=true;
				$t=1;
				//$db->debug=true;
			
				$ist=0;$ups=0;$err=0;
				$db->StartTrans();
						for($i=1;$i<=count($up_cta);$i++){
							$flag=$db->Execute($flag_cta[$i]);
							if($flag->fields['idcuenta']!=""){
								++$ups;
								//echo $up_cta[$i]."<br/>";
								$ok==$db->Execute($up_cta[$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
							}else{
								
								
							}
						}
					
				$db->CompleteTrans($ok);
				echo "Cuentas Detalles Pagos| &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
				
}



?>
