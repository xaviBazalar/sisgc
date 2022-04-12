<?php
/** Error reporting */
ini_set('memory_limit', '-1');
set_time_limit(1800);
error_reporting(E_ALL);
session_start();
date_default_timezone_set('Europe/London');

/** PHPExcel */
require_once '../class/PHPExcel.php';
require_once '../class/PHPExcel/IOFactory.php';
include '../scripts/conexion.php';
$fecha_hoy=date("Y-m-d");

if(isset($_GET['peri']) and  $_GET['peri']!=""){
	$peri=$_GET['peri'];
	
	$fc=$db->Execute("SELECT year(fecini) ano, month(fecini) mes from periodos where idperiodo=$peri");	
	$ano=$fc->fields['ano'];
	$mes=$fc->fields['mes'];
	if($mes<10){$mes=(string) "0".$mes;}
}else{
	$ano=date("Y");
	$mes=date("m");
	
	//$fecha_hoy="2012-10-09";
	//$ano='2012';
	//$mes='10';
	$periodo = $db->Execute("SELECT idperiodo,periodo  FROM periodos WHERE fecini LIKE '$ano-$mes%'");
	$peri=$periodo->fields['idperiodo'];

}

function getMonthDays($Month, $Year)
{
  
   if( is_callable("cal_days_in_month"))
   {
      return cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
   }
   else
   {
      // Obtenemos el ultima dia del mes
      return date("d",mktime(0,0,0,$Month+1,0,$Year));
   }
}
//Obtenemos la cantidad de días 
//echo getMonthDays(4, 2012);

for($o=1;$o<=getMonthDays($mes, $ano);$o++){
	if(date("l", mktime(0, 0, 0, $mes, $o,$ano)) == "Sunday" || date("l", mktime(0, 0, 0, $mes, $o, $ano)) == "Saturday")
	{
		++$domingo;
	}
}
$promedio=getMonthDays($mes, $ano)-$domingo;
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();



