<?
	require '../define_con.php';
	//if($_POST['buscar'])
	//{
	session_start();
	$cart=$_GET['cartera'];
		$venc=$_GET['ven'];
		$hoy=date('Y-m-d');
		$periodo=$_SESSION['periodo'];
		$sql="SELECT cl.idcliente,cl.cliente,p.producto,c.idcuenta,c.feccon,cp.fecven,m.monedas,cnt.contacto,cp.imptot,cp.diasmora,cnt.email,cp.idestado,cp.grupo
			  FROM cuentas c
			  JOIN clientes cl ON c.idcliente=cl.idcliente 
			  JOIN contactos cnt ON cl.idcliente=cnt.idcliente 
			  JOIN direcciones d ON cl.idcliente=d.idcliente 
			  JOIN carteras cr ON c.idcartera=cr.idcartera 
			  JOIN proveedores pr ON cr.idproveedor=pr.idproveedor 
			  JOIN productos p ON c.idproducto=p.idproducto 
			  JOIN monedas m ON c.idmoneda=m.idmoneda 
			  JOIN cuenta_periodos cp ON c.idcuenta=cp.idcuenta AND cp.idestado=1
			  /*JOIN emails e ON e.idcliente=cl.idcliente*/
			  WHERE 
			   cp.grupo!='OBS' and cp.grupo!='RET' and cp.grupo!='0' and cp.impmin!=6 and c.idcartera=$cart AND cp.idperiodo=$periodo /*and c.idsecuencia=0*/  ";
		switch ($venc)
		{
			case 1:
					$sql.=" and c.fecrev!='".date("Y-m-d")."' AND fecven<'".$hoy."' ";
					break;
			case 2:
					$sql.=" and c.fecrev!='".date("Y-m-d")."' AND fecven=ADDDATE('".$hoy."', +10) ";
					break;
			case 3:
					$sql.=" and c.fecrev!='".date("Y-m-d")."' AND fecven='".$hoy."' ";
					break;
		}
		$sql.="GROUP BY c.idcuenta ORDER BY c.idcliente";
	
		//if($_SERVER['REMOTE_ADDR']=="192.168.50.44"){echo $sql;}
		
		
		echo "<div id='design1'>";
		echo "<table>
			  <th>N&deg;</th>
			  <th>RUC</th>
			  <th>Cliente</th>
			  <th>Tipo</th>
			  <th>Documento</th>
			  <th>F.Emision</th>
			  <th>F.Vcto</th>
			  <th>Moneda</th>
			  <th>Deuda</th>
			  <th>Dias Mora</th>
			  <th>Contacto</th>
			  <th>Email</th>
			  <th>Estado</th>
			  <th>Grupo</th>
			  <th><input type='checkbox' name='email_ck' value='0' onclick='up_email_c(1);'></th>";
		$i=0;
		//echo "$sql";
		//return false;
		//$db->debug=true;
		$query=$db->Execute($sql);
		while(!$query->EOF)
		{
		$i++;
		echo "<tr>
			  <td>".$i."</td>
			  <td>".$query->fields['idcliente']."</td>
			  <td>".utf8_encode($query->fields['cliente'])."</td>
			  <td>".$query->fields['producto']."</td>
			  <td>".$query->fields['idcuenta']."</td>
			  <td>".$query->fields['feccon']."</td>
			  <td>".$query->fields['fecven']."</td>
			  <td>".$query->fields['monedas']."</td>
			  <td>".$query->fields['imptot']."</td>
			  <td>".$query->fields['diasmora']."</td>
			  <td>".$query->fields['contacto']."</td>
			  <td>".$query->fields['email']."</td>
			  <td>".$query->fields['idestado']."</td>
			   <td>".$query->fields['grupo']."</td>
			<td><input type='checkbox' name='email_ck' value='".$query->fields['idcuenta']."'></td>
			  </tr>";
		$query->MoveNext();	  
		}
		echo "</table>";
		echo "</div>";
	//}
		
?>