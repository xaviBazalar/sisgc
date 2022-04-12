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

	$id_cartera=$_GET['idcartera'];
	$idperiodo=$_SESSION['periodo'];
	//$id_camp=$_GET['idcampana'];
	//$db_t->debug=true;
	if(isset($_GET['idusuario']) and isset($_GET['idcartera'])){
		//$db->debug=true;
			if($_GET['liberar']=="1" and $_GET['idusuario']!="all"){
				$fil_lb=" and rs.idusuario=".$_GET['idusuario'];
			}else{
				$fil_lb="";
			}
			$cuentas=$db->Execute("SELECT rs.idcuenta  FROM
									(SELECT cp.idcuenta,g.idresultado,r.resultado,cp.idusuario
									FROM cuentas c
									JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$id_periodo AND cp.idestado=1
									JOIN gestiones g ON g.idcuenta=cp.idcuenta AND g.fecges LIKE '".date("Y-m")."%'
									JOIN resultados r ON g.idresultado=r.idresultado
									AND r.peso_r=(
											SELECT MIN(r2.peso_r) FROM gestiones g2 
												JOIN cuentas c2 ON g2.idcuenta=c2.idcuenta AND c2.idcartera=$id_cartera
												JOIN cuenta_periodos cp2 ON c2.idcuenta=cp2.idcuenta AND cp2.idperiodo=$id_periodo  
												JOIN resultados r2 ON g2.idresultado=r2.idresultado
												WHERE c2.idcliente=c.idcliente
												AND g2.fecges LIKE '".date("Y-m")."%' AND g2.idactividad!=4)
									GROUP BY c.idcuenta) AS rs where rs.idresultado=".$_GET['idresultado']." $fil_lb ");
									
			while(!$cuentas->EOF){
					$id_cta=$cuentas->fields['idcuenta'];
					$sql_up_lb="Update cuenta_periodos set idusuario=2 where idcuenta='".$id_cta."' and idperiodo=$id_periodo and idusuario=".$_GET['idusuario']." ";
					
					$db->Execute($sql_up_lb);
					$cuentas->MoveNext();
			}
		//$db->debug=false;
				//	$db_t->Execute($sql);
	
	}
	if(isset($_GET['idusuarios']) and isset($_GET['idcartera']) and isset($_GET['idresultado'])){
			
			$usuarios=explode(",",$_GET['idusuarios']);
			

			$tot=$db->Execute("SELECT rs.idresultado,rs.resultado,count(*) total FROM
									(SELECT cp.idcuenta,g.idresultado,r.resultado
									FROM cuentas c
									JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$id_periodo AND cp.idestado=1
									JOIN gestiones g ON g.idcuenta=cp.idcuenta AND g.fecges LIKE '".date("Y-m")."%'
									JOIN resultados r ON g.idresultado=r.idresultado
									AND r.peso_r=(
											SELECT MIN(r2.peso_r) FROM gestiones g2 
												JOIN cuentas c2 ON g2.idcuenta=c2.idcuenta AND c2.idcartera=$id_cartera
												JOIN cuenta_periodos cp2 ON c2.idcuenta=cp2.idcuenta AND cp2.idperiodo=$id_periodo  
												JOIN resultados r2 ON g2.idresultado=r2.idresultado
												WHERE c2.idcliente=c.idcliente
												AND g2.fecges LIKE '".date("Y-m")."%' AND g2.idactividad!=4)
									GROUP BY c.idcuenta) AS rs where rs.idresultado=".$_GET['idresultado']." GROUP BY rs.idresultado
									");
			$total=$tot->fields['total'];
			
			
			$limit=round(($total/(count($usuarios)-1)),0);
			$resto=fmod($total,(count($usuarios)-1));
			
			//$db->debug=true;
			if($_GET['liberar']==1){
				$fil_lb=" and cp.idusuario=".$usuarios[0];
			}else{
				$fil_lb="";
			}
			$cuentas=$db->Execute("SELECT rs.idcuenta  FROM
									(SELECT cp.idcuenta,g.idresultado,r.resultado,cp.idusuario
									FROM cuentas c
									JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$id_periodo AND cp.idestado=1
									JOIN gestiones g ON g.idcuenta=cp.idcuenta AND g.fecges LIKE '".date("Y-m")."%'
									JOIN resultados r ON g.idresultado=r.idresultado
									AND r.peso_r=(
											SELECT MIN(r2.peso_r) FROM gestiones g2 
												JOIN cuentas c2 ON g2.idcuenta=c2.idcuenta AND c2.idcartera=$id_cartera
												JOIN cuenta_periodos cp2 ON c2.idcuenta=cp2.idcuenta AND cp2.idperiodo=$id_periodo  
												JOIN resultados r2 ON g2.idresultado=r2.idresultado
												WHERE c2.idcliente=c.idcliente
												AND g2.fecges LIKE '".date("Y-m")."%' AND g2.idactividad!=4)
									GROUP BY c.idcuenta) AS rs where rs.idresultado=".$_GET['idresultado']." $fil_lb ");
								
			
			//echo $limit;
			//var_dump($usuarios);return false;
			//$db->debug=true;
			//return false;
			$s=0;
			for($i=0;$i<(count($usuarios)-1);$i++){

					for($t=1;$t<=$limit;$t++){
						++$s;
						
						$sql_up="update cuenta_periodos set idusuario=".$usuarios[$i]." where idcuenta='".$cuentas->fields['idcuenta']."' and idperiodo=$idperiodo and idestado=1 ";
						$db->Execute($sql_up);
						$cuentas->MoveNext();
						if($s==$total){break;}
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

	if($_GET['idresultado']==""){
	
	$tot=$db->Execute("SELECT COUNT(DISTINCT(c.idcuenta)) total FROM cuentas c
								JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idestado=1
								WHERE c.idcartera=$id_cartera AND cp.idusuario=2
								AND cp.idperiodo=(SELECT idperiodo FROM periodos WHERE YEAR(fecini)=YEAR(NOW()) AND MONTH(fecini)=MONTH(NOW()) )");
	}else{
		$tot=$db->Execute("SELECT rs.idresultado,rs.resultado,count(*) total FROM
							(SELECT cp.idcuenta,g.idresultado,r.resultado
							FROM cuentas c
							JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$id_periodo AND cp.idestado=1
							JOIN gestiones g ON g.idcuenta=cp.idcuenta AND g.fecges LIKE '".date("Y-m")."%'
							JOIN resultados r ON g.idresultado=r.idresultado
							AND r.peso_r=(
									SELECT MIN(r2.peso_r) FROM gestiones g2 
										JOIN cuentas c2 ON g2.idcuenta=c2.idcuenta AND c2.idcartera=$id_cartera
										JOIN cuenta_periodos cp2 ON c2.idcuenta=cp2.idcuenta AND cp2.idperiodo=$id_periodo  
										JOIN resultados r2 ON g2.idresultado=r2.idresultado
										WHERE c2.idcliente=c.idcliente
										AND g2.fecges LIKE '".date("Y-m")."%' AND g2.idactividad!=4)
							GROUP BY c.idcuenta) AS rs where rs.idresultado=".$_GET['idresultado']." GROUP BY rs.idresultado
							");
	
	}
	echo "Cuenta disponibles para asignar : ".$tot->fields['total']."<br/>";
	
	
	echo "<div style='height:460px;overflow-y:scroll;'>
			<div id='design1'>
			<table style='float:left;'>
				<tr>
					<th>idUsuario</th><th>Usuario</th><th>Total Ctas</th><th></th><th><input type='checkbox' name='res_c' value='all'  onclick='up_res_c(1);'/></th>
				</tr>";
				
				$sql="SELECT * FROM usuarios WHERE idcartera=$id_cartera AND idnivel=2 AND idestado=1 AND idusuario!=2";
				$up=$db_t->Execute($sql);
				$n=0;
				while(!$up->EOF){
					++$n;
						echo "<tr>
								<td>".$up->fields['idusuario']."</td>
								<td>".$up->fields['usuario']."</td>";
						$t_cta="SELECT cp.idusuario,COUNT(distinct(c.idcuenta)) tot FROM cuentas c
								JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta  AND cp.idperiodo=".$_SESSION['periodo']." AND cp.idestado=1
								join gestiones g on cp.idcuenta=g.idcuenta AND g.fecges LIKE '".date("Y-m")."%' AND g.idactividad!=4
								join resultados r on g.idresultado=r.idresultado 
								WHERE cp.idusuario=".$up->fields['idusuario']." AND c.idcartera=$id_cartera
								and r.idresultado=".$_GET['idresultado']."
								AND r.peso_r=(
											SELECT MIN(r2.peso_r) FROM gestiones g2 
												JOIN cuentas c2 ON g2.idcuenta=c2.idcuenta AND c2.idcartera=$id_cartera
												JOIN cuenta_periodos cp2 ON c2.idcuenta=cp2.idcuenta AND cp2.idperiodo=$id_periodo  
												JOIN resultados r2 ON g2.idresultado=r2.idresultado
												WHERE c2.idcliente=c.idcliente
												AND g2.fecges LIKE '".date("Y-m")."%' AND g2.idactividad!=4)
								
								";
						$t_cta=$db->Execute($t_cta);
						if($t_cta->fields['tot']!=0){$btn="<input type='button' onclick='libera_cta(".$up->fields['idusuario'].");' class='btn2' value='Liberar Ctas' style='background-color: blue;'/>";}else{$btn="";}
				$total=$total+($t_cta->fields['tot']);

				echo "		<td>".$t_cta->fields['tot']."</td>
								<td>$btn</td>
								<td align='center' ><input type='checkbox' name='res_c' value='".$up->fields['idusuario']."' /></td>
							 </tr>";
						$up->MoveNext();
				}
				echo "<tr><td colspan='4' align='right'><h2> Total Asignado: $total </h2></td></tr>";

				echo "	</table>
				
				
	        </div>
		 </div>";
?>

	
					<br/>
					<input type='button' class='btn' value='Sincronizar' onclick='up_res_c(0);'>
					<input type='button' class='btn' value='Liberar Todo' onclick='libera_cta("all");'>
