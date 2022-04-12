<?php
	include 'define_con.php';
	$sql="SELECT u.idusuario,u.login,u.usuario,u.enlinea,prv.proveedor,ct.cartera FROM usuarios u
		JOIN proveedores prv ON u.idproveedor=prv.idproveedor
		JOIN carteras ct ON u.idcartera=ct.idcartera 
		where u.idestado='1' ";
		if(isset($_GET['prov'])){
			$prov=$_GET['prov'];
			$sql.="AND prv.idproveedor='$prov'";
		}else{
			$sql.="AND prv.idproveedor!='1'";		
		}
		
		if(isset($_GET['estado'])){
			$est=$_GET['estado'];
			$sql.="AND u.enlinea='$est'";
		}
		
	$rpta=$db->Execute($sql);
	echo "<div id=\"ykBody\">
      <center>
			Proveedor:<select id='u_prove' onchange='cart();'><option value='' >..Seleccione</option>"; 
			$rpta_pr=$db->Execute("SELECT idproveedor,proveedor FROM proveedores where idestado=1");
			while (!$rpta_pr->EOF) {
                echo "<option value='".$rpta_pr->fields['idproveedor']."'>".$rpta_pr->fields['proveedor']."</option>";
                $rpta_pr->MoveNext();  
			}
	echo "</select>";		
	echo "  Cartera:<select id='u_cart'><option value='' >..Seleccione</option></select>";
	
	echo "  Estado en Linea:
			<select id='user_o'>
				<option value='' >..Seleccione</option>
				<option value='1' >En Linea</option>
				<option value='0' >Desconectado</option>
			</select><input class='btn' type='button' value='Buscar'  onclick=\"buscar('est_user.php?id=Kc');\" />";
	
    echo "  <fieldset><legend>Usuarios en Linea</legend>
      <div id=\"design1\">
      <table style='text-align:left;width:500px;'>
      <tr><th>Nro</th><th>Id</th><th>Nombres y Apellido</th><th>Login</th><th>Estado</th><th>Proveedor</th><th>Cartera</th><tr>";	
	while(!$rpta->EOF){
		echo "<tr><td>".++$n."</td>
			  <td style='background-color:white;'>".$rpta->fields['idusuario']."</td>
			  <td style='background-color:white;'>".$rpta->fields['usuario']."</td>
			  <td style='background-color:white;'>".$rpta->fields['login']."</td> ";
			  if($rpta->fields['enlinea']=="1"){$img="imag/icon_msn.png";$ets="En Linea";}else{$img="imag/icon_msn_of.png";$ets="Desconectado";}
		echo "<td style='background-color:white;'><img title='$ets' src='$img' /></td>
			  <td style='background-color:white;'>".$rpta->fields['proveedor']."</td>
			  <td style='background-color:white;'>".$rpta->fields['cartera']."</td>
			  </tr>";
			$rpta->MoveNext();  
	}
	echo "</table></div></fieldset></cente></div>";
	mysql_free_result($rpta->_queryID);
	$db->Close();
?>
