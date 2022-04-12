<?php

include '../scripts/conexion.php';
$dom = new DOMDocument();
$dom->load( 'xml_user/xml_opciones.xml' );
session_start();

$id_periodo=$_SESSION['periodo'];

if($id_periodo==""){return false;}

$fl=$db->Execute("Select idcuenta from entrega_tc where idcuenta='".$_GET['cta']."' and idperiodo=$id_periodo");

if($fl->fields['idcuenta']!=""){return false;}

function select_hora($id=""){
		
		$sel="<select id='$id' >\r\n";
			for($i=7;$i<=22;++$i)
			{
				$sel.="<option ='".$i."'>".$i."</option>\r\n";
			}
		$sel.="</select>\r\n";
		
		return $sel;
	}
	
function select_hora_m($id=""){
		
		$sel="<select id='$id' >\r\n";
				$sel.="<option ='00'>00</option>\r\n";
				$sel.="<option ='30'>30</option>\r\n";
		$sel.="</select>\r\n";
		
		return $sel;
}

if(isset($_GET['insert']) and $_GET['insert']=="ok"){
	$cta=$_GET['cta'];
	$fec_ent=$_GET['fec_ent'];
	$hor_ent=$_GET['hor_ent'];
	$dir=$_GET['dir'];
	$dpto=$_GET['dpto'];
	$prov=$_GET['prov'];
	$dist=$_GET['dist'];
	$ref_dir=$_GET['ref_dir'];
	$nom_eje=$_GET['nom_eje'];
	
	$sql="INSERT INTO sis_gestion.entrega_tc 
						( 
						idcuenta, 
						idperiodo, 
						f_entrega, 
						h_entrega, 
						dir_entrega, 
						coddist, 
						codprov, 
						coddpto, 
						ref, 
						n_ejecutivo
						)
						VALUES
						(
						'$cta', 
						'$id_periodo', 
						'$fec_ent', 
						'$hor_ent', 
						'$dir', 
						'$dist', 
						'$prov', 
						'$dpto', 
						'$ref_dir', 
						'$nom_eje'
						);";
	$db->Execute($sql);

}

?>
<style type="text/css">@import url("../includes/calendar/calendar-blue2.css");</style>
<script type="text/javascript" src="../includes/calendar/calendar.js"></script>
<script type="text/javascript" src="../includes/calendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="../includes/calendar/calendar-setup.js"></script>

<script type="text/javascript">
    function Ajax(){var xmlhttp=false;
            try {xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e)
            {try {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (E) {
                    xmlhttp = false;
                }
            }
            if (!xmlhttp && typeof XMLHttpRequest!='undefined'){
                xmlhttp = new XMLHttpRequest();
            }return xmlhttp;
    }
	
	function grabar(){
		var cta=document.getElementById("cta").value;
		var fec_ent=document.getElementById("fec_ent").value;
		var hor_ent=document.getElementById("hra_h").value+":"+document.getElementById("hra_m").value;
		var dir=document.getElementById("dir").value;
		var dpto=document.getElementById("dpto").value;
		var prov=document.getElementById("prov").value;
		var dist=document.getElementById("dist").value;
		var ref_dir=document.getElementById("ref_dir").value;
		var nom_eje=document.getElementById("nom_eje").value;
		
		ajax=Ajax();
		ajax.open("GET", "entrega_tc.php?insert=ok&cta="+cta+"&fec_ent="+fec_ent+"&hor_ent="+hor_ent+"&dir="+dir+"&dpto="+dpto+"&prov="+prov+"&dist="+dist+"&ref_dir="+ref_dir+"&nom_eje="+nom_eje,true);
		ajax.onreadystatechange=
			function() {
				if (ajax.readyState==4) {
					//window.close();
					
				}
			}
		ajax.send(null) 
		alert("Dato Guardado")
		//document.location.reload();
	}
</script>
<script type="text/javascript" src="../scripts/gs_val.js?id=<?php echo date("s");?>"></script>
<input style="visibility:hidden;position:absolute;" type="text" id="cta" value="<?php echo $_GET['cta'];?>"/>
<table>
<tr><td>Fecha Entrega:</td><td><input onkeyup='return false;' onkeydown="return false;"type="text" id="fec_ent" size="10"/><button id="bcalendario1" onclick="fec_ges();">...</button>
														<script type="text/javascript">
															Calendar.setup(	{
																inputField : "fec_ent", // ID of the input field
																ifFormat : "%Y-%m-%d", // the date format
																button : "bcalendario1", // ID of the button
																weekNumbers : false,
																range : [1900, 2050] } );
														</script></td></tr>
<tr><td>Hora Entrega:</td><td><?php echo select_hora('hra_h').":".select_hora_m('hra_m');?></td></tr>
<tr><td>Direccion:</td><td><input type="text" id="dir" size="35"/></td></tr>
<tr><td>Departamento:</td><td><select id="dpto" name="departamento" onchange="dpto();" >
							<option value="">Seleccione...</option>
							<?php
									
									$filas= $dom->getElementsByTagName( "dpto" );
																	
									foreach ($filas as $fila){
										echo "<option value='".$fila->getElementsByTagName( "iddpto" )->item(0)->nodeValue."'>".$fila->getElementsByTagName( "nombre" )->item(0)->nodeValue."</option>";
									}
									
									/*$total_a=count($_SESSION['dpto']);
									for($i=1;$i<=$total_a;$i++){
										$dpto=explode("*",$_SESSION['dpto'][$i]);
										echo "<option value='".$dpto[0]."'>".$dpto[1]."</option>";
									}*/
								
							?>
						</select></td></tr>
<tr><td>Provincia:</td><td><select id="prov" name="provincia" onchange="dist();">
							<option value="">Seleccione...</option>
						</select></td></tr>
<tr><td>Distrito:</td><td><select id="dist" name="distrito" >
								<option value="">Seleccione...</option>
							</select></td></tr>
<tr><td>Referencia Dir.:</td><td><input type="text" id="ref_dir" size="35"/></td></tr>
<tr><td>Nombre Ejecutivo:</td><td><input type="text" id="nom_eje" size="35"/></td></tr>
<tr><td colspan="2" align="right" ><input type="button" value="Guardar" onclick="grabar();"/><input type="button" value="Cancelar" onclick="window.close();"/></td></tr>

</table>