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
	//$id_cartera=$_GET['idcartera'];
	//$id_camp=$_GET['idcampana'];
	//$db_t->debug=true;
	$usuario=$_GET['idusuario'];
	
	if(isset($_GET['iduc']) and isset($_GET['idestado'])){
		$est=$_GET['idestado'];
		$id_uc=$_GET['iduc'];
		$db_t->Execute("update usuarios_carteras set idestado=$est where idusucar=$id_uc");
	}
	
	if(isset($_GET['idpro']) and isset($_GET['idcart'])){
		$prov=$_GET['idpro'];
		$cart=$_GET['idcart'];
		$val=$db_t->Execute("Select idusuario from usuarios_carteras where idusuario=$usuario and idproveedor=$prov and idcartera=$cart ");
		if($val->fields['idusuario']==""){
			$db_t->Execute("INSERT into usuarios_carteras (`idusuario`,`idproveedor`,`idcartera`) values ('$usuario','$prov','$cart')");
		}
	}
	
	if(isset($_GET['idusuario']) and isset($_GET['idcampana'])){
			$usuarios=explode(",",$_GET['idusuario']);
			$idcmp=$_GET['idcampana'];
	}
	
//$db_t->debug=true;
	$up=$db_t->Execute("SELECT uc.idusucar,uc.idusuario,u.usuario,p.proveedor,c.cartera,p.idproveedor,c.idcartera,uc.idestado FROM usuarios_carteras uc
						JOIN usuarios u ON uc.idusuario=u.idusuario
						JOIN proveedores p ON uc.idproveedor=p.idproveedor
						JOIN carteras c ON uc.idcartera=c.idcartera
						WHERE /*u.idproveedor=15*/  idnivel!=5  and u.idusuario=$usuario ");
	
	echo "<input id='uxc' type='text' value='$usuario' style='visibility:hidden;position:absolute;'/>";
	//echo "<input id='uxc_c' type='text' value='".$up->fields['idusucar']."' style='visibility:hidden;position:absolute;'/>";
	echo "<div style='float:left;width:600px;height:300px;'>
			<div id='design1'>
			<table>
				";
	$n=0;
	while(!$up->EOF){
		++$n;
		
			if($n==1){
				echo"<tr>
					<th>".$up->fields['idusuario']."</th><th>".$up->fields['usuario']."</th><th></th><th>Estado</th><th></th>
				</tr>";
			}
			echo "<tr>
					<td>".$up->fields['idusuario']."</td>
					<td>".$up->fields['proveedor']."</td>
					<td>".$up->fields['cartera']."</td>";
			echo   "<td style='background-color:white;'>";
						   if($up->fields['idestado']== "1"){
							   echo "<img src='imag/icons/estado_1.png'/>";
								$es="Desactivar";
								$flag=1;
						   }else{
							   echo "<img src='imag/icons/estado_2.png'/>";
							   $es="Activar";
							   $flag=2;
							}
			echo   "</td>
					<td>
						<a href='#' onclick='javascript:us_x_ct($flag,".$up->fields['idusucar'].");'>$es</a>
					</td>";
			echo   "</tr>";
		    $up->MoveNext();
	}
	
	echo "	</table>
	        </div>
		 </div>
		 
		 ";
		/* echo"<b>Seleccionar Campa&ntilde;a: </b>
					<select onchange='' id='c_id' name='id_camp'>
						<option value=''>Seleccione...</option>";
					
							$sql="SELECT idcampana,campana FROM campana ";
							
							$query= $db->Execute($sql);
							while(!$query->EOF){
								echo "<option value='".$query->fields['idcampana']."'>".$query->fields['campana']."</option>";
								$query->MoveNext();
							}
						
		echo"				</select>
									
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type='button' class='btn' value='Actualizar' onclick='up_users_c(0);'>
		  ";
		  */
		  
		  
		
	
?>
<nobr>
					<b>Proveedor: </b>
					<select onchange="cart(2);" id="u_prove2" name="proveedor">
						<option value="">Seleccione...</option>
						<?php
							$sql="SELECT idproveedor, proveedor FROM proveedores WHERE idestado=1";
							/*if($_SESSION['tnivel']==5){
								$sql.= " and idproveedor='".$_SESSION['idpro']."'";
							} */
							$query= $db->Execute($sql);
							while(!$query->EOF){
								
								echo "<option value='".$query->fields['idproveedor']."'>".$query->fields['proveedor']."</option>";
								$query->MoveNext();
							}
						?>
					</select>
					&nbsp;&nbsp;<b>Cartera:</b>
					<select id="u_cart2" name="cartera"  onchange="">
						<option value="">..Seleccione</option>
					</select>
					&nbsp;&nbsp;
					<input type='button' class='btn' value='Agregar' onclick='us_x_ct(0);'/>
					</nobr>
