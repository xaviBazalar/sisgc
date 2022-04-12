<?php

include '../scripts/conexion.php';
$dom = new DOMDocument();
$dom->load( 'xml_user/xml_opciones.xml' );

session_start();

$id_periodo=$_SESSION['periodo'];

if($id_periodo==""){return false;}

$fl=$db->Execute("Select idcuenta from validacion_tc_clientes where idcuenta='".$_GET['cta']."' and idperiodo=$id_periodo");

if($fl->fields['idcuenta']!=""){return false;}

if(isset($_GET['insert']) and $_GET['insert']=="ok"){

	$cta=$_GET['cta'];
	$tipo_doc=$_GET['tipo_doc'];
	$nro_doc=$_GET['nro_doc'];
	$fec_nac=$_GET['fec_nac'];
	$ape_p=$_GET['ape_p'];
	$ape_m=$_GET['ape_m'];
	$nom=$_GET['nom'];
	$sexo=$_GET['sexo'];
	$fono=$_GET['fono'];
	$cel=$_GET['cel'];
	$dir=$_GET['dir'];
	$dpto=$_GET['dpto'];
	$prov=$_GET['prov'];
	$dist=$_GET['dist'];
	$ref_dir=$_GET['ref_dir'];
	$mail=$_GET['mail'];
	
	$sql="
		INSERT INTO sis_gestion.validacion_tc_clientes 
					( 
					idcuenta, 
					idperiodo, 
					iddoi, 
					nro_doc, 
					fec_nac, 
					sexo, 
					ape_p,
					ape_m,
					nombre,
					fono, 
					celular, 
					direccion, 
					coddist, 
					codprov, 
					coddpto, 
					referencia, 
					email
					)
					VALUES
					( 
					'$cta', 
					'$id_periodo', 
					'$tipo_doc', 
					'$nro_doc', 
					'$fec_nac', 
					'$sexo',
					'$ape_p',
					'$ape_m',
					'$nom',
					'$fono', 
					'$cel', 
					'$dir', 
					'$dist', 
					'$prov', 
					'$dpto', 
					'$ref_dir', 
					'$mail'
					);";
	$db->debug=true;
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
		var tipo_doc=document.getElementById("doi").value;
		var nro_doc=document.getElementById("nro_doc").value;
		var fec_nac=document.getElementById("fec_nac").value;
		var ape_p=document.getElementById("ape_p").value;
		var ape_m=document.getElementById("ape_m").value;
		var nom=document.getElementById("nom").value;
		var sexo=document.getElementById("sexo").value;
		var fono=document.getElementById("fono").value;
		var cel=document.getElementById("cel").value;
		var dir=document.getElementById("dir").value;
		var dpto=document.getElementById("dpto").value;
		var prov=document.getElementById("prov").value;
		var dist=document.getElementById("dist").value;
		var ref_dir=document.getElementById("ref_dir").value;
		var mail=document.getElementById("mail").value;
		
		ajax=Ajax();
		ajax.open("GET", "validacion_tc.php?insert=ok&cta="+cta+"&tipo_doc="+tipo_doc+"&nro_doc="+nro_doc+"&fec_nac="+fec_nac+"&ape_p="+ape_p+"&ape_m="+ape_m+"&nom="+nom+"&sexo="+sexo+"&fono="+fono+"&cel="+cel+"&dir="+dir+"&dpto="+dpto+"&prov="+prov+"&dist="+dist+"&ref_dir="+ref_dir+"&mail="+mail,true);
		ajax.onreadystatechange=
			function() {
				if (ajax.readyState==4) {
					
				}
			}
		ajax.send(null) 
		alert("Dato guardado");
		//document.location.reload();
	}
</script>
<script type="text/javascript" src="../scripts/gs_val.js?id=<?php echo date("s");?>"></script>
<input style="visibility:hidden;position:absolute;" type="text" id="cta" value="<?php echo $_GET['cta'];?>"/>

<table>
<tr><td>Tipo Doc.:</td><td><select id="doi" >
							<option value="">Seleccione...</option>
							<?php
								$result = $db->Execute("SELECT * FROM doi where idestado=1");
         
								while (!$result->EOF) {
									echo "<option value='".$result->fields['iddoi']."'>".$result->fields['doi']."</option>";
									$result->MoveNext();  
								}
							?>
						</select></td></tr>
<tr><td>Nro Doc.:</td><td><input type="text" id="nro_doc" size="15"/></td></tr>						
<tr><td>Fecha Nac:</td><td><input onkeyup='return false;' onkeydown="return false;"type="text" id="fec_nac" size="10"/><button id="bcalendario1" onclick="fec_ges();">...</button>
														<script type="text/javascript">
															Calendar.setup(	{
																inputField : "fec_nac", // ID of the input field
																ifFormat : "%Y-%m-%d", // the date format
																button : "bcalendario1", // ID of the button
																weekNumbers : false,
																range : [1900, 2050] } );
														</script></td></tr>
<tr><td>Ape.Paterno:</td><td><input type="text" id="ape_p" size="35"/></td></tr>
<tr><td>Ape.Materno:</td><td><input type="text" id="ape_m" size="35"/></td></tr>
<tr><td>Nombres:</td><td><input type="text" id="nom" size="35"/></td></tr>
<tr><td>Sexo:</td><td><select id="sexo" >
								<option value="">Seleccione...</option>
								<option value="M">M</option>
								<option value="F">F</option>
							</select></td></tr>
<tr><td>Nuevo Telf.Fijo:</td><td><input type="text" id="fono" size="15"/></td></tr>
<tr><td>Nuevo Telf.Cel:</td><td><input type="text" id="cel" size="15"/></td></tr>
<tr><td>Nueva Direccion:</td><td><input type="text" id="dir" size="35"/></td></tr>
<tr><td>Nuevo Departamento:</td><td><select id="dpto" name="departamento" class="zpFormRequired" onchange="dpto();" >
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
<tr><td>Nueva Provincia:</td><td><select id="prov" name="provincia" onchange="dist();">
							<option value="">Seleccione...</option>
						</select></td></tr>
<tr><td>Nuevo Distrito:</td><td><select id="dist" name="distrito" onchange="if(this.value!=''){document.getElementById('gs_dist').className='zpIsValid'}else{document.getElementById('gs_dist').className='zpNotValid'}">
								<option value="">Seleccione...</option>
							</select></td></tr>
<tr><td>Referencia Dir.:</td><td><input type="text" id="ref_dir" size="35"/></td></tr>
<tr><td>Email:</td><td><input type="text" id="mail" size="35"/></td></tr>
<tr><td colspan="2" align="right" ><input type="button" value="Guardar" onclick="grabar();"/><input type="button" value="Cancelar" onclick="window.close();"/></td></tr>

</table>