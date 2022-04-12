<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"] == "") {
	header("Location: login.php");
}

if (!isset($_GET["parametros"]) || $_GET["parametros"]=="") {
	header("Location: login.php");
	return false;
}
require 'define_con.php';
$n=0;
if(isset($_GET['parametros'])){
    $id_param=$_GET['parametros'];
    switch($id_param){
        case "pe_o":
			$pag=0;
			$r_pag=20;
			
			
			$sql="SELECT * FROM periodos order by periodo desc";
			$total =$db->Execute($sql);

				$t_regist=$total->_numOfRows;
				
				if(isset($_GET['pag'])){
					$pag=$_GET['pag'];
					$pag=$pag-1;
					$pag=$pag*$r_pag;
					$sql.=" LIMIT $pag,$r_pag";
				}else{
					$sql.=" LIMIT $pag,$r_pag";
				}
						if($_GET['pag']!=1){
							$n=$_GET['pag']-1;
							$n=$n*$r_pag;
						}
            $result = $db->Execute($sql);
			
			if(!$result){
						echo "<input class='btn' type=\"button\" onclick=\"m4('i_p');\" value=\"Agregar Nuevo \"/>";
						return false;
						}
             echo "<div id=\"ykBody\">
                <center>
                <fieldset ><legend>Periodos</legend>
                    <div id=\"design1\">
                        <table style='text-align:left;'><th>Nº</th><th>Id</th><th>Descripcion</th><th>Inicio</th><th>Fin</th> ";
                         while (!$result->EOF) {
						 $id_per=$result->fields['idperiodo'];
                        //for ($i=0, $max=$result->FieldCount(); $i < $max; $i++)
                        echo "<tr><td>".++$n."</td>
                            <td style='background-color:white;'>".$result->fields['idperiodo']."</td>
                            <td style='background-color:white;'><a style='text-decoration:underline;cursor:pointer;' onclick=\"m4('i_p&&update=$id_per');\"> ".$result->fields['periodo']."<a></td>
                            <td style='background-color:white;'>".$result->fields['fecini']."</td>
                            <td style='background-color:white;'>".$result->fields['fecfin']."</td></tr>";
                        $result->MoveNext();
                                         }
            $cont = $db->Execute("SELECT COUNT(*) FROM periodos");
            ?>
			<!--Pie Paginacion-->	
				<tr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</tr>
			  <tr>
								<?php
									
									$t_p=$t_regist/$r_pag;
									$Res=$t_regist%$r_pag;
									if($Res>0) $t_p=floor($t_p)+1;
										$Ant=$_GET['pag']-1;
									    $Sig=$_GET['pag']+1;
								?>
			
					<td colspan="5">
						<?php //if(!isset($_GET['ndoc']) and !isset($_GET['name'])){?>
							<?php
							
							if($_GET['pag']!=1 && $t_p!=0 )
									echo "<a onclick=\"m4('pe_o&pag=1');\" href='#'>Inicio</a> &nbsp;&nbsp;<a onclick=\"m4('pe_o&&pag=$Ant');\" href='#'> « Anterior&nbsp;</a>----" ;
								else
									echo "Inicio &nbsp;Anterior&nbsp;----";
								
							if($_GET['pag']!=$t_p && $t_p!=0)
									echo "<a onclick=\"m4('pe_o&pag=$Sig');\" href='#'>&nbsp;Siguiente »</a>&nbsp;&nbsp;<a onclick=\"m4('pe_o&&pag=$t_p');\" href='#'>Fin</a> " ;
								else
									echo "&nbsp;Siguiente&nbsp; Fin";
								
							?>
						<a style='float:right;' href='functions/xlsx.php?dato=periodo'>Exportar a Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style='float:right;'>  Paginas   <?php echo $_GET['pag']." de ".$t_p?> / <?php echo $t_regist; ?> Registros  &nbsp;</b>
						<?php// }?>
					</td>
				</tr>
		<!--Fin Pie Paginacion-->	
<?php echo       "</table></div>
                 
                 </fieldset>
                <input type=\"button\" class='btn' onclick=\"m4('i_p');\" value=\"Agregar Nuevo \"/>
                    </center>
                </div>
                ";
            break;
//------------------------------------------------------------------------------------------------------------			
        case "i_p":
			if(isset($_GET['update']) && $_GET['update']!==""){
                $id_d=$_GET['update'];
                $sql= $db->Execute("SELECT * FROM periodos WHERE idperiodo=$id_d");
				$per=$sql->fields['periodo'];
                $f_ini=$sql->fields['fecini'];
				$f_fin=$sql->fields['fecfin'];
                $est=$sql->fields['idestado'];
                 echo "<div id=\"ykBody\"><center>
            <fieldset style='width:300px;text-align:left;'><legend>Actualizar Periódo</legend>
				
                    <table style='width:240px;'>
                       <tr><td class='zpFormLabel'>Nombre:</td><td><input value='$per' type='text'  id='peri'/></td></tr>
                        <tr><td class='zpFormLabel'>Desde:</td><td><input value='$f_ini' disabled  id='desde'/></td>
                        <td><button onclick=\"cale();\" id=\"bcalendario1\">...</button></td></tr>
                        
                        <tr><td class='zpFormLabel'>Hasta:</td><td><input value='$f_fin' disabled type=\"text\"  id='hasta'/></td>
                        <td><button onclick=\"cale1();\" id=\"bcalendario2\">...</button></td></tr>";
                    
                echo "</table>
                </fieldset>
                                
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" onclick=\"insert('peri&&update=1&&id=$id_d');\" class='btn' value=\"Actualizar \"/>
                <td class=\"botones\"><input type=\"button\" id='atra' class='btn' onclick=\"m4('pe_o&pag=1');\"  value=\"Regresar \"/></td>
                    </center>
						</div>
                ";
            return false;

             }
            echo "<div id=\"ykBody\"><center>
            <fieldset style='width:300px;text-align:left;'><legend>Ingresar Periódo</legend>
				
                    <table style='width:240px;'>
                        <tr><td class='zpFormLabel'>Nombre:</td><td><input  type='text'  id='peri'/></td></tr>
                        <tr><td class='zpFormLabel'>Desde:</td><td><input  disabled  id='desde'/></td>
                        <td><button onclick=\"cale();\" id=\"bcalendario1\">...</button></td></tr>
                        
                        <tr><td class='zpFormLabel'>Hasta:</td><td><input  disabled type=\"text\"  id='hasta'/></td>
                        <td><button onclick=\"cale1();\" id=\"bcalendario2\">...</button></td></tr>
                                </table>
					
                </fieldset>
                                
                <div id=\"ykBodys\" style=\"color:red;\">
                </div>
                <input type=\"button\" onclick=\"insert('peri');\"  class='btn' value=\"Agregar \"/>
                <td class=\"botones\"><input type=\"button\"  id='atra' class='btn' onclick=\"m4('pe_o&pag=1');\"  value=\"Regresar \"/></td>
                    </center>
						</div>
                ";
            break;
    }


}
?>
