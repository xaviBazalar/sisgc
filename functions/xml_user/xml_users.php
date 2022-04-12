<?php
	ini_set('memory_limit', '-1');
	set_time_limit(1800);
	include '../../define_con.php';
	
	$query=$db->Execute("SELECT MAX(gt.fecges) mx_g,SUM(gt.idgestion IS NULL) t_ge,cp.idperiodo,YEAR(gt.fecges) ano,MONTH(gt.fecges) mes,
						gt.idgestion,cu.idusuario,c.idcliente,pv.proveedor,cr.cartera,m.monedas,c.cliente,
						cu.idcuenta,cp.diasmora,cp.grupo,cp.imptot,cp.ciclo,cp.idestado 
						FROM cuentas cu JOIN clientes c ON cu.idcliente=c.idcliente 
						JOIN monedas m ON cu.idmoneda=m.idmoneda 
						JOIN carteras cr ON cu.idcartera=cr.idcartera 
						JOIN proveedores pv ON pv.idproveedor=cr.idproveedor 
						JOIN productos pr ON pr.idproducto=cu.idproducto	 
						JOIN cuenta_periodos cp ON cp.idcuenta=cu.idcuenta and cp.idestado='1' 
						LEFT JOIN gestiones gt ON cp.idcuenta=gt.idcuenta AND MONTH(gt.fecreg)='".date("m")."' AND MONTH(gt.fecges)='".date("m")."' 
						WHERE cp.idusuario=52 and cp.idperiodo=(select idperiodo from periodos where year(fecini)=".date("Y")." and Month(fecini)=".date("m").") AND cu.idcartera=(select idcartera from usuarios where idusuario='52') and cp.idestado='1' 
						GROUP BY cu.idcuenta ORDER BY idestado DESC, cp.imptot DESC,idagente DESC, gt.fecreg DESC  ");
			
	//$num_rows = mysql_num_rows($query->_queryID);
	


	$mi_xml = new DomDocument('1.0', 'UTF-8');
	$filas = $mi_xml -> createElement("cuentas");
	$mi_xml -> appendChild($filas);
	$pg=1;
	$vr=0;
	
	while(!$query->EOF){
			
			$fila_1 = $mi_xml -> createElement( "fila" );
				
				$attr_id= $mi_xml->createAttribute('id');
				$fila_1 ->appendChild( $attr_id );
				$mi_xml ->appendChild( $fila_1 );

				$valor =  $mi_xml ->createTextNode($query->fields['idcuenta']);
				$attr_id -> appendChild($valor);

						$fec_ges_max = $mi_xml -> createElement( "fec_ges_max" );
						$fec_ges_max->appendChild( $mi_xml -> createTextNode($query->fields['mx_g']) );
						$fila_1->appendChild( $fec_ges_max );
						
						$t_gestion = $mi_xml -> createElement( "t_gestion" );
						$t_gestion->appendChild( $mi_xml -> createTextNode($query->fields['t_ge']) );
						$fila_1->appendChild( $t_gestion );
						
						$periodo = $mi_xml -> createElement( "periodo" );
						$periodo->appendChild( $mi_xml -> createTextNode($query->fields['idperiodo']) );
						$fila_1->appendChild( $periodo );
						
						$ano = $mi_xml -> createElement( "ano" );
						$ano ->appendChild( $mi_xml -> createTextNode($query->fields['ano']) );
						$fila_1 ->appendChild( $ano );
						
						$mes = $mi_xml -> createElement( "mes" );
						$mes ->appendChild( $mi_xml -> createTextNode($query->fields['mes']) );
						$fila_1 ->appendChild( $mes );
						
						$id_gestion = $mi_xml -> createElement( "idgestion" );
						$id_gestion ->appendChild( $mi_xml -> createTextNode($query->fields['idgestion']) );
						$fila_1 ->appendChild( $id_gestion );
						
						$id_usuario = $mi_xml -> createElement( "idusuario" );
						$id_usuario ->appendChild( $mi_xml -> createTextNode($query->fields['idusuario']) );
						$fila_1 ->appendChild( $id_usuario );
						
						$idcliente = $mi_xml -> createElement( "idcliente" );
						$idcliente ->appendChild( $mi_xml -> createTextNode($query->fields['idcliente']) );
						$fila_1 ->appendChild( $idcliente );
						
						$proveedor = $mi_xml -> createElement( "proveedor" );
						$proveedor ->appendChild( $mi_xml -> createTextNode($query->fields['proveedor']) );
						$fila_1 ->appendChild( $proveedor );
						
						$moneda = $mi_xml -> createElement( "moneda" );
						$moneda  ->appendChild( $mi_xml -> createTextNode($query->fields['monedas']) );
						$fila_1 ->appendChild( $moneda  );
						
						$cliente = $mi_xml -> createElement( "cliente" );
						$cliente ->appendChild( $mi_xml -> createTextNode(utf8_encode($query->fields['cliente'])) );
						$fila_1 ->appendChild( $cliente );
						
						$idcuenta = $mi_xml -> createElement( "idcuenta" );
						$idcuenta ->appendChild( $mi_xml -> createTextNode($query->fields['idcuenta']) );
						$fila_1 ->appendChild( $idcuenta );
						
						$diasmora = $mi_xml -> createElement( "diasmora" );
						$diasmora ->appendChild( $mi_xml -> createTextNode($query->fields['diasmora']) );
						$fila_1 ->appendChild( $diasmora );
						
						$grupo = $mi_xml -> createElement( "grupo" );
						$grupo ->appendChild( $mi_xml -> createTextNode($query->fields['grupo']) );
						$fila_1 ->appendChild( $grupo );
						
						$imptot = $mi_xml -> createElement( "imptot" );
						$imptot ->appendChild( $mi_xml -> createTextNode($query->fields['imptot']) );
						$fila_1 ->appendChild( $imptot );
						
						$ciclo = $mi_xml -> createElement( "ciclo" );
						$ciclo ->appendChild( $mi_xml -> createTextNode($query->fields['ciclo']) );
						$fila_1 ->appendChild( $ciclo );
						
						$idestado = $mi_xml -> createElement( "idestado" );
						$idestado ->appendChild( $mi_xml -> createTextNode($query->fields['idestado']) );
						$fila_1 ->appendChild( $idestado );

			$filas ->appendChild( $fila_1 );

		$query->MoveNext();	
	}
	echo $vr;
	$mi_xml -> formatOutput = true;
	$strings_xml = $mi_xml -> saveXML();
	$mi_xml -> save('xml_52.xml');

	$dom = new DOMDocument();
	$dom->load( 'xml_52.xml' );	
	$filas= $dom->getElementsByTagName( "fila" );	
	
	$n=0;

	foreach ($filas as $fila){
		//echo $fila->getElementsByTagName( "cliente" )->item(0)->nodeValue."</br>";
	}
	
	/*function cmp($a, $b)
	{
		return strcmp($a["fruta"], $b["fruta"]);
	}*/
	
	function cmp($a, $b)
	{
		if ($a["imptot"]== $b["imptot"]) {
			return 0;
		}
		return ($a["imptot"] < $b["imptot"]) ? -1 : 1;
	}
		
	$frutas[0]["imptot"] = 10;
	$frutas[1]["imptot"] = 1;
	$frutas[2]["imptot"] = 4;

	usort($frutas, "cmp");
	//echo "<pre>";
	foreach ($frutas as $clave => $valor) {
		//echo $valor['imptot']."<br>";
		//var_dump($valor);
		//echo "$clave: $valor\n";
	}
	
	//--------------------------------------------------------------
	$mi_xml = new DomDocument('1.0', 'UTF-8');
	$filas = $mi_xml -> createElement("opciones");
	$mi_xml -> appendChild($filas);						
							
							$sql=$db->Execute("select idvalidaciones,validaciones from validaciones where idestado=1 order by idvalidaciones");
							$a=0;
							while(!$sql->EOF){

								$fila_1 = $mi_xml -> createElement( "validaciones" );
								
								$ciclo = $mi_xml -> createElement( "idvalidacion" );
								$ciclo ->appendChild( $mi_xml -> createTextNode($sql->fields['idvalidaciones'] ));
								$fila_1 ->appendChild( $ciclo );
								
								$idestado = $mi_xml -> createElement( "validacion" );
								$idestado ->appendChild( $mi_xml -> createTextNode($sql->fields['validaciones'] ));
								$fila_1 ->appendChild( $idestado );
								
								$filas ->appendChild( $fila_1 );	
								
								$sql->MoveNext();
							}
							mysql_free_result($sql->_queryID);
						
							//----------------------------------------------------------------
							
							$sql=$db->Execute("select idorigendireccion,origendireccion from origen_direcciones where idestado=1 order by origendireccion");
							$a=0;
							while(!$sql->EOF){
															
								$fila_1 = $mi_xml -> createElement( "origen_dir" );
								
								$ciclo = $mi_xml -> createElement( "idoridir" );
								$ciclo ->appendChild( $mi_xml -> createTextNode($sql->fields['idorigendireccion'] ));
								$fila_1 ->appendChild( $ciclo );
								
								$idestado = $mi_xml -> createElement( "origendir" );
								$idestado ->appendChild( $mi_xml -> createTextNode($sql->fields['origendireccion'] ));
								$fila_1 ->appendChild( $idestado );
								
								$filas ->appendChild( $fila_1 );	
								$sql->MoveNext();	
							}
							mysql_free_result($sql->_queryID);	

							//-----------------------------------------------------------------

							$dpto = $db->Execute("SELECT coddpto,nombre FROM ubigeos  WHERE codprov=00 AND coddist=00");
							$a=0;
							while(!$dpto->EOF){
							
								$fila_1 = $mi_xml -> createElement( "dpto" );
								
								$ciclo = $mi_xml -> createElement( "iddpto" );
								$ciclo ->appendChild( $mi_xml -> createTextNode($dpto->fields['coddpto'] ));
								$fila_1 ->appendChild( $ciclo );
								
								$idestado = $mi_xml -> createElement( "nombre" );
								$idestado ->appendChild( $mi_xml -> createTextNode($dpto->fields['nombre'] ));
								$fila_1 ->appendChild( $idestado );
								
								$filas ->appendChild( $fila_1 );	
								
								$dpto->MoveNext();
							}
							mysql_free_result($dpto->_queryID);	
							
							//-----------------------------------------------------------------
							
							$sql=$db->Execute("select idorigentelefono,origentelefono from origen_telefonos where idestado=1 order by origentelefono");
							$a=0;
							while(!$sql->EOF){
								
								$fila_1 = $mi_xml -> createElement( "origen_tel" );
								
								$ciclo = $mi_xml -> createElement( "idoritel" );
								$ciclo ->appendChild( $mi_xml -> createTextNode($sql->fields['idorigentelefono'] ));
								$fila_1 ->appendChild( $ciclo );
								
								$idestado = $mi_xml -> createElement( "origentel" );
								$idestado ->appendChild( $mi_xml -> createTextNode($sql->fields['origentelefono'] ));
								$fila_1 ->appendChild( $idestado );
								
								$filas ->appendChild( $fila_1 );	
								
								$sql->MoveNext();	
							}
							mysql_free_result($sql->_queryID);	
							
							//-----------------------------------------------------------------
							
							$sql=$db->Execute("select iddoi,doi from doi");
							$a=0;
							while(!$sql->EOF){
								
								$fila_1 = $mi_xml -> createElement( "documento" );
								
								$ciclo = $mi_xml -> createElement( "iddoi" );
								$ciclo ->appendChild( $mi_xml -> createTextNode($sql->fields['iddoi'] ));
								$fila_1 ->appendChild( $ciclo );
								
								$idestado = $mi_xml -> createElement( "doi" );
								$idestado ->appendChild( $mi_xml -> createTextNode($sql->fields['doi'] ));
								$fila_1 ->appendChild( $idestado );
								
								$filas ->appendChild( $fila_1 );	
								
								$sql->MoveNext();	
							}
							mysql_free_result($sql->_queryID);	
							
							//-----------------------------------------------------------------
							
							$sql=$db->Execute("select idparentesco,parentescos from parentescos");
							$a=0;
							while(!$sql->EOF){
								
								$fila_1 = $mi_xml -> createElement( "parentescos" );
								
								$ciclo = $mi_xml -> createElement( "idparentesco" );
								$ciclo ->appendChild( $mi_xml -> createTextNode($sql->fields['idparentesco'] ));
								$fila_1 ->appendChild( $ciclo );
								
								$idestado = $mi_xml -> createElement( "parentesco" );
								$idestado ->appendChild( $mi_xml -> createTextNode($sql->fields['parentescos'] ));
								$fila_1 ->appendChild( $idestado );
								
								$filas ->appendChild( $fila_1 );	
								
								$sql->MoveNext();	
							}
							mysql_free_result($sql->_queryID);	
							
							//-----------------------------------------------------------------
							
							$sql=$db->Execute("select idorigenemail,origenemail from origen_emails where idestado=1 order by idorigenemail");
							$a=0;
							while(!$sql->EOF){
								++$a;
										
								$fila_1 = $mi_xml -> createElement( "origen_mail" );
								
								$ciclo = $mi_xml -> createElement( "idorimail" );
								$ciclo ->appendChild( $mi_xml -> createTextNode($sql->fields['idorigenemail'] ));
								$fila_1 ->appendChild( $ciclo );
								
								$idestado = $mi_xml -> createElement( "orimail" );
								$idestado ->appendChild( $mi_xml -> createTextNode($sql->fields['origenemail'] ));
								$fila_1 ->appendChild( $idestado );
								
								$filas ->appendChild( $fila_1 );	
								
								$sql->MoveNext();	
							}
							mysql_free_result($sql->_queryID);
							
							//----------------------------------------------------------------------
							
							
							$sql="SELECT c.idcartera,p.idproveedor,a.idactividad,a.actividad FROM actividad_carteras ac
									JOIN actividades a ON ac.idactividad=a.idactividad
									JOIN carteras c ON ac.idcartera=c.idcartera
									JOIN proveedores p ON c.idproveedor=p.idproveedor
									AND ac.idestado='1' and a.idestado=1
									GROUP BY ac.idactividadcartera ";
																	
							$cuenta2=$db->Execute($sql);
							$a=0;
							while(!$cuenta2->EOF){
									
								$fila_1 = $mi_xml -> createElement( "actividades_".$cuenta2->fields['idcartera'] );
								
								$ciclo = $mi_xml -> createElement( "idactividad" );
								$ciclo ->appendChild( $mi_xml -> createTextNode($cuenta2->fields['idactividad'] ));
								$fila_1 ->appendChild( $ciclo );
								
								$idestado = $mi_xml -> createElement( "actividad" );
								$idestado ->appendChild( $mi_xml -> createTextNode($cuenta2->fields['actividad'] ));
								$fila_1 ->appendChild( $idestado );
								
								$filas ->appendChild( $fila_1 );	
								
								$cuenta2->MoveNext();
							}
							mysql_free_result($cuenta2->_queryID);
							
							//---------------------------------------------------
							$sql="SELECT c.idcartera,p.idproveedor,r.idcompromisos,r.idresultado,rc.idresultadocartera,r.resultado  FROM resultado_carteras rc
									JOIN resultados r ON rc.idresultado=r.idresultado
									JOIN carteras c ON rc.idcartera=c.idcartera
									JOIN proveedores p ON c.idproveedor=p.idproveedor																																
									JOIN grupo_gestiones gp ON r.idgrupogestion=gp.idgrupogestion 
									WHERE rc.idestado=1 AND r.idestado=1 AND r.flag!=1 
									GROUP BY rc.idresultadocartera ORDER BY c.idcartera ";

							$cuenta2=$db->Execute($sql);
							$a=0;
							while(!$cuenta2->EOF){
						
									$fila_1 = $mi_xml -> createElement( "resultados_".$cuenta2->fields['idcartera'] );
								
									$ciclo = $mi_xml -> createElement( "idcompromisos" );
									$ciclo ->appendChild( $mi_xml -> createTextNode($cuenta2->fields['idcompromisos'] ));
									$fila_1 ->appendChild( $ciclo );
									
									$idestado = $mi_xml -> createElement( "idresultado" );
									$idestado ->appendChild( $mi_xml -> createTextNode($cuenta2->fields['idresultado'] ));
									$fila_1 ->appendChild( $idestado );
									
									$plo = $mi_xml -> createElement( "idresultadocartera" );
									$plo ->appendChild( $mi_xml -> createTextNode($cuenta2->fields['idresultadocartera'] ));
									$fila_1 ->appendChild( $plo );
									
									$pto = $mi_xml -> createElement( "resultado" );
									$pto ->appendChild( $mi_xml -> createTextNode($cuenta2->fields['resultado'] ));
									$fila_1 ->appendChild( $pto );
									
									
									$filas ->appendChild( $fila_1 );	
									
									$cuenta2->MoveNext();
							}
							mysql_free_result($cuenta2->_queryID);
							//----------------------------------------------------------------
							
							$sql="SELECT c.idcartera,co.idcontactabilidad,co.contactabilidad
									FROM contactabilidad_carteras cc
									JOIN contactabilidad co ON cc.idcontactabilidad=co.idcontactabilidad
									JOIN carteras c ON cc.idcartera=c.idcartera
									JOIN proveedores p ON c.idproveedor=p.idproveedor																																
									WHERE cc.idestado=1 AND co.idestado=1  
									GROUP BY cc.idcontactabilidadcartera ORDER BY p.idproveedor,c.idcartera,co.contactabilidad ";
							$tpc=$db->Execute($sql);
							$a=0;
							while(!$tpc->EOF){
																
								$fila_1 = $mi_xml -> createElement( "contactabilidad_".$tpc->fields['idcartera'] );
								
								$ciclo = $mi_xml -> createElement( "idcontactabilidad" );
								$ciclo ->appendChild( $mi_xml -> createTextNode($tpc->fields['idcontactabilidad'] ));
								$fila_1 ->appendChild( $ciclo );
								
								$idestado = $mi_xml -> createElement( "contactabilidad" );
								$idestado ->appendChild( $mi_xml -> createTextNode($tpc->fields['contactabilidad'] ));
								$fila_1 ->appendChild( $idestado );
								
								$filas ->appendChild( $fila_1 );
								
								$tpc->MoveNext();
							}
							
							mysql_free_result($tpc->_queryID);
	
	$mi_xml -> formatOutput = true;
	$strings_xml = $mi_xml -> saveXML();
	$mi_xml -> save('xml_opciones.xml');
?>