<?php
	require 'define_con.php';
	//var_dump($_POST);
	$s=$db->Execute("SELECT detalle FROM detalle_produccion");
	while(!$s->EOF){
		echo $s->fields['detalle']."<br/>";
		$s->MoveNext();
	}
	if(isset($_POST['cont_ta']) and $_POST['cont_ta']!=""){
		$cont=$_POST['cont_ta'];
		$fec=date("Y-m-d");
		$db->Execute("insert into detalle_produccion (idtpro,detalle,fecha) values(1,'$cont','$fec')");
	}
?>
<script>
	function v1(){
		if(confirm("Esta seguro de ingresar esta tarea?")){
			var cont_ta=document.getElementById("cont_ta").value
			if(cont_ta==""){
				alert("Por favor ingrese tarea");
				return false;
			}
			document.tarea.submit();
		}
		
	}
</script>
<table  cellspacing="0" cellpadding="0" width="700px">
<tr>
	<td colspan="2" align="center" style="padding:10px;">
		REPORTE DE PRODUCCION DIARIO		
	</td>
</tr>
<tr>
	<td colspan="2" align="left" style="padding:6px;">
		DATOS PERSONALES		
	</td>
</tr>
<tr>
	<td align="left">
		Nombres y Apellidos:
	</td>
	<td align="center">
		Javier Arturo Bazalar
	</td>
</tr>
<tr>
	<td align="left">
		Cargo/Responsabilidad:
	</td>
	<td align="center">
		DBA / Programador
	</td>
</tr>
<tr>
	<td align="left">
		Email:
	</td>
	<td align="center">
		jbazalar@kobsa.com.pe
	</td>
</tr>
<tr>
	<td colspan="2" align="left" style="padding:6px;">
		TAREAS REALIZADAS(Tickets / Tareas Realizadas)	
	</td>
</tr>
<tr>
	<td align="left" >
		<form name="tarea" method="POST" action="ing_pro.php">
			<textarea id="cont_ta" name="cont_ta" cols="40" rows="4"></textarea>
			
		</form>	

	</td>
	<td align="center">
		<input type="button" value="Ingresar" onclick="v1();"/>
	</td>
</tr>
<tr>
	<td colspan="2" align="left" style="padding:6px;">
		PENDIENTES
	</td>
</tr>
<tr>
	<td align="left" >
		<form name="pendiente" method="POST" action="ing_pro.php">
			<textarea id="cont_pe" name="cont_pe" cols="40" rows="4">
			</textarea>
			
		</form>	

	</td>
	<td align="center">
		<input type="button" value="Ingresar"/>
	</td>
</tr>
<tr>
	<td colspan="2" align="left" style="padding:6px;">
		OBSERVACIONES
	</td>
</tr>
<tr>
	<td align="left" >
		<form name="observacion" method="POST" action="ing_pro.php">
			<textarea id="cont_obs" name="cont_obs" cols="40" rows="4">
			</textarea>
			
		</form>	

	</td>
	<td align="center">
		<input type="button" value="Ingresar"/>
	</td>
</tr>


</table>