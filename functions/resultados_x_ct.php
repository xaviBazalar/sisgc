<?php

session_start();
$iduser=$_SESSION['iduser'];
ini_set('memory_limit', '-1');
set_time_limit(1800);
/** Error reporting */
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

/** PHPExcel */

include '../define_con.php';
$db_t=  $db;

	$id_cartera=$_GET['idcartera'];
	//$id_camp=$_GET['idcampana'];
	//$db_t->debug=true;
	if(isset($_GET['idusuario']) and isset($_GET['idcartera'])){
		$sql="
							UPDATE cuenta_periodos cp,
							  cuentas c
							SET cp.idusuario = 2
							WHERE c.idcuenta = cp.idcuenta
								AND c.idcartera = ".addslashes($_GET['idcartera'])." 
								AND cp.idestado = 1 
								and cp.idusuario=".addslashes($_GET['idusuario'])."

							";
		
					$db_t->Execute($sql);
	
	}
	if(isset($_GET['idusuarios']) and isset($_GET['idcartera'])){
			$idperiodo=$_SESSION['periodo'];
			$usuarios=explode(",",$_GET['idusuarios']);
			
			$tot=$db->Execute("SELECT COUNT(DISTINCT(c.idcuenta)) total FROM cuentas c
								JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idestado=1
								WHERE c.idcartera=$id_cartera AND cp.idusuario=2
								AND cp.idperiodo=(SELECT idperiodo FROM periodos WHERE YEAR(fecini)=YEAR(NOW()) AND MONTH(fecini)=MONTH(NOW()) )");
			
			$total=$tot->fields['total'];
			
			$limit=round(($total/(count($usuarios)-1)),0);
			$resto=fmod($total,(count($usuarios)-1));
			$cuentas=$db->Execute("SELECT cp.idcuenta FROM cuentas c
						JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idestado=1
						WHERE c.idcartera=$id_cartera AND cp.idusuario=2
						AND cp.idperiodo=(SELECT idperiodo FROM periodos WHERE YEAR(fecini)=YEAR(NOW()) AND MONTH(fecini)=MONTH(NOW())) GROUP BY cp.idcuenta");
			//echo $limit;
			//var_dump($usuarios);return false;
			//$db->debug=true;
			$s=0;
			for($i=0;$i<(count($usuarios)-1);$i++){

				/*if($resto>0 and $i==(count($usuarios)-1)){
				
					$sql="
							UPDATE cuenta_periodos cp,
							  cuentas c
							SET cp.idusuario = $usuarios[$i]
							WHERE c.idcuenta = cp.idcuenta
								AND c.idcartera = $id_cartera 
								AND cp.idestado = 1 
								and cp.idusuario=2

							";
		
					$db_t->Execute($sql);
				}else{*/
					for($t=1;$t<=$limit;$t++){
						++$s;
						
						$sql_up="update cuenta_periodos set idusuario=".$usuarios[$i]." where idcuenta='".$cuentas->fields['idcuenta']."' and idperiodo=$idperiodo and idestado=1 ";
						$db->Execute($sql_up);
						$cuentas->MoveNext();
						if($s==$total){break;}
					}
				//}
				


			}
	}else if(isset($_GET['ctas']) and isset($_GET['idcartera']) and isset($_GET['idagente']) ){
	
		$idperiodo=$_SESSION['periodo'];
		$idagente=$_GET['idagente'];
		$sql="
						UPDATE cuentas c, cuenta_periodos cp  SET cp.idestado=1,c.idestado=1
						WHERE  cp.idcuenta=c.idcuenta
						AND cp.idperiodo=$idperiodo 
						AND c.idestado=0
						AND c.idcartera=$id_cartera
						AND cp.idusuario=$idagente

						";

					
					$db_t->Execute($sql);
	}
		
	
	if($id_cartera==""){return false;}
	$tot=$db->Execute("SELECT COUNT(DISTINCT(c.idcuenta)) total FROM cuentas c
								JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idestado=1
								WHERE c.idcartera=$id_cartera AND cp.idusuario=2
								AND cp.idperiodo=(SELECT idperiodo FROM periodos WHERE YEAR(fecini)=YEAR(NOW()) AND MONTH(fecini)=MONTH(NOW()) )");
			
	echo "Cuenta disponibles para asignar : ".$tot->fields['total']."<br/>";
	
	
	echo "<div style='height:460px;overflow-y:scroll;'>
			<div id='design1'>
			<table style='float:left;'>
				<tr>
					<th>idUsuario</th><th>Usuario</th><th>Total Ctas</th><th></th><th><input type='checkbox' name='res_c' value='all'  onclick='up_res_c(1);'/></th>
				</tr>";
				
				$sql="SELECT * FROM usuarios WHERE idcartera=$id_cartera AND idnivel=2  AND idusuario!=2";
				$up=$db_t->Execute($sql);
				$n=0;
				//$total=0;
				while(!$up->EOF){
					++$n;
						echo "<tr>
								<td>".$up->fields['idusuario']."</td>
								<td>".$up->fields['usuario']."</td>";
						$t_cta="SELECT cp.idusuario,COUNT(distinct(c.idcuenta)) tot FROM cuentas c
								JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta  AND cp.idperiodo=".$_SESSION['periodo']." AND cp.idestado=1
								WHERE cp.idusuario=".$up->fields['idusuario']." AND c.idcartera=$id_cartera
								";

						
						
						$t_cta=$db->Execute($t_cta);
						if($t_cta->fields['tot']!=0){$btn="<input type='button' onclick='libera_cta(".$up->fields['idusuario'].");' class='btn2' value='Liberar Ctas' style='background-color: blue;'/>";}else{$btn="";}
				//echo $t_cta->fields['tot'];
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

