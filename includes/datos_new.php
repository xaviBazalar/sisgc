<?php
	if(!isset($_SESSION['campo'])){
		if(!isset($_GET['idCl'])){
			return false;
		}
	}
	require 'class/dat_gest.class.php';
	$gestion= new dat_gest();
	$nivel=$gestion->tab_n;
	
	$id=$_GET['idCl'];
	$_SESSION['cli_actual']=$id;
	$cliente = $db->Execute("SELECT *   FROM  clientes c
							JOIN doi d ON c.iddoi=d.iddoi
							JOIN personerias pr ON c.idpersoneria=pr.idpersoneria
							where c.idcliente='$id'");

?>	
	<style type="text/css">
		.oculto
		{
			display:none;
		}
		
		.numero_oculto
		{
			display:none;
			text-align:right;
		}
	</style>
	<input id="id_cli_gs" type="hidden" value="<?php echo $id;?>"/>
	<input id="ws" type="text" style="visibility:hidden;position:absolute;" value='<?php echo $_SESSION['web'];?>'/>
	<input id="id_gt" type="hidden" value="<?php if(isset($_GET['idGt'])) echo $_GET['idGt'];?>"/>
	
	
<?php


	
	if(isset($_GET['idT'])){
		$idT=$_GET['idT'];
		echo "<input id='id_tarea' type='hidden' value='$idT'/>";
	}
	
	if(isset($_GET['temp'])){
		if($_GET['temp']!="ok"){
			header ("Location : functions/consulta_todo.php?id=&pag=1");
			return false;
		}
		$idTm=$_GET['temp'];
		echo "<input id='id_temp' type='hidden' value='$idTm'/>";
	}
	
	
			
				$hora=date("H");
				$id_user=$_SESSION['iduser'];
				/*if($_SESSION['nivel']==2){
					$sql_tot="
								SELECT
									tt.hora,
									COUNT(DISTINCT(tt.idcliente)) tot_cli,
									COUNT(*) tot_ges,
									SUM(tt.idresultado=2) tot_prom,
									SUM(tt.idtipocontactabilidad=1) CD,
									SUM(tt.idtipocontactabilidad=2) CI,
									SUM(tt.idtipocontactabilidad=1 OR tt.idtipocontactabilidad=2) tot_CONT,
									SUM(tt.tipo_fono='C') Cel,
									SUM(tt.tipo_fono='F') Fij,
									COUNT(DISTINCT(tt.hora)) tot_hor
									FROM
									(
										SELECT 	
											CASE
												WHEN telefono LIKE '9%' AND LENGTH(telefono)=9 THEN 'C' ELSE 'F'
											END tipo_fono,
											c.idcliente,g.idcuenta,g.idcontactabilidad,idresultado,
											idjustificacion,fecges,HOUR(horges) hora,tc.idtipocontactabilidad
										FROM gestiones g
										JOIN telefonos t ON g.idtelefono=t.idtelefono
										JOIN contactabilidad ct ON g.idcontactabilidad=ct.idcontactabilidad
										JOIN tipo_contactabilidad tc ON ct.idtipocontactabilidad=tc.idtipocontactabilidad
										JOIN cuentas c ON g.idcuenta=c.idcuenta
										WHERE g.usureg=$id_user
										AND fecges=DATE(NOW())
										-- AND HOUR(horges)=$hora
										GROUP BY g.fecreg
									) AS tt
									-- GROUP BY tt.hora
	
					
							";
							
					$qr=$db->Execute($sql_tot);
					
					echo "<div style='position:absolute;right:-1px;top:1px;'>";
					echo "<table style='border: 1px solid black;float:left;' cellspacing=0 cellpadding=0 >";
					echo "<tr>
											<td style='background-color:black;'></td>
											<td style='border-right: 1px solid black;padding-left:4px;padding-right:4px;background-color:chartreuse;'>
												CLIENTES 
											</td>
											<td style='border-right: 1px solid black;padding-left:4px;padding-right:4px;background-color:chartreuse;'>
												GESTIONES
											</td>
											<td style='border-right: 1px solid black;padding-left:4px;padding-right:4px;background-color:chartreuse;'>
												PROMESAS 
											</td>
											<td style='padding-left:4px;padding-right:4px;background-color:chartreuse;'>
												CONTACTOS 
											</td>
									  </tr>";
					$t_cli=round(($qr->fields['tot_cli']/$qr->fields['tot_hor']));
					$t_ges=round(($qr->fields['tot_ges']/$qr->fields['tot_hor']));
					$t_pro=round(($qr->fields['tot_prom']/$qr->fields['tot_hor']));
					$t_cont=round(($qr->fields['tot_CONT']/$qr->fields['tot_hor']));
					
					if($t_cli<18){
							$ima_cli="<img src='img/s_rojo.png' title='Debajo del Promedio' />";
					}else if($t_cli==18){
							$ima_cli="<img src='img/s_amarillo.png' title='En el Promedio' />";
					}else if($t_cli>18){
							$ima_cli="<img src='img/s_verde.png' title='Encima del Promedio' />";
					}
					
					if($t_ges<20){
							$ima_gs="<img src='img/s_rojo.png' title='Debajo del Promedio' />";
					}else if($t_ges==20){
							$ima_gs="<img src='img/s_amarillo.png' title='En el Promedio' />";
					}else if($t_ges>20){
							$ima_gs="<img src='img/s_verde.png' title='Encima del Promedio' />";
					}
					
					if($t_pro<1){
							$ima_p="<img src='img/s_rojo.png' title='Debajo del Promedio' />";
					}else if($t_pro==1){
							$ima_p="<img src='img/s_amarillo.png' title='En el Promedio' />";
					}else if($t_pro>1){
							$ima_p="<img src='img/s_verde.png' title='Encima del Promedio' />";
					}
					
					if($t_cont<7){
							$ima_c="<img src='img/s_rojo.png' title='Debajo del Promedio' />";
					}else if($t_cont==7){
							$ima_c="<img src='img/s_amarillo.png' title='En el Promedio' />";
					}else if($t_cont>7){
							$ima_c="<img src='img/s_verde.png' title='Encima del Promedio' />";
					}
					
					
					
					while(!$qr->EOF){
						echo "<tr style='border: 1px solid black;'>
									<td align='left' style='background-color:black;color:white;border: 1px solid #6FC44E ;padding-left:4px;padding-right:4px;'>PROMEDIO</td>
									<td align='center' style='border: 1px solid black;'>
										".$t_cli."
										$ima_cli
									</td>
									<td align='center' style='border: 1px solid black;'>
										".$t_ges."
										$ima_gs
									</td>
									<td align='center' style='border: 1px solid black;'>
										".$t_pro."
										$ima_p
									</td>
									<td align='center' style='border: 1px solid black;'>
										".$t_cont."
										$ima_c
									</td>
							  </tr>";
						echo "<tr style='border: 1px solid black;'>
									<td align='left' style='background-color:#7A7A7A;color:white;border: 1px solid black;padding-left:4px;padding-right:4px;'>TOTALES</td>
									<td align='center' style='border: 1px solid black;'>
										".$qr->fields['tot_cli']."
										
									</td>
									<td align='center' style='border: 1px solid black;'>
										".$qr->fields['tot_ges']."
										
									</td>
									<td align='center' style='border: 1px solid black;'>
										".$qr->fields['tot_prom']."
										
									</td>
									<td align='center' style='border: 1px solid black;'>
										".$qr->fields['tot_CONT']."
										
									</td>
							  </tr>";
							 $qr->MoveNext();
					}		
					echo "</table>";
					echo "<img src='img/semaforo.png' alt='Leyenda' style='float:left;' />";
					echo "</div>";
				}	*/
				
?>
<div id="areaList" >
					<a href="javascript:imprimir()"><span class="impresion"></span></a>
					<!-- D A T O S  D E L  C L I E N T E -->
					<table width="100%" id="design4">
						<caption>Datos del cliente</caption>
						<tr class="noborde">
							<td valign="top">
								<table width="100%" id="design3">
									<tr>
										<td  class="cellhead">Cliente:</td><td><?php echo utf8_encode($cliente->fields['cliente']);?></td>
										
										<?php
										
												function restaFechas($dFecIni, $dFecFin)
												{
													$dFecIni = str_replace("-","",$dFecIni);
													$dFecIni = str_replace("/","",$dFecIni);
													$dFecFin = str_replace("-","",$dFecFin);
													$dFecFin = str_replace("/","",$dFecFin);
												 
													ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecIni, $aFecIni);
													ereg( "([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})", $dFecFin, $aFecFin);
												 
													$date1 = mktime(0,0,0,$aFecIni[2], $aFecIni[1], $aFecIni[3]);
													$date2 = mktime(0,0,0,$aFecFin[2], $aFecFin[1], $aFecFin[3]);
												 

													return round(($date2 - $date1) / (60 * 60 * 24));
												}
												
												$d_t=$db->Execute("SELECT c.cliente,d.direccion,
												(SELECT nombre FROM ubigeos WHERE coddpto=d.coddpto AND codprov=00 AND coddist=00) dpto,
												(SELECT nombre FROM ubigeos WHERE coddpto=d.coddpto AND codprov=d.codprov AND coddist=00) prov,
												(SELECT nombre FROM ubigeos WHERE coddpto=d.coddpto AND codprov=d.codprov AND coddist=d.coddist) dist
														,t.telefono
														FROM clientes c
														left JOIN direcciones d ON c.idcliente=d.idcliente -- and d.coddpto and d.codprov and d.coddist
														left JOIN telefonos t ON c.idcliente=t.idcliente
														WHERE c.idcliente='$id' LIMIT 0,1 ");
											
												echo 	"<td  class='cellhead'>Direccion:</td><td>".utf8_encode($d_t->fields['direccion'])." * ".$d_t->fields['dpto']."-".$d_t->fields['prov']."-".$d_t->fields['dist']."</td>
														<td  class='cellhead'>Tel:</td><td>".$d_t->fields['telefono']."</td>";

										?>
										
										<td class="cellhead">Personeria:</td><td><?php echo utf8_encode($cliente->fields['personerias']);?></td>
										<td class="cellhead">Documento:</td><td><?php echo utf8_encode($cliente->fields['doi'])."-";?><?php echo utf8_encode($cliente->fields['idcliente']);?></td>
										
										<!--<td><input type="button" class="btn"  value="Ver Contactos" onclick='contactos_rpp();'/></td>-->
									</tr>
								</table>
							</td>
		
						</tr>
						
					</table>
				</div>
<div id="TabbedPanels1" class="TabbedPanels">

    <ul class="TabbedPanelsTabGroup">
	
		<?php 
			$gestion->tabs($nivel);	/*Define Pestañas de Acuerdo al Nivel*/
		?>
    </ul>
			<?php
					
				/*Inicio de Contenido*/
					if($nivel=="1" or $nivel=="2" or $nivel=="5" or $nivel=="4"){
						require 'b_cliente.php'; 
					}
					
						$dom = new DOMDocument();
						$dom->load( 'functions/xml_user/xml_opciones.xml' );	
							
					if($nivel=="3" or $nivel=="2" ){
						 if(isset($_SESSION['campo'])){
							require 'b_gestion_campo.php';
						 } else{
							require 'b_gestion.php';
						 }
					}
					
					if($nivel=="1" or $nivel=="2" or $nivel=="5"){
						require 'b_dir_fono_cont.php';
					}
				/*Fin de Contenido*/
			$db->Close();
			$db2->Close();
			?>
		
		
<script type="text/javascript">
	var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1", {defaultTab:<?php if(isset($_SESSION['campo']) or isset($_GET['idGt']) ){echo "1";}else{echo "0";} ?>});
</script>
<?php if($_SESSION['nivel']!="2"){ ?>
<script type="text/javascript">
	var TabbedPanels2 = new Spry.Widget.TabbedPanels("TabbedPanels2", {defaultTab:<?php if(isset($_SESSION['campo'])){echo "1";}else{echo "0";} ?>});
</script>
<?php }?>