//$desde=$_GET['cod'];
// Set properties
		$objPHPExcel->getProperties()->setCreator("Kobsa - Gestion")
									 ->setLastModifiedBy("Kobsa - Gestion")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");

	 // Add some data
		$objPHPExcel->getDefaultStyle()->getFont()
			->setName('Calibri')
			->setSize(9);
		

		//$cartera=$_GET['cart'];

		
		$datos_res= array();	
		$usuarios = array();
		




	


		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('b2', "GESTION DE CONTACTO/LLAMADA")
							->setCellValue('b3', "DIA - MES")
							->setCellValue('b4', "TOTAL LLAMADAS")
							->setCellValue('b5', "CLIENTES GESTIONADOS")
							->setCellValue('b6', "CLIENTES CONTACTOS")
							->setCellValue('b7', "CONTACTO DIRECTOS (CD)")
							->setCellValue('b8', "CONTACTOS INDIRECTOS (CI)")
							->setCellValue('b9', "NO CONTACTO (NC)")
							->setCellValue('b10', "CONTACTABILIDAD ( C%)")
							->setCellValue('b11', "CONTACTO DIRECTO (CD%)")
							->setCellValue('b12', "CONTACTO INDIRECTO (CI%)")
							->setCellValue('b13', "NO CONTACTO (NC%)")
							->setCellValue('b14', "INTENSIDAD (i)")
							->setCellValue('b15', "PROMESAS DE PAGO (PDP)")
							//->setCellValue('b16', "MONTO TOTAL PAGO MINIMO DE PDP")
							->setCellValue('b17', "EFECTIVIDAD DE PDP")
							->setCellValue('b18', "TASA DE CIERRE")
							
							->setCellValue('b20', "CONTACTO DIRECTO")
							->setCellValue('b21', "Contacto Directo Positivo")
							->setCellValue('b22', "Contacto Directo Indefinido")
							->setCellValue('b23', "Contacto Directo Negativo")
								->setCellValue('b24', "Promesa de Pago")
								->setCellValue('b25', "Ya pago")
								->setCellValue('b26', "Cliente realiza consultas")
								->setCellValue('b27', "No desea brindar respuesta")
								->setCellValue('b28', "No percibe / baja de Ingresos")
								->setCellValue('b29', "Salud / Catastrofes Naturales")
								->setCellValue('b30', "Imposibilidad por Salud")
								->setCellValue('b31', "Desface Fecha Sueldo - Fecha EECC")
								->setCellValue('b32', "Posible Estafa")
								->setCellValue('b33', "No llega EECC")
								->setCellValue('b34', "Sin Sucursal de Pago Cerca")
								->setCellValue('b35', "Simple olvido")
								->setCellValue('b36', "Problema en la venta")
								->setCellValue('b37', "Renuente a pagar")
								->setCellValue('b38', "Reclamo Cliente")

							->setCellValue('b40', "CONTACTO INDIRECTO")
								->setCellValue('b41', "No brinda Informacion")
								->setCellValue('b42', "Mensaje")
								->setCellValue('b43', "Licencia")
								->setCellValue('b44', "Realiza consulta*")
								->setCellValue('b45', "Fallecio titular**" )
								->setCellValue('b46', "De Viaje***")

							->setCellValue('b48', "NO CONTACTO")
								->setCellValue('b49', "Telefono equivocado/No corresponde*")
								->setCellValue('b50', "No contesta / Ocupado")
								->setCellValue('b51', "No existe (Telefono invalido)")
								->setCellValue('b52', "Grabadora**")
								->setCellValue('b53', "Cortan***")
								
							->setCellValue('b55', "INBOUND")
								->setCellValue('b56', "Promesa de Pago")
								->setCellValue('b57', "Ya pago")
								->setCellValue('b58', "Cliente realiza consultas")
								->setCellValue('b59', "Reclamo Cliente")
								->setCellValue('b60', "Consulta Realizada por Tercero")	
							
							->setCellValue('b62', "IVR / BLASTER")
								->setCellValue('b63', "IVR Envío Efectivo")
								->setCellValue('b64', "IVR No enviado")
							
							->setCellValue('b66', "SMS")
								->setCellValue('b67', "SMS Enviado")
								->setCellValue('b68', "SMS no Recepcionado")
								->setCellValue('b69', "SMS no Enviado")

							->setCellValue('b71', "VISITA DEL CLIENTE A OFICINA (O tercero relacionado)")
								->setCellValue('b72', "Promesa de Pago")
								->setCellValue('b73', "Ya pago")
								->setCellValue('b74', "Cliente realiza consultas")
								->setCellValue('b75', "Reclamo Cliente")
								->setCellValue('b76', "Visita Tercero / Pide informacion")
							
							->setCellValue('b78', "GESTION TERRENO")
								->setCellValue('b79', "Promesa de Pago")
								->setCellValue('b80', "Ya Pago")
								->setCellValue('b81', "Renuente a pagar")
								->setCellValue('b82', "Reclamo Cliente")
								->setCellValue('b83', "Contacto tercero / Deja notificacion")
								->setCellValue('b84', "Falleció titular**")
								->setCellValue('b85', "Se mudo**")
								->setCellValue('b86', "Inubicable / Notificacion bajo puerta")
								->setCellValue('b87', "Titular desconocido***")
								->setCellValue('b88', "Direccion errada / incompleta")
								->setCellValue('b89', "Zona fuera de cobertura")
								->setCellValue('b90', "Zona peligrosa")

							->setCellValue('b92', "CARTA DE COBRANZA")
								->setCellValue('b93', "Carta  Enviada")
								->setCellValue('b94', "No entregada (varios)")
							
							/*->setCellValue('b96', "CAPACITY & COSTO")
							->setCellValue('b97', "GESTION DE CONTACTO/LLAMADA")
								->setCellValue('b98', "POSICIONES")
								->setCellValue('b99', "PDP POR POSICION")
								->setCellValue('b100', "GESTORES DE COBRANZA")
								->setCellValue('b101', "PDP POR GESTOR")
								->setCellValue('b102', "HORAS POR DIA POR POSICION")
								->setCellValue('b103', "HORAS TOTALES / DIA")
								->setCellValue('b104', "PDP POR HORA")
								->setCellValue('b105', "TARIFA HORA POSICION ")
								->setCellValue('b106', "COSTO GESTION TELEFONICA (S/.) ")
								->setCellValue('b107', "COSTO UNIT (S/.) ")
								
								->setCellValue('b108', "IVR / BLASTER")
								->setCellValue('b109', "MINUTOS IVR/BLASTER UTILIZADOS")
								->setCellValue('b110', "TARIFA IVR/BLASTER ")
								->setCellValue('b111', "CLIENTES CONTACTADOS")
								->setCellValue('b112', "COSTO GESTION IVR/BLASTER (S/.) ")
								->setCellValue('b113', "COSTO UNIT (S/.)")
								
								->setCellValue('b114', "SMS")
								->setCellValue('b115', "TOTAL SMS CONECTADOS")
								->setCellValue('b116', "TARIFA SMS")
								->setCellValue('b117', "COSTO GESTION SMS (S/.)")
								->setCellValue('b118', "COSTO UNIT (S/.)")
								->setCellValue('b119', "VISITA DEL CLIENTE A OFICINA (O tercero relacionado)")
								->setCellValue('b120', "GESTION TERRENO")
								->setCellValue('b121', "CANTIDAD GESTORES DE CAMPO")
								->setCellValue('b122', "TARIFA CAMPO")
								->setCellValue('b123', "GESTION TERRENO POSITIVO")
								->setCellValue('b124', "COSTO GESTION CAMPO (S/.) ")
								->setCellValue('b125', "COSTO UNIT (S/.)")
								->setCellValue('b126', "CARTA DE COBRANZA")
								->setCellValue('b127', "CARTAS ENVIADAS")
								->setCellValue('b128', "TARIFA CARTA")
								->setCellValue('b129', "COSTO GESTION SMS (S/.)")
								->setCellValue('b130', "COSTO UNIT (S/.)")
								->setCellValue('b131', "OTROS GASTOS")
								->setCellValue('b132', "OTROS GASTOS AUTORIZADOS")
								->setCellValue('b133', "COSTO TOTAL EMPRESA DE COBRANZA")
								->setCellValue('b134', "MONTO RECUPERADO POR DIA ")
								->setCellValue('b135', "COSTO POR SOL RECUPERADO")*/
								
							;
				
			$n=3;
			++$n;

		//for($i=1;$i<=getMonthDays($mes, $ano);$i++){
		

		for($i=1;$i<=getMonthDays($mes, $ano);$i++){	
			$dia=date("l", mktime(0, 0, 0, $mes, $i,$ano));
						switch($dia){
							case "Monday":
								$dia_n="Lun";
								break;
							case "Tuesday":
								$dia_n="Mar";
								break;
							case "Wednesday":
								$dia_n="Mie";
								break;
							case "Thursday":
								$dia_n="Jue";
								break;
							case "Friday":
								$dia_n="Vie";
								break;
							case "Saturday":
								$dia_n="Sab";
								break;
							case "Sunday":
								$dia_n="Dom";
								break;
								
								
						
						}
			
			if($i<10){
				$nro=(string)"0".$i;
			}else{
				$nro=$i;
			}
			
			//Que no exceda del dia actual para la busqueda
			
			
			$cartera=$_GET['cart'];
			if($cartera==""){
				$cartera=51;
			}
			//$db->debug=true;
				
			$fecha="$ano-$mes-$nro";
			if($fecha_hoy!=$fecha){
				$sql="SELECT COUNT(*) tot_llamadas FROM (
							SELECT idgestion FROM cuentas c
							JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$peri and cp.idusuario!=1490
							JOIN gestiones g ON cp.idcuenta=g.idcuenta AND g.fecges='$ano-$mes-$nro'
							WHERE c.idcartera=$cartera AND g.idactividad!=4
							GROUP BY g.idcuenta,g.fecreg
							) AS rs2";
				$res_tt=$db->Execute($sql);
				$tot_llamadas=$res_tt->fields['tot_llamadas'];		
				$sql="SELECT
							
							COUNT(DISTINCT(rs.idcliente)) tot_clientes,
							-- COUNT(*) tot_llamadas,
							SUM(rs.tipo_res='P') positivo,
							SUM(rs.tipo_res='I') indefinido,
							SUM(rs.tipo_res='N') negativo,
							SUM(rs.tipo_contacto='CD' OR rs.tipo_contacto='CI') clientes_contactados,
							SUM(rs.tipo_contacto='CD') CD,
							SUM(rs.tipo_contacto='CI') CI,
							SUM(rs.tipo_contacto='NC') NC,
							ROUND(((((SUM(rs.tipo_contacto='CD'))+(SUM(rs.tipo_contacto='CI')))/ (COUNT(*)) )*100),2) contactabilidad,
							ROUND(((((SUM(rs.tipo_contacto='CD'))))/ (COUNT(*)) *100),2) CD_P,
							ROUND(((((SUM(rs.tipo_contacto='CI'))))/ (COUNT(*)) *100),2) CI_P,
							ROUND(((((SUM(rs.tipo_contacto='NC'))))/ (COUNT(*)) *100),2) NC_P,
							-- ((COUNT(*))/(SUM(rs.tipo_contacto='CD' OR rs.tipo_contacto='CI'))) intensidad,
							SUM(rs.PDP) PDP,
							SUM(rs.impcomp) IMP_PDP,
							ROUND(((SUM(rs.PDP)) / (COUNT(DISTINCT(rs.idcliente)))*100),2) EFECTIVIDAD_PDP,
							ROUND(((SUM(rs.PDP)) / (SUM(rs.tipo_contacto='CD'))*100),2) TASA_CIERRE,
							SUM(rs.248) `248`,
							SUM(rs.351) `351`,
							SUM(rs.339) `339`,
							SUM(rs.333) `333`,
							SUM(rs.332) `332`,
							SUM(rs.342) `342`,
							SUM(rs.325) `325`,
							SUM(rs.194) `194`,
							SUM(rs.336) `336`,
							SUM(rs.331) `331`,
							SUM(rs.346) `346`,
							SUM(rs.335) `335`,
							SUM(rs.337) `337`,
							SUM(rs.341) `341`,
							SUM(rs.340) `340`,
							SUM(rs.329) `329`,
							SUM(rs.327) `327`,
							SUM(rs.326) `326`,
							SUM(rs.338) `338`,
							SUM(rs.320) `320`,
							SUM(rs.319) `319`,
							SUM(rs.348) `348`,
							SUM(rs.330) `330`,
							SUM(rs.349) `349`,
							SUM(rs.321) `321`,
							SUM(rs.316) `316`

						FROM
						(
							SELECT r.peso_r,idactividad,idcliente,g.idcuenta,
								g.idresultado,
								impcomp,
								CASE
									WHEN gg.idgrupogestion=11 THEN 'P'
									WHEN gg.idgrupogestion=12 THEN 'I'
									WHEN gg.idgrupogestion=13 THEN 'N'
									ELSE '-'
								END tipo_res,
								CASE
									WHEN gg.idgrupogestion IN (11,12,13) THEN 'CD'
									WHEN gg.idgrupogestion=14 THEN 'CI'
									WHEN gg.idgrupogestion=15 THEN 'NC'
									ELSE '-'
								END tipo_contacto,
								IF(g.idresultado=248,1,0) PDP,
								fecges,
								horges,
								gg.idgrupogestion,
											
								IF(g.idresultado=248,1,0) `248`,
								IF(g.idresultado=351,1,0) `351`,
								IF(g.idresultado=339,1,0) `339`,
								IF(g.idresultado=333,1,0) `333`,
								IF(g.idresultado=332,1,0) `332`,
								IF(g.idresultado=342,1,0) `342`,
								IF(g.idresultado=325,1,0) `325`,
								IF(g.idresultado=194,1,0) `194`,
								IF(g.idresultado=336,1,0) `336`,
								IF(g.idresultado=331,1,0) `331`,
								IF(g.idresultado=346,1,0) `346`,
								IF(g.idresultado=335,1,0) `335`,
								IF(g.idresultado=337,1,0) `337`,
								IF(g.idresultado=341,1,0) `341`,
								IF(g.idresultado=340,1,0) `340`,
								IF(g.idresultado=329,1,0) `329`,
								IF(g.idresultado=327,1,0) `327`,
								IF(g.idresultado=326,1,0) `326`,
								IF(g.idresultado=338,1,0) `338`,
								IF(g.idresultado=320,1,0) `320`,
								IF(g.idresultado=319,1,0) `319`,
								IF(g.idresultado=348,1,0) `348`,
								IF(g.idresultado=330,1,0) `330`,
								IF(g.idresultado=349,1,0) `349`,
								IF(g.idresultado=321,1,0) `321`,
								IF(g.idresultado=316,1,0) `316`

				

							FROM cuentas c
							JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idperiodo=$peri and cp.idusuario!=1490
							JOIN gestiones g ON cp.idcuenta=g.idcuenta
							JOIN resultados r ON g.idresultado=r.idresultado
							JOIN grupo_gestiones gg ON r.idgrupogestion=gg.idgrupogestion
							WHERE g.fecges='$ano-$mes-$nro'
							AND c.idcartera=$cartera
							AND g.idactividad!=4
							AND r.peso_r=(
									SELECT MIN(r2.peso_r) FROM gestiones g2 
									JOIN cuentas c2 ON g2.idcuenta=c2.idcuenta AND c2.idcartera=$cartera
									JOIN cuenta_periodos cp2 ON c2.idcuenta=cp2.idcuenta AND cp2.idperiodo=$peri
									JOIN resultados r2 ON g2.idresultado=r2.idresultado
									WHERE c2.idcliente=c.idcliente
									AND g2.fecges='$ano-$mes-$nro'  AND g2.idactividad!=4
									  )
							GROUP BY c.idcliente
							ORDER BY gg.idgrupogestion
						) AS rs
							";
			}else{
				$sql="SELECT * FROM ciber_reportes WHERE fecha='$ano-$mes-$nro' and id_tipo_ciber=1";
				
			}
			
				if($n==4){$l="c";}
				if($n==5){$l="d";}
				if($n==6){$l="e";}
				if($n==7){$l="f";}
				if($n==8){$l="g";}
				if($n==9){$l="h";}
				if($n==10){$l="i";}
				if($n==11){$l="j";}
				if($n==12){$l="k";}
				if($n==13){$l="l";}
				if($n==14){$l="m";}
				if($n==15){$l="n";}
				if($n==16){$l="o";}
				if($n==17){$l="p";}
				if($n==18){$l="q";}
				if($n==19){$l="r";}
				if($n==20){$l="s";}
				if($n==21){$l="t";}
				if($n==22){$l="u";}
				if($n==23){$l="v";}
				if($n==24){$l="w";}
				if($n==25){$l="x";}
				if($n==26){$l="y";}
				if($n==27){$l="z";}
				if($n==28){$l="aa";}
				if($n==29){$l="ab";}
				if($n==30){$l="ac";}
				if($n==31){$l="ad";}
				if($n==32){$l="ae";}
				if($n==33){$l="af";}
				if($n==34){$l="ag";}

			$a=date("Y");
			$m=date("m");
			$d=date("d");


			if($a<$ano){
				break;
			}else if($a==$ano and $mes>$m){
				break;
			}else if($a==$ano and $mes==$m and $i>$d){
				$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue($l."2", $dia_n)
								->setCellValue($l."3", "$nro-$mes")
								->setCellValue($l."4", "0")
								->setCellValue($l."5", "0")
								->setCellValue($l."6", "0")
								->setCellValue($l."7", "0")
								->setCellValue($l."8", "0")
								->setCellValue($l."9", "0")
								->setCellValue($l."10", "0%")
								->setCellValue($l."11", "0%")
								->setCellValue($l."12", "0%")
								->setCellValue($l."13", "0%")
								->setCellValue($l."14", "0")
								->setCellValue($l."15", "0")
								// ->setCellValue($l."16", $q->fields['IMP_PDP'])
								->setCellValue($l."17", "0%")
								->setCellValue($l."18", "0%")
								
								->setCellValue($l."20", "0")
									->setCellValue($l."21", "0")
									->setCellValue($l."22", "0")
									->setCellValue($l."23", "0")
										->setCellValue($l."24", "0")
										->setCellValue($l."25", "0")
										->setCellValue($l."26", "0")
										->setCellValue($l."27", "0")
										->setCellValue($l."28", "0")
										->setCellValue($l."29", "0")
										->setCellValue($l."30", "0")
										->setCellValue($l."31", "0")
										->setCellValue($l."32", "0")
										->setCellValue($l."33", "0")
										->setCellValue($l."34", "0")
										->setCellValue($l."35", "0")
										->setCellValue($l."36", "0")
										->setCellValue($l."37", "0")
										->setCellValue($l."38", "0")
								
								
								->setCellValue($l."40", "0")
									->setCellValue($l."41", "0")
									->setCellValue($l."42", "0")
									->setCellValue($l."43", "0")
									->setCellValue($l."44", "0")
									->setCellValue($l."45", "0")
									->setCellValue($l."46", "0")
									

								->setCellValue($l."48", "0")
									->setCellValue($l."49", "0")
									->setCellValue($l."50", "0")
									->setCellValue($l."51", "0")
									->setCellValue($l."52", "0")
									->setCellValue($l."53", "0")
								;
								++$n;
				continue;
			}
			
			
			$q=$db->Execute($sql);
			
				
					while(!$q->EOF){
						//if($fecha_hoy!=$fecha){ $tot_llamadas=$q->fields['tot_llamadas'];}
					
						$intensidad=($tot_llamadas/($q->fields['CD']+$q->fields['CI']));
						$sql_ss=$db->Execute("SELECT fecha,tot_llamadas FROM ciber_reportes WHERE fecha='$ano-$mes-$nro' and id_tipo_ciber=1");
						//$db->debug=true;
						if($dia_n=="Dom"){
							
							if($sql_ss->fields['fecha']==""){
								$tot_llamadas=0;
								$ins=$db->Execute("INSERT INTO ciber_reportes(
												id_tipo_ciber,
												fecha,
												tot_llamadas,
												tot_clientes,
												positivo,
												indefinido,
												negativo,
												clientes_contactados,
												CD,
												CI,
												NC,
												contactabilidad,
												CD_P,
												CI_P,
												NC_P,
												PDP,
												IMP_PDP,
												EFECTIVIDAD_PDP,
												TASA_CIERRE,
												`248`,
												`351`,
												`339`,
												`333`,
												`332`,
												`342`,
												`325`,
												`194`,
												`336`,
												`331`,
												`346`,
												`335`,
												`337`,
												`341`,
												`340`,
												`329`,
												`327`,
												`326`,
												`338`,
												`320`,
												`319`,
												`348`,
												`330`,
												`349`,
												`321`,
												`316`)
												VALUES (
													1,
													'$ano-$mes-$nro',
													$tot_llamadas,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0,
													0
													)");
							}else{
									$tot_llamadas=0;
									$ins=$db->Execute("UPDATE ciber_reportes SET 
												
												tot_llamadas=".$sql_ss->fields['tot_llamadas'].",
												tot_clientes=0,
												positivo=0,
												indefinido=0,
												negativo=0,
												clientes_contactados=0,
												CD=0,
												CI=0,
												NC=0,
												contactabilidad=0,
												CD_P=0,
												CI_P=0,
												NC_P=0,
												PDP=0,
												IMP_PDP=0,
												EFECTIVIDAD_PDP=0,
												TASA_CIERRE=0,
												`248`=0,
												`351`=0,
												`339`=0,
												`333`=0,
												`332`=0,
												`342`=0,
												`325`=0,
												`194`=0,
												`336`=0,
												`331`=0,
												`346`=0,
												`335`=0,
												`337`=0,
												`341`=0,
												`340`=0,
												`329`=0,
												`327`=0,
												`326`=0,
												`338`=0,
												`320`=0,
												`319`=0,
												`348`=0,
												`330`=0,
												`349`=0,
												`321`=0,
												`316`=0
												where 
													fecha='$ano-$mes-$nro'
												and id_tipo_ciber='1'
													");
								
							}
						}else{
													
							if($sql_ss->fields['fecha']==""){
								$ins=$db->Execute("INSERT INTO ciber_reportes( 
												id_tipo_ciber,
												fecha,
												tot_llamadas,
												tot_clientes,
												positivo,
												indefinido,
												negativo,
												clientes_contactados,
												CD,
												CI,
												NC,
												contactabilidad,
												CD_P,
												CI_P,
												NC_P,
												PDP,
												IMP_PDP,
												EFECTIVIDAD_PDP,
												TASA_CIERRE,
												`248`,
												`351`,
												`339`,
												`333`,
												`332`,
												`342`,
												`325`,
												`194`,
												`336`,
												`331`,
												`346`,
												`335`,
												`337`,
												`341`,
												`340`,
												`329`,
												`327`,
												`326`,
												`338`,
												`320`,
												`319`,
												`348`,
												`330`,
												`349`,
												`321`,
												`316`)
												VALUES (
													1,
													'$ano-$mes-$nro',
													$tot_llamadas,
													".$q->fields['tot_clientes'].",
													".$q->fields['positivo'].",
													".$q->fields['indefinido'].",
													".$q->fields['negativo'].",
													".$q->fields['clientes_contactados'].",
													".$q->fields['CD'].",
													".$q->fields['CI'].",
													".$q->fields['NC'].",
													".$q->fields['contactabilidad'].",
													".$q->fields['CD_P'].",
													".$q->fields['CI_P'].",
													".$q->fields['NC_P'].",
													".$q->fields['PDP'].",
													".$q->fields['IMP_PDP'].",
													".$q->fields['EFECTIVIDAD_PDP'].",
													".$q->fields['TASA_CIERRE'].",
													".$q->fields['248'].",
													".$q->fields['351'].",
													".$q->fields['339'].",
													".$q->fields['333'].",
													".$q->fields['332'].",
													".$q->fields['342'].",
													".$q->fields['325'].",
													".$q->fields['194'].",
													".$q->fields['336'].",
													".$q->fields['331'].",
													".$q->fields['346'].",
													".$q->fields['335'].",
													".$q->fields['337'].",
													".$q->fields['341'].",
													".$q->fields['340'].",
													".$q->fields['329'].",
													".$q->fields['327'].",
													".$q->fields['326'].",
													".$q->fields['338'].",
													".$q->fields['320'].",
													".$q->fields['319'].",
													".$q->fields['348'].",
													".$q->fields['330'].",
													".$q->fields['349'].",
													".$q->fields['321'].",
													".$q->fields['316']."
													)");
							}else{
								if($fecha_hoy!=$fecha){ $act_tt="tot_llamadas='".$tot_llamadas."',";}else{$act_tt="";}
								$ins=$db->Execute("UPDATE ciber_reportes SET 

												$act_tt
												positivo=".$q->fields['positivo'].",
												indefinido=".$q->fields['indefinido'].",
												negativo=".$q->fields['negativo'].",
												clientes_contactados=".$q->fields['clientes_contactados'].",
												CD=".$q->fields['CD'].",
												CI=".$q->fields['CI'].",
												NC=".$q->fields['NC'].",
												contactabilidad=".$q->fields['contactabilidad'].",
												CD_P=".$q->fields['CD_P'].",
												CI_P=".$q->fields['CI_P'].",
												NC_P=".$q->fields['NC_P'].",
												PDP=".$q->fields['PDP'].",
												IMP_PDP=".$q->fields['IMP_PDP'].",
												EFECTIVIDAD_PDP=".$q->fields['EFECTIVIDAD_PDP'].",
												TASA_CIERRE=".$q->fields['TASA_CIERRE'].",
												`248`=".$q->fields['248'].",
												`351`=".$q->fields['351'].",
												`339`=".$q->fields['339'].",
												`333`=".$q->fields['333'].",
												`332`=".$q->fields['332'].",
												`342`=".$q->fields['342'].",
												`325`=".$q->fields['325'].",
												`194`=".$q->fields['194'].",
												`336`=".$q->fields['336'].",
												`331`=".$q->fields['331'].",
												`346`=".$q->fields['346'].",
												`335`=".$q->fields['335'].",
												`337`=".$q->fields['337'].",
												`341`=".$q->fields['341'].",
												`340`=".$q->fields['340'].",
												`329`=".$q->fields['329'].",
												`327`=".$q->fields['327'].",
												`326`=".$q->fields['326'].",
												`338`=".$q->fields['338'].",
												`320`=".$q->fields['320'].",
												`319`=".$q->fields['319'].",
												`348`=".$q->fields['348'].",
												`330`=".$q->fields['330'].",
												`349`=".$q->fields['349'].",
												`321`=".$q->fields['321'].",
												`316`=".$q->fields['316']."
												where 
													fecha='$ano-$mes-$nro'
												and id_tipo_ciber=1
													");
							
							
							}
						//$db->debug=false;
						}
						$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue($l."2", $dia_n)
								->setCellValue($l."3", "$nro-$mes")
								->setCellValue($l."4", $tot_llamadas)
								->setCellValue($l."5", $q->fields['tot_clientes'])
								->setCellValue($l."6", $q->fields['clientes_contactados'])
								->setCellValue($l."7", $q->fields['CD'])
								->setCellValue($l."8", $q->fields['CI'])
								->setCellValue($l."9", $q->fields['NC'])
								->setCellValue($l."10", str_replace(".",",",$q->fields['contactabilidad'])."%")
								->setCellValue($l."11", str_replace(".",",",$q->fields['CD_P'])."%")
								->setCellValue($l."12", str_replace(".",",",$q->fields['CI_P'])."%")
								->setCellValue($l."13", str_replace(".",",",$q->fields['NC_P'])."%")
								->setCellValue($l."14", round($intensidad,2))
								->setCellValue($l."15", $q->fields['PDP'])
								// ->setCellValue($l."16", $q->fields['IMP_PDP'])
								->setCellValue($l."17", str_replace(".",",",$q->fields['EFECTIVIDAD_PDP'])."%")
								->setCellValue($l."18", str_replace(".",",",$q->fields['TASA_CIERRE'])."%")
								
								->setCellValue($l."20", $q->fields['CD'])
									->setCellValue($l."21", $q->fields['positivo'])
									->setCellValue($l."22", $q->fields['indefinido'])
									->setCellValue($l."23", $q->fields['negativo'])
										->setCellValue($l."24", $q->fields['248'])
										->setCellValue($l."25", $q->fields['351'])
										->setCellValue($l."26", $q->fields['339'])
										->setCellValue($l."27", $q->fields['333'])
										->setCellValue($l."28", $q->fields['332'])
										->setCellValue($l."29", $q->fields['342'])
										->setCellValue($l."30", $q->fields['325'])
										->setCellValue($l."31", $q->fields['194'])
										->setCellValue($l."32", $q->fields['336'])
										->setCellValue($l."33", $q->fields['331'])
										->setCellValue($l."34", $q->fields['346'])
										->setCellValue($l."35", $q->fields['335'])
										->setCellValue($l."36", $q->fields['337'])
										->setCellValue($l."37", $q->fields['341'])
										->setCellValue($l."38", $q->fields['340'])
								
								
								->setCellValue($l."40", $q->fields['CI'])
									->setCellValue($l."41", $q->fields['329'])
									->setCellValue($l."42", $q->fields['327'])
									->setCellValue($l."43", $q->fields['326'])
									->setCellValue($l."44", $q->fields['338'])
									->setCellValue($l."45", $q->fields['320'])
									->setCellValue($l."46", $q->fields['319'])
									

								->setCellValue($l."48", $q->fields['NC'])
									->setCellValue($l."49", $q->fields['348'])
									->setCellValue($l."50", $q->fields['330'])
									->setCellValue($l."51", $q->fields['349'])
									->setCellValue($l."52", $q->fields['321'])
									->setCellValue($l."53", $q->fields['316'])
		
								/*->setCellValue($l."98", 20)
									->setCellValue($l."99",  round(($q->fields['PDP']/20),2))
									->setCellValue($l."100", 40)
									->setCellValue($l."101", round(($q->fields['PDP']/40),2))
									->setCellValue($l."102", 8)
									->setCellValue($l."103", 160)
									->setCellValue($l."104", round(($q->fields['PDP']/160),2)) 
									->setCellValue($l."105", 14)
									->setCellValue($l."106", (160*14))
									->setCellValue($l."107", round(($q->fields['PDP']/(160*14)),2))
									
									->setCellValue($l."133", (160*14))
									->setCellValue($l."134", $q->fields['IMP_PDP'])
									->setCellValue($l."135", round(($q->fields['IMP_PDP']/(160*14)),0))*/
								;
								
						$q->MoveNext();
					}
				
				
				//$objPHPExcel->getActiveSheet()->getStyle('C3:AG60')->getBorderStyle()->setBorderStyle(PHPExcel_Style_Border::BORDER_NONE);
				++$n;
				
		}
		
		$objPHPExcel->getActiveSheet()->getStyle('C3:AG60')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle('C2:AG2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('B2:AG18')->getBorders()->applyFromArray(
																							array(
																								'allborders' => array(
																									'style' => PHPExcel_Style_Border::BORDER_DASHED,
																									'color' => array(
																										'rgb' => '000000'
																									)
																								)
																							)
																						);
				for($h=6;$h<=18;$h++){
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setOutlineLevel(1);
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setVisible(false);
				}
				
				$objPHPExcel->getActiveSheet()->getStyle('B20:AG38')->getBorders()->applyFromArray(
																							array(
																								'allborders' => array(
																									'style' => PHPExcel_Style_Border::BORDER_DASHED,
																									'color' => array(
																										'rgb' => '000000'
																									)
																								)
																							)
																							);

				for($h=21;$h<=38;$h++){
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setOutlineLevel(1);
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setVisible(false);
				}
				
				$objPHPExcel->getActiveSheet()->getStyle('B40:AG46')->getBorders()->applyFromArray(
																							array(
																								'allborders' => array(
																									'style' => PHPExcel_Style_Border::BORDER_DASHED,
																									'color' => array(
																										'rgb' => '000000'
																									)
																								)
																							)
																							);	
				
				for($h=41;$h<=46;$h++){
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setOutlineLevel(1);
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setVisible(false);
				}
				
				$objPHPExcel->getActiveSheet()->getStyle('B48:AG53')->getBorders()->applyFromArray(
																							array(
																								'allborders' => array(
																									'style' => PHPExcel_Style_Border::BORDER_DASHED,
																									'color' => array(
																										'rgb' => '000000'
																									)
																								)
																							)
																							);	
				
				for($h=49;$h<=53;$h++){
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setOutlineLevel(1);
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setVisible(false);
				}
				
				$objPHPExcel->getActiveSheet()->getStyle('B55:AG60')->getBorders()->applyFromArray(
																							array(
																								'allborders' => array(
																									'style' => PHPExcel_Style_Border::BORDER_DASHED,
																									'color' => array(
																										'rgb' => '000000'
																									)
																								)
																							)
																							);	
				for($h=56;$h<=60;$h++){
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setOutlineLevel(1);
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setVisible(false);
				}	
				
				$objPHPExcel->getActiveSheet()->getStyle('B62:AG64')->getBorders()->applyFromArray(
																							array(
																								'allborders' => array(
																									'style' => PHPExcel_Style_Border::BORDER_DASHED,
																									'color' => array(
																										'rgb' => '000000'
																									)
																								)
																							)
																							);	
				for($h=63;$h<=64;$h++){
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setOutlineLevel(1);
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setVisible(false);
				}	
				
                $objPHPExcel->getActiveSheet()->getStyle('B66:AG69')->getBorders()->applyFromArray(
																							array(
																								'allborders' => array(
																									'style' => PHPExcel_Style_Border::BORDER_DASHED,
																									'color' => array(
																										'rgb' => '000000'
																									)
																								)
																							)
																							);	
				
				for($h=67;$h<=69;$h++){
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setOutlineLevel(1);
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setVisible(false);
				}	
				
				$objPHPExcel->getActiveSheet()->getStyle('B71:AG76')->getBorders()->applyFromArray(
																							array(
																								'allborders' => array(
																									'style' => PHPExcel_Style_Border::BORDER_DASHED,
																									'color' => array(
																										'rgb' => '000000'
																									)
																								)
																							)
																							);	
				
				for($h=72;$h<=76;$h++){
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setOutlineLevel(1);
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setVisible(false);
				}	
				
				$objPHPExcel->getActiveSheet()->getStyle('B78:AG90')->getBorders()->applyFromArray(
																							array(
																								'allborders' => array(
																									'style' => PHPExcel_Style_Border::BORDER_DASHED,
																									'color' => array(
																										'rgb' => '000000'
																									)
																								)
																							)
																							);																				

				for($h=79;$h<=90;$h++){
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setOutlineLevel(1);
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setVisible(false);
				}			
				
				$objPHPExcel->getActiveSheet()->getStyle('B92:AG94')->getBorders()->applyFromArray(
																							array(
																								'allborders' => array(
																									'style' => PHPExcel_Style_Border::BORDER_DASHED,
																									'color' => array(
																										'rgb' => '000000'
																									)
																								)
																							)
																							);																				
			
				for($h=93;$h<=94;$h++){
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setOutlineLevel(1);
					$objPHPExcel->getActiveSheet()->getRowDimension($h)->setVisible(false);
				}	
				
				$objPHPExcel->getActiveSheet()->getStyle('B96:AG135')->getBorders()->applyFromArray(
																							array(
																								'allborders' => array(
																									'style' => PHPExcel_Style_Border::BORDER_DASHED,
																									'color' => array(
																										'rgb' => '000000'
																									)
																								)
																							)
																							);
		//$objPHPExcel->getActiveSheet()->getStyle('c10:ag10')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
		if($_GET['peri']=="" and $_GET['cart']==""){		
			return false;		
		}
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setAutoSize(true);
		
		$objPHPExcel->getActiveSheet()->setTitle('reporte_gestiones_cc');
		
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

/* Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="archivo.xlsx"');
header('Cache-Control: max-age=0');*/
header('Content-Disposition: attachment;filename="reporte_gestiones_cc.xls"');
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
//$objWriter->save('archivo.xls');  
$objWriter->save('php://output');
//header("Location: archivo.xls");
exit;

?>