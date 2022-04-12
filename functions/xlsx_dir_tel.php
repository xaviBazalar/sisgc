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




// Create new PHPExcel object


		$peri=$_GET['peri'];
		//$mes=$db->Execute("Select month(fecini) mes from periodos where idperiodo='$peri'");
		$mes_p=$db->Execute("SELECT year(fecini) ano,MONTH(fecini) mes FROM periodos WHERE idperiodo='$peri'");
		$mes_a=$mes_p->fields['mes'];
		$año_a=$mes_p->fields['ano'];
		//mysql_free_result($mes_p->_queryID);
/*Inicio Instrucciones*/
		$cart=$_GET['cart'];	
		if($_GET['tipo']=="T"){$x="t";}else{$x="d";}
		if($_GET['tipo']=="T"){
			/*$sql="SELECT t.idtelefono,f.fuente,ot.origentelefono,t.idcliente,t.telefono,e.estado,
					t.prioridad,t.observacion,
					CASE 
								WHEN t.idvalidacion IS NULL THEN 'No Validado' 
								WHEN t.idvalidacion THEN v.validaciones
					END AS validacion,
					t.fecreg
				FROM cuentas c
				JOIN telefonos t ON c.idcliente=t.idcliente
				JOIN fuentes f ON t.idfuente=f.idfuente
				JOIN origen_telefonos ot ON t.idorigentelefono=ot.idorigentelefono
				JOIN estados e ON t.idestado=e.idestado
				LEFT JOIN validaciones v ON t.idvalidacion=v.idvalidaciones
				";*/
				
			$sql="SELECT t.*
				FROM cuentas c
				JOIN telefonos t ON c.idcliente=t.idcliente AND YEAR($x.fecreg)=$año_a AND MONTH($x.fecreg)='$mes_a'
				WHERE c.idcartera='$cart' 
				GROUP BY t.idtelefono";
				
			$titulo="idtelefono\tfuente\torigen_telefono\tdocumento\ttelefono\testado\tprioridad\tobservacion\tvalidacion\tfecreg";	
				
		}else if($_GET['tipo']=="D"){
			/*$sql="SELECT  d.iddireccion,f.fuente,od.origendireccion,d.idcliente,d.direccion,
					d.coddpto,d.codprov,d.coddist,e.estado,d.prioridad,d.observacion,
					CASE 
								WHEN d.idvalidacion IS NULL THEN 'No Validado' 
								WHEN d.idvalidacion THEN v.validaciones
					END AS validacion,
					d.fecreg
				FROM cuentas c
				JOIN direcciones d ON c.idcliente=d.idcliente
				JOIN fuentes f ON d.idfuente=f.idfuente
				JOIN origen_direcciones od ON d.idorigendireccion=od.idorigendireccion
				JOIN estados e ON d.idestado=e.idestado
				LEFT JOIN validaciones v ON d.idvalidacion=v.idvalidaciones
			";*/
			$sql="SELECT d.*
				FROM cuentas c
				JOIN direcciones d ON c.idcliente=d.idcliente AND YEAR($x.fecreg)='$año_a' AND MONTH($x.fecreg)='$mes_a'
				WHERE c.idcartera='$cart' 
				GROUP BY d.iddireccion";
			
			$titulo="iddireccion\tfuente\torigen_direccion\tidcliente\tdireccion\tcoddpto\tcodprov\tcoddist\testado\tprioridad\tobservacion\tvalidacion\tfecreg";
		
		}
	



		/*if(isset($_GET['fecini']) and isset($_GET['fecfin'])){
			$ini=$_GET['fecini'];
			$fin=$_GET['fecfin'];
			$sql.=" AND g.fecges BETWEEN '$ini' AND '$fin' ";
		}*/
		
				
		$n=1;
		$fp=fopen("datos_$x.txt",'w');
		/*echo $sql."<br>";
		echo $titulo;
		*/
		$body=$titulo;
		fwrite($fp,$body);
		fwrite($fp , chr(13).chr(10));
		$query=$db->Execute($sql);
		$db->Close();
		
		//var_dump($query);
		//return false;
		while(!$query->EOF){
				++$n;
				
				if($_GET['tipo']=="D"){
					$cont.=$query->fields['iddireccion']."\t";
					$cont.=$query->fields['idfuente']."\t";
					$cont.=$query->fields['idorigendireccion']."\t";
					$cont.="=\"".$query->fields['idcliente']."\"\t";
					$cont.=$query->fields['direccion']."\t";
					$cont.=$query->fields['coddpto']."\t";
					$cont.=$query->fields['codprov']."\t";
					$cont.=$query->fields['coddist']."\t";
			
				}
				
				if($_GET['tipo']=="T"){
				if($_GET['tipo']=="T"){
				
					$fono= str_replace("-", " ", $query->fields['telefono']);
					$fono= str_replace("*", " ", $query->fields['telefono']);
					$fono= str_replace("(", " ", $query->fields['telefono']);
					$fono= str_replace(")", " ", $query->fields['telefono']);
					$fono= str_replace("#", " ", $query->fields['telefono']);
					
					$cont=$query->fields['idtelefono'];
					$cont.=$query->fields['idfuente']."\t";	
					$cont.=$query->fields['idorigentelefono']."\t";
					$cont.="=\"".$query->fields['idcliente']."\"\t";
					$cont.=$fono."\t";
				}
				
				$cont.=$query->fields['idestado']."\t";
				$cont.=$query->fields['prioridad']."\t";
				$cont.=$query->fields['observacion']."\t";
				$cont.=$query->fields['idvalidacion']." \t";
				$cont.=$query->fields['fecreg']."\t";

				fwrite($fp , $cont);
				fwrite($fp , chr(13).chr(10));
				$query->MoveNext();
			}
			fclose($fp);
			echo "<a href='guardar_como.php?name=datos_$x.txt' target='blank'>Click para descargar</a><br/>";	
				

	$db->Close();
	
?>
