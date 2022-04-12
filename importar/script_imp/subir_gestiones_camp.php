<?php
//return false;
ini_set('memory_limit', '-1');
set_time_limit(1800);
require_once 'Excel/reader.php';
include 'ConnecADO.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$periodo=$_GET['periodo'];

$archivo=$_GET['archivo'];

if($archivo==""){return false;}
$data->read($archivo);
error_reporting(E_ALL ^ E_NOTICE);
$db->debug=true;
$w=0;

	//echo "<font color='red'>Inicio de importacion :</font>".$ti=date("H:i:s")."<br/>";
//echo $ti;
	//echo "<pre>";
//$fp = fopen('verif.txt', 'w');
//fwrite($fp, 'importando');
//fclose($fp);
$db->StartTrans();
	echo "<pre>";								  		
$d_nr=0;
				$d_nr_x=0;										
foreach($data->sheets as $x => $y){//recorrido de las pestañas del archivo excel
            
           $n_tabla=  strtolower($data->boundsheets[$x]['name']);//definimos nombre de pestaña
 //------------------------------------------------------------------------
	$name= strtolower($data->boundsheets[$x]['name']);
	var_dump($data->sheets[$x]['numRows']);
	
        echo "Datos Importados : ".$data->boundsheets[$x]['name']."<br/>";//nombre de las pestañas
       // echo "<table border='1'style='font-size:12px;'>";
		//echo 	$data->sheets[$x]['numRows'];	
              
               for ($i = 2,$h=1,$c=2; $i <= $data->sheets[$x]['numRows']; $i++,$h++,$c++) {// defino $i=2 para q no tome la primera fila q es la cabecera
				
							
                        for ($j = 1; $j <= $data->sheets[$x]['numCols']; $j++) {
								
							
							if($name=="gestiones"){ 
								
								if($data->sheets[$x]['cells'][$i][1]==""){
									//break;
								}
								
								if($j==1){
									$sql="Insert into gestiones(idfuente,idagente,fecges,horges,idresultado,impcomp,feccomp,iddireccion,idcontactabilidad,idubicabilidad,
									idpredio,idmaterial_predio,idnro_pisos,idcolor_pared,observacion,usureg,idactividad,idcuenta,idestado) values(";
									 /*$objPHPExcel->setActiveSheetIndex(0)
											->setCellValue('a'.$c, $data->sheets[$x]['cells'][$i][$j]);*/
									//echo $data->sheets[$x]['cells'][$i][$j]."-";
									$sql.="1,'".$data->sheets[$x]['cells'][$i][$j]."',";
									
									continue;
								}
								
								
								if($j==3){
									$pos = strpos($data->sheets[$x]['cells'][$i][$j], "/");
									if($pos){
										$fecs = explode("/",$data->sheets[$x]['cells'][$i][$j]);
										$fecs = $fecs[2]."-".$fecs[1]."-".$fecs[0];
										$sql.="'".$fecs."',";
									}else{
										$sql.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									}
									
									
									/*$objPHPExcel->setActiveSheetIndex(0)
											->setCellValue('b'.$c, $idfo);*/
									//	echo $idfo."-";
									//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==4){
									$sql.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
										//echo $iddi."";
									//echo "<br/>";
									//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==5){
									$sql.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
										//echo $iddi."";
									//echo "<br/>";
									//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==6){
									$sql.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
										//echo $iddi."";
									//echo "<br/>";
									//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==7){
									$sql.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
										//echo $iddi."";
									//echo "<br/>";
									//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								if($j==8){
									$sql.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
										//echo $iddi."";
									//echo "<br/>";
									//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								if($j==9){
									$sql.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
									echo $sql;
										//echo $iddi."";
									//echo "<br/>";
									//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==10){
									$sql.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
										//echo $iddi."";
									//echo "<br/>";
									//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==11){
									$sql.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
										//echo $iddi."";
									//echo "<br/>";
									//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==12){
									$sql.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
										//echo $iddi."";
									//echo "<br/>";
									//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==13){
									$sql.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
										//echo $iddi."";
									//echo "<br/>";
									//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==14){
									$sql.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
										//echo $iddi."";
									//echo "<br/>";
									//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									continue;
								}
								
								if($j==15){
									$sql.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."',";
										//echo $iddi."";
									//echo "<br/>";
									//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";
									

									continue;
									
								}
								
											
								
								
								
														
								if($j==16){
									$sql.="'".addslashes($data->sheets[$x]['cells'][$i][$j])."','4',";
									$idcliente=$data->sheets[$x]['cells'][$i][2];
									$cart=$data->sheets[$x]['cells'][$i][17];
								
										
										
										$rs=$db->Execute("SELECT c.idcuenta FROM cuentas c 
															JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta
															WHERE c.idcliente='$idcliente' AND cp.idperiodo='$periodo' and c.idcartera='$cart'");
										$p=1;										
										while(!$rs->EOF){
											if($p==1){
												$sql2=$sql;
											}
											$sql.="'".$rs->fields['idcuenta']."',0)";
											$sql3=$sql2." '".$rs->fields['idcuenta']."',0)";
											//echo $sql."<br>";
											//return false;
											$ok=$db->Execute($sql3);
											$p++;
											
											if($ok){++$d_nr;}
											if(!$ok){ $error=false;}else{$error=true;}

											//echo $rs->fields['idcuenta'];
										//	echo $rs->fields['iddireccion'].",";
											$rs->MoveNext();
										}
											
									
									//echo "<br>";
										//echo $iddi."";
									//echo "<br/>";

									//$query3.="'".$data->sheets[$x]['cells'][$i][$j]."',";

									continue;
								

								}

								
							}

						}
						
		
		

						
                    //echo "</tr>";
				}
        //echo "</table>";
	//echo $w;
	$w++;
}
	echo $d_nr."<br>";
		echo $d_nr_x;
$db->CompleteTrans($error);
$db->Close();
?>
