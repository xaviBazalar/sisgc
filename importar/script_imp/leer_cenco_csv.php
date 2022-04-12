<?php
	ini_set('memory_limit', '-1');
	session_start();
	set_time_limit(1800);
	echo "<pre style='font-size=8px;'>";
	$n=0;
	$flag=0;
	include 'ConnecADO.php';
	$name=$_GET['archivo'];
	
	$name_csv=str_replace("zip","csv",$name);
	$name_csv=str_replace("_"," ",$name_csv);
	//$db->debug=true;
	$id_periodo=$_SESSION['periodo'];
	$idcartera=$_GET['idcartera'];
	
	/*****************************************************************************/
	/*
	include('pclzip.lib.php');

	//forma de llamar la clase
	 $archive = new PclZip($name);

	//Ejecutamos la funcion extract

	  if ($archive->extract(PCLZIP_OPT_PATH, 'data',
							PCLZIP_OPT_REMOVE_PATH, 'temp_install') == 0) {
		die("Error : ".$archive->errorInfo(true));
	  }
	  */
	 /*****************************************************************************/


	 
	function fecha($x){
	/*Formato mmddyyyy a yyyy-mm-dd*/
			$ano=substr($x,4,4);
			$mes=substr($x,0,2);
			$dia=substr($x,2,2);
			
			$fecha=(string)$ano."-".$mes."-".$dia;
			
			return $fecha;
	}
	
	$archivo="data/$name_csv";
	$archivo="20130201_ALTO_CONTACTO.csv";
	
	//$fp = fopen("/var/www/html/gestion/cencosud_asignacion/rem_AE1".date("Ymd").".txt", 'r') or die("Error");
	$fp = fopen($archivo, 'r') or die("Error");

	$ctas= array();
	$ls="";

	//$db->Execute("UPDATE cuentas c, cuenta_periodos cp SET cp.idestado=0,cp.usureg='22' WHERE c.idcuenta=cp.idcuenta AND cp.idestado=1 AND cp.idperiodo=$id_periodo AND c.idcartera=$idcartera ");
	//sleep(35);
	
	$s=1;
	$l=1;
	$direcciones= array();
	$clientes=array();
	$telefonos=array();
	$cuentas=array();
	$cuenta_p=array();
	$flag_cta=array(); 
	$update_cta=array();
	
	function utf8($str){
		 $stri=mb_convert_encoding($str, "UTF-8");
		 $stri=trim($stri);
		 return str_replace("'","",$stri);
	}

	function bin8($st){
			if(strpos($st,"000000")){
				return "";
			}else{ 
				$fo=addslashes($st);
				$fon=str_replace("\\0","",$fo);
				return $fon;
			}
	}
	
	function lp2($str){
		$d1=trim($str);
		$d1=addslashes($d1);
		$d1=str_replace("\\0","",$d1);
	//	$d1=str_replace("0","",$d1);
		$d1=str_replace("NULL","",$d1);
		$d1=str_replace("-"," ",$d1);
		return $d1;
	}
	while (!feof($fp))
	{
		++$n;
			
			$linea=fgets($fp);
			$dt=explode(",",$linea);
			//---------------------------------------------
			$idcliente=trim($dt[9]);
			$cli=$dt[10];
			//$cliente=mb_convert_encoding($dt[10],'UTF-8', 'UTF-16LE' );
			if($n==1){continue;}
			if($idcliente==""){continue;}

			$str = mb_convert_encoding($cli, "UTF-8");
			$flag_cta[0][$s]="select idcliente from clientes where idcliente='".$idcliente."'";
			$update_cta[0][$s]="update clientes set cliente='".utf8($dt[10])."' where idcliente='".$idcliente."'";
			
			$clientes[$s]="INSERT INTO clientes (iddoi,idpersoneria,idcliente,cliente,observacion)
			values(1,1,'".$idcliente."','".utf8($dt[10])."','".$dt[0]."-".$dt[2]."-".$dt[5]."')";
			//--------------------------------------------------------	
			//$db2->debug=true;
			$id_dir=$db->Execute("Select coddpto,codprov,coddist from ubigeos where nombre='".utf8($dt[32])."' and nombre2='".utf8($dt[33])."' and nombre3='".utf8($dt[34])."' ");
			//$db2->debug=false;
			$coddpto=$id_dir->fields['coddpto'];
			$codprov=$id_dir->fields['codprov'];
			$coddist=$id_dir->fields['coddist'];
			///echo $d_dpd->fields['coddpto']."-".$codprov."-".$coddist."<br/>";
			$flag_cta[1][$s]="select idcliente from direcciones where idcliente='".$idcliente."' and direccion='".utf8($dt[29])."'";
			$update_cta[1][$s]="update direcciones set coddpto='$coddpto',codprov='$codprov',coddist='$coddist',observacion='".utf8($dt[38])."**".$dt[34]."' where idcliente='".$idcliente."' and direccion='".utf8($dt[29])."'";
			$direcciones[$s]="INSERT INTO direcciones (idfuente,idcuadrante,idorigendireccion,idcliente,direccion,coddpto,codprov,coddist,observacion)
			values (1,1,1,'".$idcliente."','".utf8($dt[29])."',
			'$coddpto','$codprov','$coddist','".utf8($dt[38])."**".utf8($dt[34])."')
			";
			//--------------------------------------------------------	
			
			$fono_1=bin8($dt[35]);
			$fono_2=bin8($dt[36]);
			$fono_3=bin8($dt[40]);
			$fono_4=bin8($dt[41]);
			$fono_5=bin8($dt[47]);
			$fono_6=bin8($dt[48]);
			
			if($fono_3!=""){
				$fon=str_replace("0000000","",$fono_3);
				$fon=str_replace("000000","",$fono_3);
				$fon=str_replace("00000","",$fono_3);
				$fon=str_replace("0000","",$fono_3);
				$fono_3=$fon;
			}
			if($fono_4!=""){
				$fon=str_replace("0000000","",$fono_4);
				$fon=str_replace("000000","",$fono_4);
				$fon=str_replace("00000","",$fono_4);
				$fon=str_replace("0000","",$fono_4);
				$fono_4=$fon;
			}
			
			/*if(strpos($fono_4,"000000")){
				$fo=bin8($dt[41]);
				$fon=substr($fo,3);
				echo var_dump($fon)."<br/>";
			}*/
			
			if($fono_1!="" and !strpos($fono_1,"L")){
				if(!strpos($fono_1,"000000")){
					if(strpos($dt[35],"+")){
						$fono_1=substr($fono_1,3);
					}
					if($fono_1==9 and strlen($fono_1)<9){$nit="9";}else{$nit="";}
					
					if(substr($fono_1,0,1)==9){$oriT=5;}else{$oriT=2;}
					
					$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$idcliente."' and telefono='".$nit.$fono_1."'";
					$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$idcliente."' and telefono='".$nit.$fono_1."'";
					$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
					values (1,$oriT,'".$idcliente."','".$nit.$fono_1."','')";
					++$l;
				}
			}
			
			if($fono_2!="" and !strpos($fono_2,"L")){
				if(!strpos($fono_2,"000000")){
					if(strpos($dt[36],"+")){
						$fono_2=substr($fono_2,3);
					}
					if($fono_2==9 and strlen($fono_2)<9){$nit="9";}else{$nit="";}
					
					if(substr($fono_2,0,1)==9){$oriT=5;}else{$oriT=2;}
					
					$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$idcliente."' and telefono='".$nit.$fono_2."'";
					$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$idcliente."' and telefono='".$nit.$fono_2."'";
					$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
					values (1,$oriT,'".$idcliente."','".$nit.$fono_2."','')";
					++$l;
				}
			}
			
			if($fono_3!="" and !strpos($fono_3,"L")){
				if(!strpos($fono_3,"000000")){
					if(strpos($dt[40],"+")){
						$fono_3=substr($fono_3,3);
					}
					if($fono_3==9 and strlen($fono_3)<9){$nit="9";}else{$nit="";}
					
					if(substr($fono_3,0,1)==9){$oriT=6;}else{$oriT=3;}
					
					$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$idcliente."' and telefono='".$nit.$fono_3."'";
					$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$idcliente."' and telefono='".$nit.$fono_3."'";
					$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
					values (1,$oriT,'".$idcliente."','".$nit.$fono_3."','')";
					++$l;
				}
			}
			
			if($fono_4!="" and !strpos($fono_4,"L")){
				if(!strpos($fono_4,"000000")){
					if(strpos($dt[41],"+")){
						$fono_4=substr($fono_4,3);
					}
					if($fono_4==9 and strlen($fono_4)<9){$nit="9";}else{$nit="";}
					
					if(substr($fono_4,0,1)==9){$oriT=6;}else{$oriT=3;}
					
					$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$idcliente."' and telefono='".$nit.$fono_4."'";
					$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$idcliente."' and telefono='".$nit.$fono_4."'";
					$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
					values (1,$oriT,'".$idcliente."','".$nit.$fono_4."','')";
					++$l;
				}
			}

			if($fono_5!="" and !strpos($fono_5,"L")){
				if(!strpos($fono_5,"000000")){
					if(strpos($dt[47],"+")){
						$fono_5=substr($fono_5,3);
					}
					if($fono_5==9 and strlen($fono_5)<9){$nit="9";}else{$nit="";}
					
					if(substr($fono_5,0,1)==9){$oriT=5;}else{$oriT=2;}
					
					$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$idcliente."' and telefono='".$nit.$fono_5."'";
					$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$idcliente."' and telefono='".$nit.$fono_5."'";
					$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
					values (1,$oriT,'".$idcliente."','".$nit.$fono_5."','')";
					++$l;
				}
			}
			
			if($fono_6!="" and !strpos($fono_6,"L")){
				if(!strpos($fono_6,"000000")){
					if(strpos($dt[48],"+")){
						$fono_6=substr($fono_6,3);
					}
					if($fono_6==9 and strlen($fono_6)<9){$nit="9";}else{$nit="";}
					
					if(substr($fono_6,0,1)==9){$oriT=5;}else{$oriT=2;}
					
					$flag_cta[2][$l]="select idcliente from telefonos where idcliente='".$idcliente."' and telefono='".$nit.$fono_6."'";
					$update_cta[2][$l]="update telefonos set idorigentelefono=$oriT where idcliente='".$idcliente."' and telefono='".$nit.$fono_6."'";
					$telefonos[$l]="INSERT INTO telefonos (idfuente,idorigentelefono,idcliente,telefono,observacion)
					values (1,$oriT,'".$idcliente."','".$nit.$fono_6."','')";
					++$l;
				}
			}
			//--------------------------------------------------------	
		
			$fecs = substr(bin8($dt[22]),0,4)."-".substr(bin8($dt[22]),4,2)."-".substr(bin8($dt[22]),6,2);
			$id_cta=trim($dt[2]);
			$id_cta2=trim($dt[3]);
			$clasi=trim($dt[18]);
			$clasi=addslashes($clasi);
			$clasi=str_replace("\\","",$clasi);
			$clasi=str_replace("0","",$clasi);
			$clasi=str_replace("NULL","",$clasi);

			
			$flag_cta[3][$s]="select idcuenta from cuentas where idcuenta='".$id_cta."-1'  -- and idcliente='".$idcliente."'  ";
			$update_cta[3][$s]="update cuentas set idcartera=$idcartera,idcliente='".$idcliente."',obs2='".$clasi."**".$fecs."', idproducto=1,idtipocartera=1 where idcuenta='".$id_cta."-1'";
			$cuentas[$s]="INSERT INTO cuentas (idmoneda,idproducto,idcartera,idusuario,idtipocartera,idcliente,idcuenta,obs2)
				values (1,1,$idcartera,2,1,'".$idcliente."','".$id_cta."-1','".$clasi."**".$fecs."');
				";
			//-------------------------------------------
			
			if($data->sheets[$x]['cells'][$i][41]=="Riesgo Alto"){$riesgo=1;}
			if($data->sheets[$x]['cells'][$i][41]=="Riesgo Medio"){$riesgo=2;}
			if($data->sheets[$x]['cells'][$i][41]=="Riesgo Bajo"){$riesgo=3;}

			if((strtoupper($data->sheets[$x]['cells'][$i][41]))=="ALTO"){$riesgo=1;}
			if((strtoupper($data->sheets[$x]['cells'][$i][41]))=="MEDIO"){$riesgo=2;}
			if((strtoupper($data->sheets[$x]['cells'][$i][41]))=="BAJO"){$riesgo=3;}

			$sl="select idcuenta,idusuario from cuenta_periodos where idcuenta='".$id_cta."-1' and idperiodo=".($id_periodo-1);
			$id_usureg=$db->Execute($sl);
			
			if($id_usureg->fields['idusuario']!=""){
				$idusu=$id_usureg->fields['idusuario'];
			}else{
				$idusu=2;
			}

			
			//$fecven=substr($data->sheets[$x]['cells'][$i][6],0,4)."-".substr($data->sheets[$x]['cells'][$i][6],4,2)."-".substr($data->sheets[$x]['cells'][$i][6],6);
			$fecven=$fecs;
			$fl_dm=$db->Execute("SELECT DATEDIFF(NOW(),'$fecven') diasmora");
			$flag_cta[4][$s]="select idcuenta from cuenta_periodos where idcuenta='".$id_cta."-1' and idperiodo=$id_periodo";

			$tot_ct_s2=$db->Execute("select idcuenta from cuenta_periodos where idcuenta='".lp2($id_cta2)."-1' and idperiodo=$id_periodo");

			
	
				$tot_ct_s=$db->Execute("select idcuenta from cuenta_periodos where idcuenta='".lp2($id_cta)."-1' and idperiodo=$id_periodo");
				if($tot_ct_s->fields['idcuenta']!=""){
					++$f;
					if($tot_ct_s2->fields['idcuenta']!=""){
						//$db->debug=true;
						//$db->Execute("update cuenta_periodos set obs3='".lp2($id_cta)."-1',ciclo='100' where idcuenta='".$tot_ct_s2->fields['idcuenta']."' and idperiodo=$id_periodo");
						//$db->debug=false;
						++$p;
					}
				}
				if($tot_ct_s->fields['idcuenta']==""){
					++$g;
					//echo $id_cta."<br>";
					if($tot_ct_s2->fields['idcuenta']!=""){
						//$db->Execute("update cuenta_periodos set obs3='".lp2($id_cta)."-1' where idcuenta='".$tot_ct_s2->fields['idcuenta']."' and idperiodo=$id_periodo");
						++$o;
					}
				} 
			
			
			$update_cta[4][$s]="update cuenta_periodos set usureg=$id_periodo,idestado=1,grupo='C',fecven='".$fecven."',impmin='".$dt[23]."',imptot='".$dt[25]."',diasmora='".$fl_dm->fields['diasmora']."',ciclo='".$dt[20]."',observacion2='$riesgo',observacion='-".$dt[2]."-".$dt[5]."' where idcuenta='".$id_cta."-1' and idperiodo=$id_periodo";
			$cuentas_p[$s]="INSERT INTO cuenta_periodos (usureg,idperiodo,idusuario,idcuenta,grupo,fecven,impmin,imptot,diasmora,ciclo,observacion2,observacion)
				values ($id_periodo,$id_periodo,$idusu,'".$id_cta."-1','".$dt[7]."','".$fecven."','".$dt[23]."','".$dt[25]."','".$fl_dm->fields['diasmora']."','".$dt[20]."','".$riesgo."','-".$dt[2]."-".$dt[5]."');
				";
			
			$s++;
			
			
	}	
	
	fclose($fp);
	echo $f."<br>";
	
	//var_dump(count($clientes));
	//var_dump($direcciones);
	//var_dump(count($telefonos));
	//var_dump(count($cuentas));
	var_dump($cuentas_p);
	return false;
	$ok=true;
	$t=1;
	//$db->debug=true;
	$ist=0;$ups=0;$err=0;
	$db->StartTrans();
			for($i=1;$i<=count($clientes);$i++){
				$flag=$db->Execute($flag_cta[0][$i]);
				if($flag->fields['idcliente']==""){
					++$ist;
					$ok=$db->Execute($clientes[$i]);
					if($ok == false){
						$db->CompleteTrans(false);
						return false;
					}
				}else{
					++$ups;
					$ok=$db->Execute($update_cta[0][$i]);
					if($ok == false){
						$db->CompleteTrans(false);
						return false;
					}
				}
			}
		
	$db->CompleteTrans($ok);
	echo "<b style='font-size:14px;'><center>Clientes </center></br> &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) </b><br/><hr/>";
	$db->debug=false;

	//$db->debug=true;
	$ist=0;$ups=0;$err=0;
	$db->StartTrans();
			for($i=2;$i<=count($direcciones);$i++){
				$flag=$db->Execute($flag_cta[1][$i]);
				if($flag->fields['idcliente']==""){
					++$ist;
					

					$ok=$db->Execute($direcciones[$i]);
					$db->debug=false;
					if($ok == false){
						

						$db->CompleteTrans(false);
						return false;
					}
				}else{
					++$ups;
					$ok=$db->Execute($update_cta[1][$i]);
					if($ok == false){
						$db->CompleteTrans(false);
						return false;
					}
				}
			}
		
	$db->CompleteTrans($ok);
	echo "<b style='font-size:14px;'><center>Direcciones Personales </center></br> &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) </b><br/><hr/>";
	echo "<b style='font-size:14px;'><center>Direcciones de Trabajo </center></br> &nbsp;&nbsp;Ingresados ( ".($d_trabajo-1)." )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) </b><br/><hr/>";
	
	//$db->debug=false;
	//$db->debug=true;
	$ist=0;$ups=0;$err=0;
	$db->StartTrans();
			for($i=1;$i<=count($telefonos);$i++){
				$flag=$db->Execute($flag_cta[2][$i]);
				if($flag->fields['idcliente']==""){
					++$ist;
					$ok=$db->Execute($telefonos[$i]);
					if($ok == false){
						$db->debug=true;

						$db->CompleteTrans(false);
						$db->debug=false;

						return false;
					}
				}else{
					++$ups;
					$ok=$db->Execute($update_cta[2][$i]);
					if($ok == false){
						$db->debug=true;

						$db->CompleteTrans(false);
						$db->debug=false;

						return false;
					}
				}
			}
		
	$db->CompleteTrans($ok);
	echo "<b style='font-size:14px;'><center>Telefonos </center></br>  &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) </b><br/><hr/>";
	//$db->debug=false;
	//$db->debug=true;
	$ist=0;$ups=0;$err=0;
	$db->StartTrans();
			for($i=2;$i<=count($cuentas);$i++){
				$flag=$db->Execute($flag_cta[3][$i]);
				if($flag->fields['idcuenta']==""){
					++$ist;
					$ok=$db->Execute($cuentas[$i]);
					if($ok == false){
						$db->CompleteTrans(false);
						return false;
					}
				}else{
					++$ups;
					$ok=$db->Execute($update_cta[3][$i]);
					if($ok == false){
						$db->CompleteTrans(false);
						return false;
					}
				}
			}
		
	$db->CompleteTrans($ok);
	echo "<b style='font-size:14px;'><center>Cuentas </center></br>  &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) </b><br/><hr/>";
	//$db->debug=true;
	$ist=0;$ups=0;$err=0;
	$db->StartTrans();
			for($i=2;$i<=count($cuentas_p);$i++){
				if($flag_cta[4][$i]==""){ continue;}
				$flag=$db->Execute($flag_cta[4][$i]);
				if($flag->fields['idcuenta']==""){
					++$ist;
					$ok=$db->Execute($cuentas_p[$i]);
					if($ok == false){
						$db->CompleteTrans(false);
						return false;
					}
				}else{
					++$ups;
					$ok=$db->Execute($update_cta[4][$i]);
					if($ok == false){
						$db->CompleteTrans(false);
						return false;
					}
				}
			}
		
	$db->CompleteTrans($ok);
	echo "<b style='font-size:14px;'><center>Cuentas Periodo</center></br> &nbsp;&nbsp;Ingresados ( $ist )&nbsp;&nbsp; | &nbsp;&nbsp; Actualizados( $ups )&nbsp;&nbsp; | &nbsp;&nbsp; Erroneos ( $err ) </b><br/><hr/>";
				


?>
