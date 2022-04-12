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
$up_cta_pg=array();
	$s=1;
    for ($i = 1,$h=1; $i <= $data->sheets[$x]['numRows']; $i++,$h++) {// defino $i=2 para q no tome la primera fila q es la cabecera
			    
				if($i==1){				
					$fecha=array();
				}
                for ($j = 1; $j <= 1; $j++) {
						
						if($i==1 and $j>=10){
							$fecha[$j]=substr($data->sheets[$x]['cells'][$i][2],0,4)."-".substr($data->sheets[$x]['cells'][$i][2],4,2)."-".substr($data->sheets[$x]['cells'][$i][$j],6);
						}else if($i==1){
							$fecha[$j]=$data->sheets[$x]['cells'][$i][$j];
						}else{
							$fecha[$j]=substr($data->sheets[$x]['cells'][$i][2],0,4)."-".substr($data->sheets[$x]['cells'][$i][2],4,2)."-".substr($data->sheets[$x]['cells'][$i][2],6);

							if($j>=1){
								if($data->sheets[$x]['cells'][$i][$j]!="NULL" and $data->sheets[$x]['cells'][$i][$j]!="0"){
									/*
									  3 => string 'NUM_CTA' (length=7)
									  6 => string 'EFECTIVIDAD' (length=11)
									  7 => string 'PARA GESTION' (length=12)
									  8 => string 'TIPO_PAGO' (length=9)
									  9 => string 'TOT_PAGOS' (length=9)*/
									  
									$flag="select idcuenta from cuenta_pagos where idcuenta='".trim($data->sheets[$x]['cells'][$i][3])."-1' and idperiodo='$id_periodo' and fecpag='".$fecha[$j]."' and imptot=".$data->sheets[$x]['cells'][$i][9];
									$flag_cta[$s]=$flag;
									
									if($data->sheets[$x]['cells'][$i][6]=="SI PAGO"){
										++$w;
										$up_cta[$w]="Update cuenta_periodos set idestado=0 where idcuenta='".$data->sheets[$x]['cells'][$i][3]."-1' and idperiodo=$id_periodo   ";
										
									}

									if($data->sheets[$x]['cells'][$i][6]=="NO PAGO"){
										++$z;
										$up_cta_n[$z]="Update cuenta_periodos set idestado=1 where idcuenta='".$data->sheets[$x]['cells'][$i][3]."-1' and idperiodo=$id_periodo and usureg=$id_periodo ";
										
									}

									++$o;
									$up_cta_pg[$o]="update cuenta_pagos set fecreg='".date("Y-m-d H:i:s")."',observacion='".$data->sheets[$x]['cells'][$i][6]."*".$data->sheets[$x]['cells'][$i][7]."*".$data->sheets[$x]['cells'][$i][8]."*".$data->sheets[$x]['cells'][$i][9]."' where idcuenta='".trim($data->sheets[$x]['cells'][$i][3])."-1' and idperiodo='$id_periodo' -- and fecpag='".$fecha[$j]."' and imptot=".$data->sheets[$x]['cells'][$i][$j]."";
									//if($data->sheets[$x]['cells'][$i][3]==7879577){ echo $o."-".$up_cta_pg[$o];}

									
									$dato[$s]="insert into cuenta_pagos (idcuenta,idperiodo,fecpag,imptot,observacion) 
									values('".trim($data->sheets[$x]['cells'][$i][3])."-1',$id_periodo,'".$fecha[$j]."',".$data->sheets[$x]['cells'][$i][9].",'".$data->sheets[$x]['cells'][$i][6]."*".$data->sheets[$x]['cells'][$i][7]."*".$data->sheets[$x]['cells'][$i][8]."*".$data->sheets[$x]['cells'][$i][9]."')";
													
				
									++$s;
									
								}else{
									if($data->sheets[$x]['cells'][$i][6]=="SI PAGO"){
										++$w;
										$up_cta[$w]="Update cuenta_periodos set idestado=0 where idcuenta='".$data->sheets[$x]['cells'][$i][3]."-1' and idperiodo=$id_periodo ";
										
									}

									if($data->sheets[$x]['cells'][$i][6]=="NO PAGO"){
										++$z;
										$up_cta_n[$z]="Update cuenta_periodos set idestado=1 where idcuenta='".$data->sheets[$x]['cells'][$i][3]."-1' and idperiodo=$id_periodo and usureg=$id_periodo ";
										
									}

									++$o;
									
									$up_cta_pg[$o]="update cuenta_pagos set fecreg='".date("Y-m-d H:i:s")."',observacion='".$data->sheets[$x]['cells'][$i][6]."*".$data->sheets[$x]['cells'][$i][7]."*".$data->sheets[$x]['cells'][$i][8]."*".$data->sheets[$x]['cells'][$i][9]."' where idcuenta='".trim($data->sheets[$x]['cells'][$i][3])."-1' and  idperiodo='$id_periodo' -- and fecpag='".$fecha[$j]."' ";
									//if($data->sheets[$x]['cells'][$i][3]==7879577){ echo $o."-".$up_cta_pg[$o];}
									
								}	
							}
							
							
						}
				}
				//var_dump($fecha);
								
	}
	$ok=true;
				$t=1;
				//echo count($up_cta_n)."<br>";
				//echo count($up_cta)."<br>";
				//$db->debug=true;
				$db->StartTrans();
						for($i=1;$i<=count($up_cta_n);$i++){
								
								$ok==$db->Execute($up_cta_n[$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
								
								

						}
				//$db->debug=false;	
				
				$db->CompleteTrans($ok);

				//$db->debug=true;
				$ist=0;$ups=0;$err=0;
				$db->StartTrans();
						for($i=1;$i<=count($up_cta);$i++){
								
								$ok==$db->Execute($up_cta[$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
								
								

						}
				//$db->debug=false;	
				$db->CompleteTrans($ok);
				//return false;
				$ist=0;$ups=0;$err=0;
				//$db->debug=true;
				$db->StartTrans();
						for($i=1;$i<=count($up_cta_pg);$i++){
							$flag=$db->Execute($flag_cta[$i]);
								//++$ups;
								
								$ok=$db->Execute($up_cta_pg[$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
								

							}						
				//$db->debug=true;	
						for($i=1;$i<=count($dato);$i++){
							$flag=$db->Execute($flag_cta[$i]);
							if($flag->fields['idcuenta']!=""){
								++$ups;
								
								$ok=$db->Execute($up_cta_pg[$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}

							}else{
								
								++$ist;
								$ok=$db->Execute($dato[$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
								
							}
						}
					
				$db->CompleteTrans($ok);
				echo "Cuentas Pagos | &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
				
}



?>
