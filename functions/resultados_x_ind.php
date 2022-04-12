<?php

session_start();
$iduser=$_SESSION['iduser'];
ini_set('memory_limit', '-1');
set_time_limit(1800);
/** Error reporting */
error_reporting(E_ALL);
$id_periodo=$_SESSION['periodo'];
date_default_timezone_set('Europe/London');

/** PHPExcel */

include '../define_con.php';
$db_t=  $db;
//$db->debug=true;
//$db_t->debug=true;
	$id_cartera=$_GET['idcartera'];
	$idperiodo=$_SESSION['periodo'];
	//$id_camp=$_GET['idcampana'];
	//$db_t->debug=true;
	if($_GET['incremental']!=""){ 
		if($_GET['incremental']=="1"){ 
			$fil_i=" and SUBSTRING_INDEX(SUBSTRING_INDEX( c.obs2 , '*', -2 ),'*',1) NOT IN ('0','') ";
		}else if($_GET['incremental']=="2"){
			$fil_i=" and SUBSTRING_INDEX(SUBSTRING_INDEX( c.obs2 , '*', -2 ),'*',1) IN ('0','') ";
		}
	}else{
		$fil_i="";
	}
	
	if($_GET['grupo']!=""){ 
		$fil_grp=" and cp.grupo='".$_GET['grupo']."' ";
	}else{
		$fil_grp="";
	}
	
	if($_GET['clasificacion']!=""){ 
		$fil_c=" and c.obs2 like '".$_GET['clasificacion']."%' ";
	}else{
		$fil_c="";
	}
	
	if($_GET['riesgo']!=""){ 
		$fil_r=" and cp.observacion2=".$_GET['riesgo'];
	}else{ 
		$fil_r="";
	}
	
	if($_GET['desde']!=""){
		$fil_saldo=" and cp.imptot BETWEEN ".$_GET['desde']." and ".$_GET['hasta']." ";
	}else{
		$fil_saldo="";
	}
	
	if($_GET['pagos']!=""){
		$fil_pgs=" and rs.pago like '%".$_GET['pagos']."%'  ";
	}else{
		$fil_pgs="";
	}
	
	if($_GET['gestion']=="0"){
	
		if($_GET['pagos']!=""){
			$fil_pgs=" and rs.pago like '%".$_GET['pagos']."%'  ";
		}else{
			$fil_pgs="";
		}
	
		$fil_ges=" WHERE rs.gestion is null $fil_pgs ";
	
	}else if($_GET['gestion']=="1"){
		
		if($_GET['pagos']!=""){
			$fil_pgs=" and rs.pago like '%".$_GET['pagos']."%'  ";
		}else{
			$fil_pgs="";
		}
		
		$fil_ges=" WHERE rs.gestion is not null $fil_pgs";
	}else{
		if($_GET['pagos']!=""){
			$fil_pgs=" WHERE rs.pago like '%".$_GET['pagos']."%'  ";
		}else{
			$fil_pgs="";
		}
		$fil_ges="";
	}
	
	if(isset($_GET['idusuario']) and isset($_GET['idcartera'])){
		//$db->debug=true;
			if($_GET['liberar']=="1" and $_GET['idusuario']!="all"){
				$fil_lb=" and cp.idusuario=".$_GET['idusuario'];
			}else{
				$fil_lb="";
			}
			$cuentas=$db->Execute("select rs.* from
									(SELECT 
									SUBSTRING_INDEX(SUBSTRING_INDEX( c.obs2 , '*', -2 ),'*',1)  incremental,
									cp.observacion2 riesgo,
									SUBSTRING_INDEX(  c.obs2 , '*', 1 ) clasificacion,
									(
										SELECT 	SUBSTRING_INDEX(SUBSTRING_INDEX( observacion , '*', -3 ),'*',1)
										FROM cuenta_pagos where idperiodo=$idperiodo and idcuenta=cp.idcuenta limit 0,1
									) pago,
									cp.imptot facturado,
									cp.idcuenta,
									cp.idusuario,
									(SELECT idcuenta FROM gestiones WHERE idcuenta=cp.idcuenta AND fecges LIKE '".date("Y-m")."%' LIMIT 0,1) gestion
									FROM cuentas c
									JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$idperiodo AND cp.idestado=1
									WHERE c.idcartera=$id_cartera $fil_lb
									$fil_i
									$fil_c
									$fil_r
									$fil_grp
									$fil_saldo) as rs
									$fil_ges
									$fil_pgs");
									
			while(!$cuentas->EOF){
					$id_cta=$cuentas->fields['idcuenta'];
					$sql_up_lb="Update cuenta_periodos set idusuario=2 where idcuenta='".$id_cta."' and idperiodo=$id_periodo and idusuario=".$_GET['idusuario']." ";
					$db->Execute($sql_up_lb);
					$cuentas->MoveNext();
			}
		//$db->debug=false;
				//	$db_t->Execute($sql);
	
	}
	if(isset($_GET['idusuarios']) and isset($_GET['idcartera']) and isset($_GET['incremental']) and isset($_GET['riesgo'])){
			
			$usuarios=explode(",",$_GET['idusuarios']);
			$tot_u=explode(",",$_GET['tot_u']);

			$tot=$db->Execute("select count(*) total,sum(rs.idusuario=2) disponible from
								(SELECT 
									SUBSTRING_INDEX(SUBSTRING_INDEX( c.obs2 , '*', -2 ),'*',1)  incremental,
									cp.observacion2 riesgo,
									SUBSTRING_INDEX(  c.obs2 , '*', 1 ) clasificacion,
									(
										SELECT 	SUBSTRING_INDEX(SUBSTRING_INDEX( observacion , '*', -3 ),'*',1)
										FROM cuenta_pagos where idperiodo=$idperiodo and idcuenta=cp.idcuenta limit 0,1
									) pago,
									cp.imptot facturado,
									cp.idcuenta,
									cp.idusuario,
									(SELECT idcuenta FROM gestiones WHERE idcuenta=cp.idcuenta AND fecges LIKE '".date("Y-m")."%' LIMIT 0,1) gestion
									FROM cuentas c
									JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$idperiodo AND cp.idestado=1
									WHERE c.idcartera=$id_cartera and cp.idusuario=2
									$fil_i
									$fil_c
									$fil_r
									$fil_grp
									$fil_saldo) as rs
									$fil_ges
									$fil_pgs
									");
			$total=$tot->fields['disponible'];
			
			
			$limit=round(($total/(count($usuarios)-1)),0);
			$resto=fmod($total,(count($usuarios)-1));
			
			//$db->debug=true;
			if($_GET['liberar']==1){
				$fil_lb=" and cp.idusuario=".$usuarios[0];
			}else{
				$fil_lb="";
			}
			$cuentas=$db->Execute("	Select rs.* from
									(SELECT 
									SUBSTRING_INDEX(SUBSTRING_INDEX( c.obs2 , '*', -2 ),'*',1)  incremental,
									cp.observacion2 riesgo,
									SUBSTRING_INDEX(  c.obs2 , '*', 1 ) clasificacion,
									(
										SELECT 	SUBSTRING_INDEX(SUBSTRING_INDEX( observacion , '*', -3 ),'*',1)
										FROM cuenta_pagos where idperiodo=$idperiodo and idcuenta=cp.idcuenta limit 0,1
									) pago,
									cp.imptot facturado,
									cp.idcuenta,
									cp.idusuario,
									(SELECT idcuenta FROM gestiones WHERE idcuenta=cp.idcuenta AND fecges LIKE '".date("Y-m")."%' LIMIT 0,1) gestion
									FROM cuentas c
									JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$idperiodo AND cp.idestado=1
									WHERE c.idcartera=$id_cartera and cp.idusuario=2
									$fil_i
									$fil_c
									$fil_r
									$fil_grp									
									$fil_saldo) as rs
									$fil_ges
									$fil_pgs");
								
			
			//echo $limit;
			//var_dump($usuarios);return false;
			//$db->debug=true;
			if($_GET['tot_u']==""){
				$total=$limit;
			}
			$s=0;
			for($i=0;$i<(count($usuarios)-1);$i++){

					for($t=1;$t<=$total;$t++){
						
						++$s;
						if($tot_u[$i]=="" and $_GET['tot_u']!=""){ break;}
	
							$sql_up=" update cuenta_periodos set idusuario=".$usuarios[$i]." where idcuenta='".$cuentas->fields['idcuenta']."' and idperiodo=$idperiodo and idestado=1 ";
						
						if($t>$tot_u[$i] and  $_GET['tot_u']!="") { break;}
						
						
						$db->Execute($sql_up);
						$cuentas->MoveNext();
						//if($s==$total){break;}
					}
		
				


			}
	}else if(isset($_GET['ctas']) and isset($_GET['idcartera']) and isset($_GET['idagente']) ){
	
		/*$idperiodo=$_SESSION['periodo'];
		$idagente=$_GET['idagente'];
		$sql="
						UPDATE cuentas c, cuenta_periodos cp  SET cp.idestado=1,c.idestado=1
						WHERE  cp.idcuenta=c.idcuenta
						AND cp.idperiodo=$idperiodo 
						AND c.idestado=0
						AND c.idcartera=$id_cartera
						AND cp.idusuario=$idagente

						";

					
					$db_t->Execute($sql);*/
	}
		
	
	if($id_cartera==""){return false;}

	
	//$db->debug=true;
		$tot=$db->Execute("select count(*) total,sum(rs.idusuario=2) disponible from
								(SELECT 
									SUBSTRING_INDEX(SUBSTRING_INDEX( c.obs2 , '*', -2 ),'*',1)  incremental,
									cp.observacion2 riesgo,
									SUBSTRING_INDEX(  c.obs2 , '*', 1 ) clasificacion,
									(
										SELECT 	SUBSTRING_INDEX(SUBSTRING_INDEX( observacion , '*', -3 ),'*',1)
										FROM cuenta_pagos where idperiodo=$idperiodo and idcuenta=cp.idcuenta limit 0,1
									) pago,
									cp.imptot facturado,
									cp.idcuenta,
									cp.idusuario,
									(SELECT idcuenta FROM gestiones WHERE idcuenta=cp.idcuenta AND fecges LIKE '".date("Y-m")."%' LIMIT 0,1) gestion
									FROM cuentas c
									JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$idperiodo AND cp.idestado=1
									WHERE c.idcartera=$id_cartera
									$fil_i
									$fil_c
									$fil_r
									$fil_grp
									$fil_saldo) as rs
									$fil_ges
									$fil_pgs
							");
	

	echo "Cuenta Filtradas : ".$tot->fields['total']." | Disponibles : ".$tot->fields['disponible']."<br/>";

	echo "<div style='height:460px;overflow-y:scroll;'>
			<div id='design1'>
			<table style='float:left;'>
				<tr>
					<th>idUsuario</th><th>Usuario</th><th>Total Ctas</th><th>Liberar</th><th>Cantidad</th><th><input type='checkbox' name='res_c' value='all'  onclick='up_res_c(1);'/></th>
				</tr>";
				
				$sql="SELECT * FROM usuarios WHERE idcartera=$id_cartera AND idnivel=2 AND idestado=1 AND idusuario!=2";
				$up=$db_t->Execute($sql);
				$n=0;
				while(!$up->EOF){
					++$n;
						echo "<tr>
								<td>".$up->fields['idusuario']."</td>
								<td>".$up->fields['usuario']."</td>";
						$t_cta="select count(*) tot,rs.idusuario from
								(SELECT 
									SUBSTRING_INDEX(SUBSTRING_INDEX( c.obs2 , '*', -2 ),'*',1)  incremental,
									cp.observacion2 riesgo,
									SUBSTRING_INDEX(  c.obs2 , '*', 1 ) clasificacion,
									(
										SELECT 	SUBSTRING_INDEX(SUBSTRING_INDEX( observacion , '*', -3 ),'*',1)
										FROM cuenta_pagos where idperiodo=$idperiodo and idcuenta=cp.idcuenta limit 0,1
									) pago,
									cp.imptot facturado,
									cp.idcuenta,
									cp.idusuario,
									(SELECT idcuenta FROM gestiones WHERE idcuenta=cp.idcuenta AND fecges LIKE '".date("Y-m")."%' LIMIT 0,1) gestion
									FROM cuentas c
									JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$idperiodo AND cp.idestado=1
									WHERE c.idcartera=$id_cartera and cp.idusuario=".$up->fields['idusuario']."
									$fil_i
									$fil_c
									$fil_r
									$fil_grp
									$fil_saldo) as rs
									$fil_ges
									$fil_pgs
								
								";
						$t_cta=$db->Execute($t_cta);
						if($t_cta->fields['tot']!=0){$btn="<input type='button' onclick='libera_cta(".$up->fields['idusuario'].");' class='btn2' value='Liberar Ctas' style='background-color: blue;'/>";}else{$btn="";}
				$total=$total+($t_cta->fields['tot']);

				echo "		<td>".$t_cta->fields['tot']."</td>
								<td>$btn</td>
								<td><input style='color:black;border-color:grey;' type='text' id='u_".$up->fields['idusuario']."' size='5' maxlength='5'/></td>
								<td align='center' ><input type='checkbox' name='res_c' value='".$up->fields['idusuario']."' /></td>
							 </tr>";
						$up->MoveNext();
				}
				echo "<tr><td colspan='5' align='right'><h2> Total Asignado: $total </h2></td></tr>";

				echo "	</table>
				
				
	        </div>
		 </div>";
?>

	
					<br/>
					<input type='button' class='btn' value='Sincronizar' onclick='up_res_c(0);'>
					<input type='button' class='btn' value='Liberar Todo' onclick='libera_cta("all");'>
