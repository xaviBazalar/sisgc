<?php
include '../scripts/conexion.php';
?>

<script>
	function Ajax(){var xmlhttp=false;
				try {xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e)
				{try {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (E) {
						xmlhttp = false;
					}
				}
				if (!xmlhttp && typeof XMLHttpRequest!='undefined')
				{
					xmlhttp = new XMLHttpRequest();
				}return xmlhttp;
	}
	
	function buscar(web){
		
		flag=0;	
		if(document.getElementById('result')){
			var divResultado = document.getElementById('result');
		}
		
		
		if(document.getElementById('numdoc')){
			var n_doc=document.getElementById('numdoc').value
			if(n_doc!=""){
				var web=web+"&ndoc="+n_doc
				flag=1
			}
		
		}
		if(document.getElementById('name_gs')){
				var name=document.getElementById('name_gs').value
			if(name!=""){
				var web=web+"&name="+name
				flag=1
			}
		
		}
		if(document.getElementById('grup')){
				var grup=document.getElementById('grup').value
			if(grup!=""){
				var web=web+"&grup="+grup
			}
		
		}
		
		if(document.getElementById('ciclo')){
				var ciclo=document.getElementById('ciclo').value
			if(ciclo!=""){
				var web=web+"&ciclo="+ciclo
			}
		
		}
		
		if(document.getElementById('u_prove')){
				var prov=document.getElementById('u_prove').value
			if(prov!=""){
				var web=web+"&prov="+prov
				flag=1;
			}/*else{
				alert("Elija un Proveedor");
				return false;
			}*/
		
		}
		
		if(document.getElementById('u_cart')){
				var cart=document.getElementById('u_cart').value
			if(cart!=""){
				var web=web+"&cart="+cart
				flag=1;
			}
		
		}
		
		if(flag==0){ alert("Elija al menos un campo para la busqueda");return false;}
			ajax=Ajax();
			ajax.open("GET",web,true);
			divResultado.innerHTML = "<center><img src='../imag/cargando.gif'/></center>"

			ajax.onreadystatechange=
				function() {
				divResultado.innerHTML = "<center><img src='../imag/cargando.gif'/></center>"
				if (ajax.readyState==4) {
					divResultado.innerHTML = ajax.responseText
				}
			}
			ajax.send(null)
			return;
	}
</script>
<script src="../style/zpform/utils/utils.js" type="text/javascript"></script>
	<script src="../style/zpform/utils/transport.js" type="text/javascript"></script>
	<script src="../style/zpform/src/form.js" type="text/javascript"></script>
	<link href="../style/zpform/themes/alternate.css" rel="stylesheet" />
	<script src="../spry/SpryTabbedPanels.js" type="text/javascript"></script>
	<link href="../spry/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="../style/blue/menu.css" />
	<link href="../style/blue/blue.css" rel="stylesheet" type="text/css" />
	
	<link href="../style/blue/print.css" rel="stylesheet" type="text/css" media="print" />
	<link href="../style/tables.css" type="text/css" rel="stylesheet"/>
	<link href="../style/filas.css" type="text/css" rel="stylesheet" />
<div id="main_sys" >
<table width="780" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<table style="font-size:11px;" border="0" class="blue">
				<!--<form name="frmDatos">-->
				<tr>
					<td>Proveedor</td>
                                        <td>Cartera</td>
                                        <td>Resultados</td>
					<td>N° Documento</td>
					<td>Nombres</td>
                                        <td>Grupo</td>
										<td>Ciclo</td>
                                        <td>Buscar Todo</td>

                                        <!--<td>Ubicabilidad</td>
					<td>Ciclo</td>-->
				</tr>
				<tr>
					<td>

						<input type="hidden" name="orden" value="1" />
						<input type="hidden" name="ad" value="a" />
                                                <?php  $result = $db->Execute("SELECT * FROM proveedores");?>
						<select id='u_prove' onchange='cart();'>
                                                    <option value="">--Seleccionar--</option>
                                                    <?php
                                                    while (!$result->EOF) {
                                                    echo "<option value='".$result->fields['idproveedor']."'>".$result->fields['proveedor']."</option>";
                                                    $result->MoveNext();                                    }
                                                    ?>
						</select>
                                        </td>
                                        <td>
                                                <select id='u_cart' onchange='r_cart();'>
							<option value="">--Seleccionar--</option>
						</select>
					</td>
                                        <td>
                                           	<select id="r_cartera" sonchange="cambia_gestion();">
							<option value="">--Seleccionar--</option>
						</select>
                                        </td>

					<td><input type="text" id="numdoc" value="" size="18" maxlength="18" /></td>
					<td><input type="text" id="name_gs" value="" size="35" maxlength="35" /></td>
										<td><input type="text" id="grup"  value="" size="12" maxlength="10" /></td>
										<td><input type="text" id="ciclo" value="" size="12" maxlength="10" /></td>
                                        <td><input type="checkbox" name="todo" value="1" /></td>
                                        <td><input class='btn' type="button" value="Buscar" onclick="buscar('consulta_res.php?id=&pag=1');" /></td>
										
                                        <!--<td><select name="ubicabilidad"><option value="">Todos</option><option label="07:00 - 10:00" value="1">07:00 - 10:00</option>
                                            <option label="10:01 - 14:00" value="2">10:01 - 14:00</option>
                                            <option label="14:01 - 18:00" value="3">14:01 - 18:00</option>
                                            <option label="18:01 - 21:00" value="4">18:01 - 21:00</option>
                                            </select></td>

					<td><input type="text" name="ciclo" value="" size="3" maxlength="2" style="text-align:right;" /></td>-->
				</tr>
				<tr>
					<td valign="middle" colspan="6">
						
					</td>
				</tr>
				<!--</form>-->
			</table>
<div id="c_gestion">
	<!--aca va el tbody-->
</div>
		</td>
	</tr>
</table>
</div>
<div id="result">

</div>
<?php $db->Close(); ?>
<!--<tr onMouseOver="this.className='rowshover'" onMouseOut="this.className='gris'" class="gris">

    <td align="right"><b></b></td>
    <td align="center">//</td>
    <td><a href=''></a></td>
    <td></td>
    <td></td>
    <td></td>
    <td align="right">&nbsp;</td>
    <td align="center">&nbsp;&nbsp;</td>
    <td align="center"></td>
    <td align="center">&nbsp;&nbsp;</td>
    <td align="right">&nbsp;</td>

    <td></td>
    <td></td>
 </tr>-->