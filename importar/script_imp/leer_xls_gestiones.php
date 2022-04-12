<?php
return false;
session_start();
ini_set('memory_limit', '-1');
set_time_limit(1800);
echo "<pre style='font-size:11px;'>";
require_once 'Excel/reader.php';
include 'ConnecADO.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read("gestiones1.xls");

foreach($data->sheets as $x => $y){//recorrido de las pestañas del archivo excel
            
	$name= strtolower($data->boundsheets[$x]['name']);
     //echo $data->boundsheets[$x]['name']."<br/><br/>";//nombre de las pestañas
	 
	$s=1;
	$l=1;
	$e=0;
	$gestiones=array();
	$telefonos=array();
	$direcciones=array();
	
    for ($i = 2,$h=1; $i <= $data->sheets[$x]['numRows']; $i++,$h++) {// defino $i=2 para q no tome la primera fila q es la cabecera
			    
                for ($j = 1; $j <= $data->sheets[$x]['numCols']; $j++) {
					//echo	$data->sheets[$x]['numCols'];
					//return false;
					if($j==$data->sheets[$x]['numCols']){
						
						$f=explode("/",$data->sheets[$x]['cells'][$i][2]);
						$fecha=$f[2]."-".$f[1]."-".$f[0];
						
						$fc=explode("/",$data->sheets[$x]['cells'][$i][7]);
						$fec_comp=$fc[2]."-".$fc[1]."-".$fc[0];
						
						$cl="select idcliente,idcuenta from cuentas where idcuenta='".$data->sheets[$x]['cells'][$i][1]."-1'";
						$cl=$db->Execute($cl);
						$idcliente=$cl->fields['idcliente'];
						$cta=$cl->fields['idcuenta'];
						
						if($cta==""){
							++$e;
						}
						
						$sl="select idtelefono from telefonos where telefono='".trim($data->sheets[$x]['cells'][$i][9])."' limit 0,1 ";
						$sl_f=$db->Execute($sl);
						$idfono=$sl_f->fields['idtelefono'];
						
						if($idfono==""){
							++$e;
							if(substr($data->sheets[$x]['cells'][$i][9],0,1)==9){$oriT=2;}else{$oriT=1;}
							$sql_f="insert into telefonos (idorigentelefono,idcliente,telefono) values(";
							$sql_f.="'$oriT','$idcliente','".$data->sheets[$x]['cells'][$i][9]."')";
							$db->Execute($sql_f);
						}
						if($cta!=""){
							++$a;
							$sql="insert into gestiones (idcuenta,idactividad,idresultado,idcontactabilidad,fecges,horges,feccomp,idtelefono,usureg) values";
							$sql.="('".$cta."',28,'".$data->sheets[$x]['cells'][$i][5]."',1,";
							$hora=round(($data->sheets[$x]['cells'][$i][3]*24), 0, PHP_ROUND_HALF_DOWN);
							$temp=($data->sheets[$x]['cells'][$i][3])-($hora/24);
							$minuto=round((($temp*24)*60), 0, PHP_ROUND_HALF_DOWN);

							if($minuto<= -1){
								$hora=$hora-1;
								$minuto=60+$minuto;
							}else if($minuto=="-0"){
								$minuto="00";
							}
							
							if(strlen($minuto)==1){
								$minuto="0".$minuto;
							}
							
							if(strlen($hora)==1){
								$hora="0".$hora;
							}
							$sl="select idtelefono from telefonos where telefono='".trim($data->sheets[$x]['cells'][$i][9])."' limit 0,1 ";
							$sl_f=$db->Execute($sl);
							$idfono=$sl_f->fields['idtelefono'];

							$sql.="'$fecha','$hora:$minuto:00','$fec_comp','$idfono','2') ";
							$gestiones[$a]=$sql;
						}
						
						if($data->sheets[$x]['cells'][$i][15]!=""){
							++$z;
							$codpto=substr($data->sheets[$x]['cells'][$i][19],0,2);
							$codprov=substr($data->sheets[$x]['cells'][$i][19],2,2);
							$coddist=substr($data->sheets[$x]['cells'][$i][19],4);
							$sql_d="insert into direcciones (idcuadrante,idorigendireccion,idcliente,direccion,coddpto,codprov,coddist) values(";
							$sql_d.="1,'1','$idcliente','".$data->sheets[$x]['cells'][$i][15]."','$codpto','$codprov','$coddist')";
							
							$direcciones[$z]=$sql_d;
						}
						if($data->sheets[$x]['cells'][$i][13]!=""){
							if(substr($data->sheets[$x]['cells'][$i][13],0,1)==9){$oriT=2;}else{$oriT=1;}
							++$t;
							$sql_f="insert into telefonos (idorigentelefono,idcliente,telefono) values(";
							$sql_f.="'$oriT','$idcliente','".$data->sheets[$x]['cells'][$i][13]."')";
							
							$telefonos[$t]=$sql_f;
						}
						
						$s++;
					}
				}
				
				//if($i==1) var_dump($dato);
								
	}
				
				//var_dump($gestiones);return false;
				$ok=true;
				$t=1;
				//$db->debug=true;
				$ist=0;$ups=0;$err=0;
				$db->StartTrans();
						for($i=1;$i<=count($direcciones);$i++){

								++$ist;
								$ok=$db->Execute($direcciones[$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
							
						}
				$db->CompleteTrans($ok);
				echo "Direcciones Nuevas | &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";
				//$db->debug=true;
				$ist=0;$ups=0;$err=0;
				$db->StartTrans();
						for($i=2;$i<=count($telefonos);$i++){
							
								++$ist;
								$ok=$db->Execute($telefonos[$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
						}
					
				$db->CompleteTrans($ok);
				echo "Telefonos Nuevos | &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";

				$ist=0;$ups=0;$err=0;
				$db->StartTrans();
						for($i=1;$i<=count($gestiones);$i++){
							
								++$ist;
								$ok=$db->Execute($gestiones[$i]);
								if($ok == false){
									$db->CompleteTrans(false);
									return false;
								}
						}
					
				$db->CompleteTrans($ok);
				echo "Gestiones| &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) <br/>";

					
	
}






?>
