		
		<?php 
				$prv=$_SESSION['prove'];
				$p_cart=$_SESSION['cartera'];
				
		?>
		
		<thead id="unos">

		 
		<?php if($prv==7 or $prv==8 or ($prv==12 and $p_cart!=34) or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16) or $prv==17 or $prv==14 or $prv==9 or $prv==20 or $prv==21 or $prv==22 or $prv==27) { ?>	<th>Cta</th><? }?>
		<?php if($prv==7 or $prv==8 or ($prv==12 and $p_cart!=34) or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16) or $prv==17 or $prv==14 or $prv==9 or $prv==20 or $prv==21 or $prv==22 or $prv==27) { ?>	<th>Mon.</th><? }?>
		<?php if($prv==7 or $prv==8 or ($prv==12 and $p_cart!=34) or $prv==2 or ($prv==11  and $p_cart!=22  and $p_cart!=16) or $prv==17 or $prv==14 or $prv==9 or $prv==20 or $prv==21 or $prv==22 or $prv==27) { ?>	<th>Proveedor - Producto</th><? }?>
		
		
		<?php
			if($prv==18){
		?>
				

				<th>Cta</th>
				<th>Mon.</th>	
				<th>Proveedor-Producto</th>	
				<th>Obs.1</th>	
				<th>F.Vcto</th>	
				<th>Mora</th>	
				<th>Imp.Capital</th>	
				<th>Imp.Total</th>	
				<th>Obs.2</th>	
				<th>Grupo</th>	
				<th>Ciclo</th>	
				
		<?php	
			}
		?>
		
		<?php
			if($prv==11 and $p_cart==16){
		?>
				<th>Cta</th>
				<th>Mon.</th>	
				<th>Proveedor-Producto</th>	
				<th>Obs.1</th>	
				<th>F.Vcto</th>	
				<th>Mora</th>	
				<th>Imp.Capital</th>	
				<th>Imp.Total</th>	
				<th>Imp.A Pagar</th>	
				<th>Grupo</th>	
				<th>Ciclo</th>	
				<th>Obs.2</th>	
		<?php	
			}
		?>
		
		
		<?php
			if($prv==16 ){
		?>
				<th>Proveedor-Producto</th>
				<th>Moneda</th>	
				<th>Nro Prestamo</th>
				<th>Vencimiento</th>
				<th>Dias Mora</th>
				
				<th>Desembolso</th>
				
				<th>Capital Total</th>
				<th>Capital Vencido</th>
				<th>Interes</th>
				<th>Cargos</th>
				<th>Deuda Vencida</th>
				<th>Deuda Total</th>
				<th>Grupo</th>
				<th>Ciclo</th>
				
				<th>Ult. Pago Hist.</th>
				
				<th>Dscto</th>
				<th>Plazo</th>
				
			
				
		<?php	
			}
		?>
		
		

		<?php
			if($prv==25){
		?>
				<th>Proveedor-Producto</th>	
				<th>Moneda</th>	
				<th>Cuenta</th>
				<th>Nro de Cuotas</th>	
				<th>Observaci&oacute;n</th>
				<th>Vencimiento</th>
				<th>Dias Mora</th>
				<th>Capital</th>	
				<th>Int.Com.Venc.</th>	
				<th>Int.Mora</th>	
				<th>Gastos</th>	
				<th>Deuda Vencida</th>	
				<th>Deuda Total</th>
				<th>Valor Cuota</th>
				<th>Cartera</th>
				<th>Contenci&oacute;n</th>
				
		<?php	
			}
		?>
		
		<?php
			if($prv==24){
		?>
				<th>Cod Consultora - Pedido</th>
				<th>Proveedor-Producto</th>	
				<th>Moneda</th>	
				<th>Monto_Deuda</th>	
				<th>Saldo_Deuda</th>	
				<th>Saldo_Atualiz.</th>	
				<th>Mora</th>	
				<th>Fech.Vencim.</th>	
				<th>Grupo</th>	
				
				<th>Ciclo</th>

		<?php	
			}
		?>
		
		<?php
			if($prv==11 and $p_cart==22){
		?>
				<th>Tipo Cartera</th>
				<th>N&deg; Operaci&oacute;n</th>	
				<th>Proveedor-Producto</th>	
				<th>Moneda</th>	
				<th>Capital</th>	
				<th>Deuda Total</th>	
				<th>Monto Campa&ntilde;a</th>	
				<th>Fraccionado</th>	
				<th>N&deg; CuotCred-Pagadas-Vencidas</th>	
				<th>Origen Deuda</th>	
				<th>Mora</th>	
				<th>Fech.Vencim.</th>	
				<th>Grupo</th>	
				<th>Ciclo</th>

		<?php	
			}
		?>
		
		<?php
			if($prv==12 and $p_cart==34){
		?>
				<th>Tipo Cartera</th>
				<th>N&deg; Operaci&oacute;n</th>	
				<th>Proveedor-Producto</th>	
				<th>Fec Con</th>
				<th>Grupo</th>	
				<th>Observacion</th>	
				<th>Detalle</th>

		<?php	
			}
		?>
		
		

		
		<?php if($prv==27 or $prv==14 or $prv==9 or $prv==20 or $prv==21 or $prv==22) { ?>	
				<th>Cartera</th>
				<th>Imp.Total</th>
				<th>Saldo</th>
				<th>Mora</th>
				<th>femis</th>
				<th>fecven</th>
				<th>% Deuda</th>
				<th>frecep</th>
				<th>Prov</th>
				<th>C.E</th>
				<th>C.P</th>
				<th>Grupo</th>

		<? }?>
		
		<?php if($prv==7 or $prv==8 or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16)) { ?>	<th>Imp.Capital</th><? }?>
		<?php if(($prv==12 and $p_cart!=34) or $prv==2 or $prv==7 ) { ?><th>Imp.Vencido</th>	<? }?>
		<?php if($prv==7 or $prv==8 or ($prv==12 and $p_cart!=34) or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16) or $prv==17) { ?>	<th>Imp.Total</th><? }?>
		<?php if($prv==8 ) { ?><th>Imp.Fracc.Ini</th><? }?>
		<?php if($prv==8) { ?><th>Imp.Fracc.Pr</th><? }?>
		<?php if($prv==8) { ?><th>Imp.Fracc.Mnt</th><? }?>
		<?php if($prv==8) { ?><th>Fracc.Cuo</th><? }?>
		<?php if($prv==7 or ($prv==12 and $p_cart!=34) or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16) or $prv==17)  { ?>	<th>Mora</th><? }?>
		<?php if($prv==0 or $prv==17) { ?><th>Imp.Min</th><? }?>
		<?php if($prv==7 or $prv==8 or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16)) { ?>	<th>Imp.Descuento</th><? }?>
		<?php if($prv==7 or $prv==8 or ($prv==12 and $p_cart!=34) or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16) or $prv==17) { ?>	<th>F. Vcto</th><? }?>
		<?php if($prv==0) { ?><th>F. Con</th><? }?>
		<?php if($prv==7 or ($prv==12 and $p_cart!=34) or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16) or $prv==17) { ?>	<th>Grupo</th><? }?>
		<?php if($prv==7 or ($prv==12 and $p_cart!=34) or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16) or $prv==17) { ?>	<th>Ciclo</th><? }?>
		<?php if($prv==7 or $prv==8 or ($prv==12 and $p_cart!=34) or $prv==2 or $prv==17) { ?>	<th>Obs.</th><? }?>
		<?php if($prv==7 or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16)) { ?>	<th>Obs2.</th><? }?>
		

		<?php  if($prv==15){?>
				<th>Proveedor - Producto</th>
				<th>Codigo</th>
				<th>Campaña</th>
				<th>Tienda</th>
				<th>Grupo</th>
				<th>Ciclo</th>
				<th>Fech.Nacimiento</th>
		<?php }?>
		</thead>
		

		
		<tbody>
		<?php
				//$db2->debug=true;
				$cartera = $_SESSION['cartera'];
												$periodo = $_SESSION['periodo'];
												$sql="SELECT ct.cartera,tpc.tipocartera,c.*,cp.*,m.simbolo,m.monedas,pr.proveedor,pro.producto,cp.idperiodo,cp.idestado ,
	(SELECT fecges FROM gestiones WHERE idresultado=19 AND idcuenta=c.idcuenta AND YEAR(fecges)='2012'  LIMIT 0,1) c_p,
	(SELECT fecges FROM gestiones WHERE idresultado=20 AND idcuenta=c.idcuenta AND YEAR(fecges)='2012'  LIMIT 0,1) c_e
												FROM cuentas c 
																		LEFT JOIN tipo_carteras tpc on c.idtipocartera=tpc.idtipocartera
																		JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta
																		JOIN monedas m ON c.idmoneda=m.idmoneda
																		JOIN carteras ct ON c.idcartera=ct.idcartera
																		JOIN proveedores pr ON ct.idproveedor=pr.idproveedor
																		JOIN productos pro ON c.idproducto=pro.idproducto	
																		WHERE c.idcliente='$id'	and cp.idperiodo='$periodo'";
																		
																		if($_SESSION['nivel']==2 and $prv==14){
																			$sql.=" and pr.idproveedor=$prv and cp.idestado=1";
																		}
																		
																		if($_SESSION['nivel']!=1 and $_SESSION['nivel']!=5 and $prv!=14 ){
																			$sql.=" and ct.idcartera='$cartera' and cp.idestado=1";
																		}
												//if($prv==14 or $prv==9 or $prv==20 or $prv==21 or $prv==22 ) { echo $sql;}		
												//if($prv==2) { echo $sql;}				
												$cuenta=$db2->Execute($sql);		
				while(!$cuenta->EOF){
					$ct=$cuenta->fields['idcuenta'];
					if($cuenta->fields['idestado']=='0'){$bgr="background-color:#87CEFA;";}else{$bgr="";}
					
					if($prv==14 or $prv==9 or $prv==20 or $prv==21 or $prv==22) {
						if($cuenta->fields['diasmora']==0 and $cuenta->fields['idestado']!=0){$bgr="background-color:#98FB98;";}
						else if($cuenta->fields['diasmora']>0 and $cuenta->fields['idestado']!=0){$bgr="background-color:#FFC0CB;";}else if( $cuenta->fields['idestado']!=0){$bgr="";}
					}
					
					echo "<tr id='".$cuenta->fields['idcuenta']."'  onclick=\"show_cta_gest('$ct')\"; style='cursor:pointer;$bgr'>";
						$cta=explode('-',$cuenta->fields['idcuenta']);
						
						
						if(count($cta)==3){
							$cta[0]=$cta[0]."-".$cta[1];
						}
						
						if(count($cta)==4){
							$cta[0]=$cta[0]."-".$cta[1]."-".$cta[2];
						}
						

					$impven=number_format($cuenta->fields['impven'],2,'.',',');
				
					$impt=number_format($cuenta->fields['imptot'],2,'.',',');
											
					$impds=number_format($cuenta->fields['impdestot'],2,'.',',');
					
					$impcap=number_format($cuenta->fields['impcap'],2,'.',',');
					
					if($prv==15){
						
						echo "<td>".$cuenta->fields['proveedor']."-".$cuenta->fields['producto']."</td>";
						echo "<td>".$cta[0]."</td>";
						echo "<td>".$cuenta->fields['tipocartera']."</td>";	
						echo "<td>".$cuenta->fields['observacion']."</td>";
						echo "<td>".$cuenta->fields['grupo']."</td>";
						echo "<td>".$cuenta->fields['ciclo']."</td>";
						echo "<td>".$cuenta->fields['feccon']."</td>";
						
					}
					

					
					if($prv==7 or $prv==8 or ($prv==12 and $p_cart!=34) or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16) or $prv==17 or $prv==14 or $prv==9 or $prv==20 or $prv==21 or $prv==22 or $prv==27) { echo "<td>".$cta[0]."</td>";}
					if($prv==7 or $prv==8 or ($prv==12 and $p_cart!=34) or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16) or $prv==17 or $prv==14 or $prv==9 or $prv==20 or $prv==21 or $prv==22 or $prv==27) { echo "<td>".$cuenta->fields['simbolo']."</td>";}
					if($prv==7 or $prv==8 or ($prv==12 and $p_cart!=34) or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16) or $prv==17 or $prv==14 or $prv==9 or $prv==20 or $prv==21 or $prv==22 or $prv==27) { echo "<td>".$cuenta->fields['proveedor']."-".$cuenta->fields['producto']."</td>";}
					if($prv==7 or $prv==8 or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16)) {
						/*$sp= explode(".",$cuenta->fields['impcap']);
																		$tot_impt=strlen($sp[0]);
																		if($tot_impt>=4){
																			$impcap=substr_replace($sp[0],',',-3,0).".".$sp[1];
																		}else{
																			$impcap=$cuenta->fields['impcap'];
																		}	*/
	
						echo "<td class='numeros'>".$impcap."</td>";
					}
					if(($prv==12 and $p_cart!=34) or $prv==2 or $prv==7 ) { echo "<td class='numeros'>".$impven."</td>";}
					if($prv==7 or $prv==8 or $prv==2 or ($prv==12 and $p_cart!=34) or ($prv==11 and $p_cart!=22  and $p_cart!=16) or $prv==17) { echo "<td class='numeros'>".$impt."</td>";}
					if($prv==8) { echo "<td class='numeros'>".$cuenta->fields['impfraini']."</td>";}
					if($prv==8) { echo "<td class='numeros'>".$cuenta->fields['impfracpr']."</td>";}
					if($prv==8) { echo "<td class='numeros'>".$cuenta->fields['impframnt']."</td>";}
					if($prv==8) { echo "<td class='numeros'>".$cuenta->fields['fracuo']."</td>";}
					if($prv==7 or ($prv==12 and $p_cart!=34) or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16) or $prv==17) { echo "<td class='numeros'>".$cuenta->fields['diasmora']."</td>";}					
					if($prv==0 or $prv==17) { echo "<td class='numeros'>".$cuenta->fields['impmin']."</td>";}
					if($prv==7 or $prv==8  or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16)) { echo "<td class='numeros'>".$impds."</td>";}		
					if($prv==7 or $prv==8 or ($prv==12 and $p_cart!=34) or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16) or $prv==17) { echo "<td align='center'>".$cuenta->fields['fecven']."</td>";}
					if($prv==0) { echo "<td class='numeros'>".$cuenta->fields['feccon']."</td>";}
					if($prv==7 or ($prv==12 and $p_cart!=34) or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16) or $prv==17) { echo "<td>".$cuenta->fields['grupo']."</td>";}
					if($prv==7 or ($prv==12 and $p_cart!=34) or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16) or $prv==17) { echo "<td>".$cuenta->fields['ciclo']."</td>";}
					if($prv==7 or $prv==8 or ($prv==12 and $p_cart!=34) or $prv==2  or $prv==17) { echo "<td>".$cuenta->fields['observacion']."</td>";}
					if($prv==7 or $prv==2 or ($prv==11 and $p_cart!=22  and $p_cart!=16)) { echo "<td>".$cuenta->fields['observacion2']."</td>";}
					
					
					/*
					1	Areq
					2	Cajam
					3	Chicl
					4	Cusc
					5	Hyo
					6	Ica
				    7   lima
					8	Piur
					9	Tacn
					10	Truj
					11	Tumb
					12	Otros*/

					if($prv==14 or $prv==9 or $prv==20 or $prv==21 or $prv==22 or $prv==27) {
						echo "<td>".$cuenta->fields['cartera']."</td>";
						echo "<td class='numeros'>".$impt."</td>";
						echo "<td class='numeros'>".$impcap."</td>";
						echo "<td class='numeros'>".$cuenta->fields['diasmora']."</td>";
						//echo "<td>".$cuenta->fields['fecentfac']."</td>";
						echo "<td>".$cuenta->fields['feccon']."</td>";
						echo "<td>".$cuenta->fields['fecven']."</td>";
						echo "<td class='numeros'>".(int)$cuenta->fields['impmin']."%</td>";
						
						if($cuenta->fields['fecentfac']=="" or $cuenta->fields['fecentfac']=="0000-00-00" ){
							$fecrep="";
						}else{
							$fecrep=$cuenta->fields['fecentfac'];
						}
						
						echo "<td>".$fecrep."</td>";
						
						switch($cuenta->fields['fracuo']){
							case 1:
								$pro_r="Areq";
								break;
							case 2:
								$pro_r="Cajam";
								break;
							case 3:
								$pro_r="Chicl";
								break;
							case 4:
								$pro_r="Cusc";
								break;
							case 5:
								$pro_r="Hyo";
								break;
							case 6:
								$pro_r="Ica";
								break;
							case 7:
								$pro_r="Lima";
								break;
							case 8:
								$pro_r="Piur";
								break;
							case 9:
								$pro_r="Tacn";
								break;
							case 10:
								$pro_r="Truj";
								break;
							case 11:
								$pro_r="Tumb";
								break;
							case 12:
								$pro_r="Otros";
								break;
						}
						
						echo "<td>".$pro_r."</td>";
						echo "<td>".$cuenta->fields['c_e']."</td>";
						//echo "<td>".$cuenta->fields['feccobpre']."</td>";
						echo "<td>".$cuenta->fields['c_p']."</td>";
						echo "<td>".$cuenta->fields['grupo']."</td>";
					}
					
					 if($prv==11 and $p_cart==22) { 
							echo   "<td>".$cuenta->fields['tipocartera']."</td>
									<td>".$cta[0]."</td>
									<td>".$cuenta->fields['proveedor']."-".$cuenta->fields['producto']."</td>	
									<td>".$cuenta->fields['simbolo']."</td>
									<td>".$impcap."</td>
									<td>".$impt."</td>	
									<td>".$cuenta->fields['impdestot']."</td>
									<td>".$cuenta->fields['observacion']."</td>	
									<td>".$cuenta->fields['observacion2']."</td>	
									<td>".$cuenta->fields['obs3']."</td>
									<td>".$cuenta->fields['diasmora']."</td>
									<td>".$cuenta->fields['fecven']."</td>	
									<td>".$cuenta->fields['grupo']."</td>	
									<td>".$cuenta->fields['ciclo']."</td>";

					 }
					 
					 if($prv==12 and $p_cart==34) { 
							echo   "<td>".$cuenta->fields['tipocartera']."</td>
									<td>".$cta[0]."</td>
									<td>".$cuenta->fields['proveedor']."-".$cuenta->fields['producto']."</td>	
									<td>".$cuenta->fields['fecven']."</td>
									<td>".$cuenta->fields['grupo']."</td>	
									<td>".$cuenta->fields['observacion']."</td>	
									<td>".$cuenta->fields['observacion2']."</td>	
									";
					 }
					
					
					
					
					if($prv==16){
					
	

							echo   "
									<td>".$cuenta->fields['proveedor']."-".$cuenta->fields['producto']."</td>
									<td>".$cuenta->fields['simbolo']."</td>
									<td>".$cta[0]."</td>		
									<td>".$cuenta->fields['fecven']."</td>
									<td>".$cuenta->fields['diasmora']."</td>
									
									<td>".$cuenta->fields['impcapori']."</td>
									
									<td>".$impcap."</td>
									<td>".$cuenta->fields['impmor']."</td>
									<td>".$cuenta->fields['impint']."</td>
									<td>".$cuenta->fields['impotr']."</td>
									<td>".$cuenta->fields['impven']."</td>
									<td>".$cuenta->fields['imptot']."</td>
									
									<td>".$cuenta->fields['grupo']."</td>
									<td>".$cuenta->fields['ciclo']."</td>
									
									
									<td>".$cuenta->fields['observacion']."</td>	
									
									<td>".$cuenta->fields['observacion2']."</td>	
									<td>".$cuenta->fields['obs3']."</td>	
							
									";
					}
					
					if($prv==24){
							echo   "<td>".$cta[0]."</td>
									<td>".$cuenta->fields['proveedor']."-".$cuenta->fields['producto']."</td>	
									<td>".$cuenta->fields['simbolo']."</td>
									<td>".$cuenta->fields['impcapori']."</td>
									<td>".$impcap."</td>
									<td>".$impt."</td>	
									<td>".$cuenta->fields['diasmora']."</td>
									<td>".$cuenta->fields['fecven']."</td>	
									<td>".$cuenta->fields['grupo']."</td>
									<td>".$cuenta->fields['ciclo']."</td>";
					}
		
					
					
					if($prv==25){
							echo   "<td>".$cuenta->fields['proveedor']."-".$cuenta->fields['producto']."</td>
									<td>".$cuenta->fields['simbolo']."</td>
									<td>".$cta[0]."</td>
									<td>".$cuenta->fields['nrocuotas']."</td>
									<td>".$cuenta->fields['observacion']."</td>
									<td>".$cuenta->fields['fecven']."</td>	
									<td>".$cuenta->fields['diasmora']."</td>
									<td>".$impcap."</td>
									<td>".$cuenta->fields['impint']."</td>
									<td>".$cuenta->fields['impmor']."</td>
									<td>".$cuenta->fields['impotr']."</td>
									<td>".$impven."</td>
									<td>".$impt."</td>	
									<td>".$cuenta->fields['impmin']."</td>
									<td>".$cuenta->fields['grupo']."</td>
									<td>".$cuenta->fields['ciclo']."</td>
									";
					}
					
					if($prv==11 and $p_cart==16) { 
							echo   "<td>".$cta[0]."</td>
									<td>".$cuenta->fields['simbolo']."</td>
									<td>".$cuenta->fields['proveedor']."-".$cuenta->fields['producto']."</td>	
									<td>".$cuenta->fields['observacion']."</td>
									<td>".$cuenta->fields['fecven']."</td>
									<td>".$cuenta->fields['diasmora']."</td>	
									<td>".$impcap."</td>
									<td>".$impt."</td>	
									<td>".$cuenta->fields['impdestot']."</td>	
									<td>".$cuenta->fields['grupo']."</td>
									<td>".$cuenta->fields['ciclo']."</td>
									<td>".$cuenta->fields['observacion2']."</td>	
									";

					 }		
					
					if($prv==18) { 
							echo   "<td>".$cta[0]."</td>
									<td>".$cuenta->fields['simbolo']."</td>
									<td>".$cuenta->fields['proveedor']."-".$cuenta->fields['producto']."</td>	
									<td>".$cuenta->fields['observacion']."</td>
									<td>".$cuenta->fields['fecven']."</td>
									<td>".$cuenta->fields['diasmora']."</td>	
									<td>".$impcap."</td>
									<td>".$impt."</td>	
									<td>".utf8_encode($cuenta->fields['observacion2'])."</td>
									<td>".$cuenta->fields['grupo']."</td>
									<td>".$cuenta->fields['ciclo']."</td>
										
									";

					 }					
					
					echo "</tr>" ;
					
					
					$cuenta->MoveNext();
				}
				mysql_free_result($cuenta->_queryID);

		?>
		</tbody>