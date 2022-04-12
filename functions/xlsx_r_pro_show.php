<?php
ini_set('memory_limit', '-1');
set_time_limit(1800);
/** Error reporting */
error_reporting(E_ALL);

date_default_timezone_set('Europe/London');

include '../scripts/conexion.php';
				$cart=$_GET['cart'];
				$fi=$_GET['fi'];
				$fn=$_GET['fn'];

				if($cart==24){
					$tot_rs="SUM(rs.idresultado =51) ventas,";
					$col="<th>VENTAS</th>";

				}else if($cart==51){
					$tot_rs="SUM(rs.idresultado =248) promesas,";
					$col="<th>PROMESAS</th>";
				}else{
					$tot_rs="SUM(rs.idresultado =2) promesas,";
					$col="<th>PROMESAS</th>";
				}
/*Inicio Instrucciones*/
			echo "<div id='design1'>";
			echo "<table>";
			echo	"<tr>
						<th>ID</th>
						<th>DNI</th>
						<th>NEGOCIADOR</th>
						<th>07:00\n-\n07:59</th>
						<th>08:00\n-\n08:59</th>
						<th>09:00\n-\n09:59</th>
						<th>10:00\n-\n10:59</th>
						<th>11:00\n-\n11:59</th>
						<th>12:00\n-\n12:59</th>
						<th>13:00\n-\n13:59</th>
						<th>14:00\n-\n14:59</th>
						<th>15:00\n-\n15:59</th>
						<th>16:00\n-\n16:59</th>
						<th>17:00\n-\n17:59</th>
						<th>18:00\n-\n18:59</th>
						<th>19:00\n-\n19:59</th>
						<th>20:00\n-\n20:59</th>
						<th>TOTAL</th>
						<th>CONTACTOS\n(CD+CI)</th>
						<th>CD</th>
						<th>TOTAL CLIENTES</th>
						$col
					</tr>
						";
						
				
				
				if($cart==51){
								$sql_actividad=" AND g.idactividad BETWEEN '27' AND '33' AND fecges BETWEEN '$fi' AND '$fn' ";


				}else{
								$sql_actividad=" AND g.idactividad BETWEEN '1' AND '2' AND fecges BETWEEN '$fi' AND '$fn' ";

				}

				$sql="SELECT rs.idusuario,rs.documento,rs.usuario,COUNT(DISTINCT rs.fecreg) total_llamadas,
				SUM(rs.idtipocontactabilidad BETWEEN '1' AND '2') contactos_CD_CI,
				SUM(rs.idtipocontactabilidad =1) CD,
				$tot_rs
				SUM(HOUR(rs.fecreg)='6') h6,
				SUM(HOUR(rs.fecreg)='7') h7,
				SUM(HOUR(rs.fecreg)='8') h8,
				SUM(HOUR(rs.fecreg)='9') h9,
				SUM(HOUR(rs.fecreg)='10') h10,
				SUM(HOUR(rs.fecreg)='11') h11,
				SUM(HOUR(rs.fecreg)='12') h12,
				SUM(HOUR(rs.fecreg)='13') h13,
				SUM(HOUR(rs.fecreg)='14') h14,
				SUM(HOUR(rs.fecreg)='15') h15,
				SUM(HOUR(rs.fecreg)='16') h16,
				SUM(HOUR(rs.fecreg)='17') h17,
				SUM(HOUR(rs.fecreg)='18') h18,
				SUM(HOUR(rs.fecreg)='19') h19,
				SUM(HOUR(rs.fecreg)='20') h20,
				SUM(HOUR(rs.fecreg)='21') h21 ,
				count(distinct(rs.idcliente)) tot_cli
				FROM (SELECT cts.idcliente,u.idusuario,u.documento,u.usuario,COUNT(DISTINCT g.fecreg) total,g.fecreg,g.fecges,g.usureg,ct.idtipocontactabilidad,g.idresultado							
							FROM usuarios u
							JOIN proveedores p ON u.idproveedor=p.idproveedor
							JOIN carteras c ON u.idcartera=c.idcartera
							JOIN gestiones g ON u.idusuario=g.usureg
							join cuentas cts on g.idcuenta=cts.idcuenta 
							JOIN contactabilidad ct ON g.idcontactabilidad=ct.idcontactabilidad	
							WHERE u.idnivel='2' 
							AND c.idcartera='$cart'
							$sql_actividad
							GROUP BY g.usureg,g.fecreg
							) AS rs
							GROUP BY rs.usureg
				";
			
				
				$query=$db->Execute($sql);
				while(!$query->EOF){
				
					$id=$query->fields['idusuario'];
					$doc=$query->fields['documento'];
					$user=$query->fields['usuario'];
					$tot_ll=$query->fields['total_llamadas'];
					$cont=$query->fields['contactos_CD_CI'];
					$CD=$query->fields['CD'];
					$cli=$query->fields['tot_cli'];
					
					if($cart==24){
						$prom=$query->fields['ventas'];
					}else{
						$prom=$query->fields['promesas'];					
					}
	
					
					
					$h[7]=$query->fields['h6']+$query->fields['h7'];
					$h[8]=$query->fields['h8'];
					$h[9]=$query->fields['h9'];
					$h[10]=$query->fields['h10'];
					$h[11]=$query->fields['h11'];
					$h[12]=$query->fields['h12'];
					$h[13]=$query->fields['h13'];
					$h[14]=$query->fields['h14'];
					$h[15]=$query->fields['h15'];
					$h[16]=$query->fields['h16'];
					$h[17]=$query->fields['h17'];
					$h[18]=$query->fields['h18'];
					$h[19]=$query->fields['h19'];
					$h[20]=$query->fields['h20']+$query->fields['h21'];
					
					echo "<tr>
								<td>$id</td>
								<td>$doc</td>
								<td>$user</td>";
					for($i=7;$i<=20;$i++){
							echo "<td style='text-align:center;'>$h[$i]</td>";
						}
					
					echo "		<td style='text-align:center;'>$tot_ll</td>
								<td style='text-align:center;'>$cont</td>
								<td style='text-align:center;'>$CD</td>
								<td style='text-align:center;'>$cli</td>
								<td style='text-align:center;'>$prom</td>
					
						</tr>";
					$query->MoveNext();
				}
				
				echo "</div>";
				
				
				
				

?>
