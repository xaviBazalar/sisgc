<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
  <link rel="stylesheet" type="text/css" media="all" href="css/estilos.css"/>        
  <script language="JavaScript">
   function doPrint()
   {
	document.all.item("noprint").style.visibility='hidden' 
	window.print()
	document.all.item("noprint").style.visibility='visible'
   }
  </script>
 </head>
 <body>  
<?php
 // libreria de funciones
 include("funciones.php");
 //conectar a base de datos
 $linkP=Conectarse();
 $linkM=ConectarseMysql();
 //toma variables del frm
 $periodo=$_POST['cmbperiodo'];       
 $campana=$_POST['cmbcampana']; 
 $chr=chr(32);      		 
 //eliminamos datos de tabla tmp 6
 $query="DELETE FROM tmp_ripley_6";
 $result0=pg_query($linkP,$query); 
?>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr>
    <td colspan="4">&nbsp;</td>
   </tr>
   <tr>
    <td>
     <div id=noprint>
      <form method="POST" action="rep35.php" name="frm">
       <table border="0" align="center" cellpadding="0" cellspacing="0" class="tabla" >
        <tr>
         <td colspan="6">&nbsp;</td>
        </tr>
        <tr>
        <tr>
         <td align="center">&nbsp;Periodo:
<?php
    	 $query="SELECT perid, pernombre
		   		 FROM periodos
				 ORDER BY perdesde DESC";
         $result=pg_query($linkP,$query);
		 $num= pg_num_rows($result);
?>
          <select name="cmbperiodo">
<?php
 		 for($i=0; $i<$num; $i++) 
		 {
		  $row= pg_fetch_array($result);
?>
          <option value='<?php echo $row[0]; ?>' <?php if ($periodo==$row[0]) echo "selected='selected'" ?>><?php echo $row[1]; ?></option>
<?php
		 }
?>
          </select>
         </td>
		 <td align="center">&nbsp;&nbsp;</td>        
         <td align="center">&nbsp;Campa&ntilde;a:
<?php
    	 $query="SELECT table_name 
		 		 FROM information_schema.tables 
				 WHERE SUBSTRING(table_name,1,3)='RPY' 
				 ORDER BY table_name";
         $result= mysql_query($query,$linkM); 
		 $num	= mysql_num_rows($result);
?>
          <select name="cmbcampana">
<?php
 		 for($i=0; $i<$num; $i++) 
		 {
		  $row= mysql_fetch_row($result);
?>
          <option value='<?php echo $row[0]; ?>' <?php if ($campana==$row[0]) echo "selected='selected'" ?>><?php echo $row[0]; ?></option>
<?php
		 }
?>
          </select>
         </td>
		 <td align="center">&nbsp;&nbsp;</td>
         <td align="center"><input type="submit" value="Buscar" class="BOTON"></td>
         <td align="center">&nbsp;&nbsp;</td>
         <td align="center"><a href="rep35exp.php"><img src="images/arrow-blue-rounded-right.jpg" width="30" height="20" border=1 alt="Exportar"></a>&nbsp;&nbsp;</td>
        </tr>
        <tr> 
        </tr>
        <tr>
         <td colspan="6">&nbsp;</td>
        </tr>
       </table>
      </form>
     </div>
    </td>
   </tr>
<!--
   <tr>
    <td>
 	<h1 style="font-size:16px; color:#FF0000">&nbsp;&nbsp;&nbsp;EN DESARROLLO, NO USAR !!!<br><br></h1>
    </td>
   </tr>

  -->  
   <tr>
    <td>
     <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="tabla">
      <thead>
       <tr>
        <th colspan="8" class="tablatitulo">RIPLEY FRONTEND - Transferencia de Data</th>
       </tr>
       <tr>
        <th width="30" class="tablasubtitulo" scope="col">N&deg;</th>       
        <th width="100" class="tablasubtitulo" scope="col">Id</th>        
        <th width="100" class="tablasubtitulo" scope="col">Telefono</th>
        <th width="100" class="tablasubtitulo" scope="col">Cliente</th>
        <th width="100" class="tablasubtitulo" scope="col">DNI</th>
        <th width="100" class="tablasubtitulo" scope="col">Evento</th>
        <th width="100" class="tablasubtitulo" scope="col">Agente</th>        
        <th width="150" class="tablasubtitulo" scope="col">Fecha/Hora</th>
       </tr>
      <thead>      
      <tbody>
       <tr>
        <td colspan="8">
         <div class="inner">
          <table width="780" border="0" cellpadding="0" cellspacing="0" class="tabladet">
<?php
		   if (empty($campana) && empty($periodo))
    	   {	
           }
		   else
           {		 
    	    $query="SELECT perdesde, perhasta
		   	   	    FROM periodos
				    WHERE perid=$periodo";
            $result=pg_query($linkP,$query);
		    $num= pg_num_rows($result);
  		    for($i=0; $i<$num; $i++) 
		    {
		     $row= pg_fetch_array($result);
			 $perdesde	=$row[0];
		     $perhasta	=$row[1];
			}
		    //(disposition=-2 or disposition=-3 or disposition=-4 or disposition=-6)
            $query="SELECT id, phone, cliente, dni, disposition, agent, lastupdated, cb_datetime
					FROM $campana 
					WHERE  (disposition between -7 AND -2) AND 
					      (SUBSTRING(lastupdated,1,10) between '$perdesde' AND '$perhasta') AND 
						  (lastupdated != cb_datetime)"; 			
            $result1=mysql_query($query,$linkM); 
            $num1	=mysql_num_rows($result1);
            //Si hay registros			 
            if ($num1>0)
            {
             for($i=0; $i<$num1; $i++) 
             {
              $row1			= mysql_fetch_array($result1);
			  $id			= $row1[0];		
			  $phone		= $row1[1];		
			  $cliente		= $row1[2];		
			  $dni			= $row1[3];		
			  $disposition	= $row1[4];		
			  $agent		= $row1[5];				  
			  $lastupdated	= $row1[6];		
			  $cb_datetime	= $row1[7];					  
              //  
			   $query="INSERT INTO tmp_ripley_6 (id, phone, cliente, dni, disposition, agent, lastupdated, cb_datetime, periodo, campana) 
				        				values ('$id', '$phone', '$cliente', '$dni', '$disposition', '$agent', '$lastupdated', '$cb_datetime', $periodo, '$campana')";
               $result0=pg_query($linkP,$query); 
             }			
            }	
 			//
	       	//mostramos data final
           	$query="SELECT id, phone, cliente, dni, disposition, agent, lastupdated
		   	FROM tmp_ripley_6
			ORDER BY phone";
           	$result3=pg_query($linkP,$query); 
           	$num3=pg_num_rows($result3);
           	for($k=0; $k<$num3; $k++) 
           	{
             $row3= pg_fetch_array($result3);
             //establece colores de filas
             if ( ($k % 2)==0 )
             {
?>			 
              <tr class='tabladettr1'>
<?php          
             }
             else
             {
?>			 
              <tr class='tabladettr2'>
<?php                        
             }
?>
	           <td width="30" 	class="tabladettd"><?php $contador++ ; echo $contador; ?></td>
               <td width="100"  class="tabladettd"><?php echo Alerta($row3[0]); ?></td>
               <td width="100"  class="tabladettd"><?php echo Alerta($row3[1]); ?></td>
               <td width="100"  class="tabladettd"><?php echo Alerta($row3[2]); ?></td>
               <td width="100"  class="tabladettd"><?php echo Alerta($row3[3]); ?></td>
               <td width="100"  class="tabladettd"><?php echo Alerta($row3[4]); ?></td>
               <td width="100"  class="tabladettd"><?php echo Alerta($row3[5]); ?></td>               
               <td width="150"  class="tabladettd"><?php echo Alerta($row3[6]); ?></td>                              
              </tr>
<?php               
            }		  
           }			  
           //liberamos el resultado
           pg_free_result($result0);
           pg_free_result($result1);
           //pg_free_result($result2);
           pg_free_result($result3);									
           //cerramos sesion
           pg_close($link);							
?>
          </table>
         </div>
        </td>
       </tr>
      </tbody>
     </table>
    </td>    
   </tr>        
  </table>     
 </body>
</html>