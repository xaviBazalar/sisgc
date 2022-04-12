<?php
//include '../scripts/conexion.php';
include '../define_con.php';
session_start();
$id = $_SESSION['iduser'];
if(isset($_POST['parametros'])){
    $valor = $_POST['parametros'];
	/*if($valor=="fuente"){
		require_once('fuente.class.php');
        if(isset($_POST['value']) && $_POST['value']==!"" ){
			$fuente= new Fuente();
			$fuente->fuente=$_POST['value'];
			$fuente->usureg=$id;
			
			$function= new FuenteDAO();

            if(isset($_POST['update'])){
                $fuente->id_ft=$_POST['id'];
                $fuente->estado=$_POST['value2'];
				$function->update($fuente,$db);
				return false;
            }
			$function->insert($fuente,$db);
			return false;
		}
    }*/
 //--------------------------------------------------------------------
    if($valor=="nivel"){
		require_once('nivel.class.php');
        if(isset($_POST['value']) && $_POST['value']==!"" ){
			$nivel= new Nivel();
			$nivel->nivel=$_POST['value'];
			$nivel->usureg=$id;
			
			$function= new NivelDAO();

            if(isset($_POST['update'])){
                $nivel->id_nivel=$_POST['id'];
                $nivel->estado=$_POST['value2'];
				$function->update($nivel,$db);
				return false;
            }
			$function->insert($nivel,$db);
			return false;
		}
    }
 //--------------------------------------------------------------------
    if($valor=="moneda"){
		require_once('moneda.class.php');
        if(isset($_POST['value']) && $_POST['value']==!"" ){
		
			$moneda= new Moneda();
				$moneda->moneda=$_POST['value'];
				$moneda->simbol=$_POST['value3'];
				$moneda->usureg=$id;
			$function= new MonedaDAO();
			
            if(isset($_POST['update'])){
                $moneda->id_moneda=$_POST['id'];
                $moneda->estado=$_POST['value2'];
				$function->update($moneda,$db);
                return false;
            }
			$function->insert($moneda,$db);
            return false;
        
		}
	}
 //--------------------------------------------------------------------
    if($valor=="parent"){
		require_once('parentesco.class.php');
        if(isset($_POST['value']) && $_POST['value']==!"" ){
			
			$parentesco= new Parentesco();
				$parentesco->parentesco=$_POST['value'];
				$parentesco->usureg=$id;
				
			$function= new ParentescoDAO();
			
            if(isset($_POST['update'])){
                $parentesco->id_parent=$_POST['id'];
                $parentesco->estado=$_POST['value2'];
				$function->update($parentesco,$db);
                return false;
            }
			$function->insert($parentesco,$db);
            return false;
        }

    }
 //--------------------------------------------------------------------
    if($valor=="pers"){
		require_once('personeria.class.php');
        if(isset($_POST['value']) && $_POST['value']==!"" ){
			
			$personeria= new Personeria();
				$personeria->personeria=$_POST['value'];
            
			$function= new PersoneriaDAO();

            if(isset($_POST['update'])){
                $personeria->id_person=$_POST['id'];
                $personeria->estado=$_POST['value2'];
				$function->update($personeria,$db);
                return false;
            }
            $function->insert($personeria,$db);
			return false;
        }

    }
 //--------------------------------------------------------------------
    if($valor=="doc"){
		require_once('doi.class.php');
        if(isset($_POST['value']) && $_POST['value']==!"" ){
			
			$doi= new Doi();
				$doi->doi=$_POST['value'];
				$doi->usureg=$id;
			$function= new DoiDAO();
            
            if(isset($_POST['update'])){
                $doi->id_doi=$_POST['id'];
                $doi->estado=$_POST['value2'];
                $function->update($doi,$db);
                return false;
            }
			$function->insert($doi,$db);
			return false;
		}
    }
 //--------------------------------------------------------------------
    //Esta es la seccion Periodos
    if($valor=="peri"){
		require_once('periodo.class.php');
        if(isset($_POST['value']) && $_POST['value']==!"" ){
			$periodo= new Periodo();
				$periodo->periodo=$_POST['value'];
				$periodo->fec_ini=$_POST['value2'];
				$periodo->fec_fin=$_POST['value3'];
				$periodo->usureg=$id;
				
			$function= new PeriodoDAO();
			if(isset($_POST['update'])){
				$periodo->id_periodo=$_POST['id'];
                $function->update($periodo,$db);               
                return false;
            }
           $function->insert($periodo,$db);
		   return false;
         
        }

    }
 //--------------------------------------------------------------------
    //Esta es ls seccion Usuarios
    if($valor=="user"){
		require_once('usuario.class.php');
        if(isset($_POST['value']) && $_POST['value']==!"" ){
			$usuario = new Usuario();
			$usuario->nombre=$_POST['value'];
			$usuario->doc=$_POST['value2'];
			$usuario->ndoc=$_POST['value3'];
			$usuario->fn=$_POST['value4'];
			$usuario->dom=$_POST['value5'];
			$usuario->fono=$_POST['value6'];
			$usuario->email=$_POST['value7'];
			$usuario->usrs=$_POST['value8'];
			$usuario->upas=md5($_POST['value8']);
			$usuario->fi=$_POST['value10'];
			$usuario->cart=$_POST['value11'];
			$usuario->niv=$_POST['value12'];
			$usuario->est=$_POST['value13'];
			$usuario->dpto=$_POST['value14'];
			$usuario->prov=$_POST['value15'];
			$usuario->dist=$_POST['value16'];
			$usuario->prove=$_POST['value17'];
			
			$function = new UsuarioDAO();
			
            if(isset($_POST['update'])){
                $id=$_POST['id'];
				$function->update($usuario,$db,$id);    
                return false;
            }
			
			$function->insert($usuario,$db);
		}
    }
 //--------------------------------------------------------------------
    if($valor=="prove"){
		require_once('proveedor.class.php');
        if(isset($_POST['value']) && $_POST['value']==!"" ){
			$provee = new Proveedor();
            $provee->proveedor=$_POST['value'];
            $provee->iddoi=$_POST['value2'];
            $provee->idpersoneria=$_POST['value3'];
            $provee->doc=$_POST['value4'];
            $provee->tel=$_POST['value5'];
			$provee->cntac=$_POST['value6'];
            $provee->obs=$_POST['value7'];
            $provee->estado=$_POST['value8'];
           	$provee->coddpto=$_POST['value9'];
            $provee->codprov=$_POST['value10'];
            $provee->coddist=$_POST['value11'];
            
			$function = new ProveedorDAO();
			
            if(isset($_POST['update'])){
                $provee->id_prove=$_POST['id'];
                $function->update($provee,$db);
                return false;
            }
				$function->insert($provee,$db);	 
			    return false;
		}
    }
 //--------------------------------------------------------------------
     if($valor=="seg"){
		require_once('segmto.class.php');
        if(isset($_POST['value']) && $_POST['value']==!"" ){
			$segmento = new Segmento();
			$segmento->segmento=$_POST['value'];
			$segmento->usureg=$id;
            
			$function = new SegmentoDAO();
				
            if(isset($_POST['update'])){
                $segmento->id_seg=$_POST['id'];
                $segmento->estado=$_POST['value2'];
                $function->update($segmento,$db);
                return false;
            }
				$function->insert($segmento,$db);
			return false;	
		}
    }
 //--------------------------------------------------------------------
    if($valor=="produ"){
        require_once('producto.class.php');
        if(isset($_POST['value']) && $_POST['value']==!"" ){
			$producto = new Producto();
            $producto->producto=$_POST['value'];
            $producto->seg=$_POST['value3'];
            $producto->proveedor=$_POST['value2'];
			$producto->usureg=$id;

            $function = new ProductoDAO();
			
            if(isset($_POST['update'])){
                $producto->id_prd=$_POST['id'];
                $producto->estado=$_POST['value4'];
				$function->update($producto,$db);
                return false;
            }
           
            $function->insert($producto,$db);
			return false;	
         
		}
    }
 //--------------------------------------------------------------------
    if($valor=="cart"){

        if(isset($_POST['value']) && $_POST['value']==!"" ){
            $cart=$_POST['value'];
            $idpro=$_POST['value2'];
            $idest=$_POST['value3'];
            
            if(isset($_POST['update'])){
                $id=$_POST['id'];
                $update = $db->Execute("UPDATE carteras
                                        SET idproveedor='$idpro',cartera='$cart',idestado='$idest'
                                        WHERE idcartera=$id");
                echo "Actualizado Correctamente";
                return false;
            }
            $result = $db->Execute("INSERT INTO carteras(idproveedor,cartera,idestado) VALUES('$idpro','$cart','$idest')");
             if($result){ echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; }
        }else{
            echo "No se pudo registrar los datos";
        }

    }
 //--------------------------------------------------------------------
    if($valor=="cont"){

        if(isset($_POST['value']) && $_POST['value']==!"" ){
            $conta=$_POST['value'];
            
            if(isset($_POST['update'])){
                $id=$_POST['id'];
                $id_c=$_POST['value2'];
                $update = $db->Execute("UPDATE contactabilidad
                                        SET contactabilidad='$conta',idestado=$id_c
                                        WHERE idcontactabilidad=$id");
                echo "Actualizado Correctamente";
                return false;
            }
            $result = $db->Execute("INSERT INTO contactabilidad(contactabilidad) VALUES('$conta')");
             if($result){ echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; }
        }else{
            echo "No se pudo registrar los datos";
        }

    }
 //--------------------------------------------------------------------
    if($valor=="ubic"){

        if(isset($_POST['value']) && $_POST['value']==!"" ){
            $ubi=$_POST['value'];

            if(isset($_POST['update'])){
                $id=$_POST['id'];
                $id_ub=$_POST['value2'];
                $update = $db->Execute("UPDATE ubicabilidad
                                        SET ubicabilidad='$ubi',idestado=$id_ub
                                        WHERE idubicabilidad=$id");
                echo "Actualizado Correctamente";
                return false;
            }
            $result = $db->Execute("INSERT INTO ubicabilidad(ubicabilidad) VALUES('$ubi')");
             if($result){ echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; }
        }else{
            echo "No se pudo registrar los datos";
        }

    }
 //--------------------------------------------------------------------
    if($valor=="or_d"){

        if(isset($_POST['value']) && $_POST['value']==!"" ){
            $or_d=$_POST['value'];
            
            if(isset($_POST['update'])){
                $id=$_POST['id'];
                $es_od=$_POST['value2'];
                $update = $db->Execute("UPDATE origen_direcciones
                                        SET origendireccion='$or_d',idestado=$es_od
                                        WHERE idorigendireccion=$id");
                echo "Actualizado Correctamente";
                return false;
            }
            $result = $db->Execute("INSERT INTO origen_direcciones(origendireccion) VALUES('$or_d')");
             if($result){ echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; }
        }else{
            echo "No se pudo registrar los datos";
        }

    }
 //--------------------------------------------------------------------
    if($valor=="or_t"){

        if(isset($_POST['value']) && $_POST['value']==!"" ){
            $or_t=$_POST['value'];
            
            if(isset($_POST['update'])){
                $id=$_POST['id'];
                $es_od=$_POST['value2'];
                $update = $db->Execute("UPDATE origen_telefonos
                                        SET origentelefono='$or_t',idestado=$es_od
                                        WHERE idorigentelefono=$id");
                echo "Actualizado Correctamente";
                return false;
            }
            $result = $db->Execute("INSERT INTO origen_telefonos(origentelefono) VALUES('$or_t')");
             if($result){ echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; }
        }else{
            echo "No se pudo registrar los datos";
        }

    }
 //--------------------------------------------------------------------
    if($valor=="gest_g"){

        if(isset($_POST['value']) && $_POST['value']==!"" ){
            $ges_g=$_POST['value'];
            if(isset($_POST['update'])){
                $id=$_POST['id'];
                $ides=$_POST['value2'];
                $update = $db->Execute("UPDATE grupo_gestiones
                                        SET grupogestion='$ges_g',idestado='$ides'
                                        WHERE idgrupogestion=$id");
				
                echo "Actualizado Correctamente";
                return false;
            }
            $result = $db->Execute("INSERT INTO grupo_gestiones(grupogestion) VALUES('$ges_g')");
             if($result){ echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; }
        }else{
            echo "No se pudo registrar los datos";
        }

    }
 //--------------------------------------------------------------------
    if($valor=="gest_r"){

        if(isset($_POST['value']) && $_POST['value']==!"" ){
            $grup=$_POST['value'];
            $reslt=$_POST['value2'];
			$id_c=$_POST['value3'];	
			$flag_campo=$_POST['value5'];
            if(isset($_POST['update'])){
                $id=$_POST['id'];
                $ides=$_POST['value4'];
                $update = $db->Execute("UPDATE resultados
                                        SET idgrupogestion='$grup',resultado='$reslt',idestado='$ides',idcompromisos='$id_c',flag='$flag_campo'
                                        WHERE idresultado=$id");
                echo "Actualizado Correctamente";
                return false;
            }
            $result = $db->Execute("INSERT INTO resultados(idgrupogestion,resultado,idcompromisos,flag) VALUES('$grup','$reslt','$id_c','$flag_campo')");
             if($result){ echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; }
        }else{
            echo "No se pudo registrar los datos";
        }

    }
 //--------------------------------------------------------------------
    if($valor=="just"){

        if(isset($_POST['value']) && $_POST['value']==!"" ){
            $idest=$_POST['value3'];
            $id_r=$_POST['value2'];
            $just=$_POST['value'];
            if(isset($_POST['update'])){
                $id=$_POST['id'];
                $update = $db->Execute("UPDATE justificaciones
                                        SET idresultado='$id_r',justificacion='$just',idestado='$idest'
                                        WHERE idjustificacion=$id");
                echo "Actualizado Correctamente";
                return false;
            }
            $result = $db->Execute("INSERT INTO justificaciones(idresultado,justificacion) VALUES('$id_r','$just')");
             if($result){ echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; }
        }else{
            echo "No se pudo registrar los datos";
        }
    }
 //--------------------------------------------------------------------
    if($valor=="r_xc"){

        if(isset($_POST['value']) && $_POST['value']==!"" ){
            $id_r=$_POST['value'];
            $id_c=$_POST['value2'];

            if(isset($_POST['update'])){
                $id=$_POST['id'];
                $ides=$_POST['value3'];
                $update = $db->Execute("UPDATE resultado_carteras
                                        SET idresultado='$id_r',idcartera='$id_c',idestado='$ides'
                                        WHERE idresultadocartera=$id");
                echo "Actualizado Correctamente";
                return false;
            }
            $result = $db->Execute("INSERT INTO resultado_carteras(idcartera,idresultado) VALUES('$id_c','$id_r')");
             if($result){ echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; }
        }else{
            echo "No se pudo registrar los datos";
        }

    }
 //--------------------------------------------------------------------
    if($valor=="plano"){

        if(isset($_POST['value']) && $_POST['value']==!"" ){
             $dpto=$_POST['value2'];
             $prov=$_POST['value3'];
             $dist=$_POST['value4'];
                    $re_p = $db->Execute("SELECT * FROM ubigeos  WHERE coddpto=$dpto AND codprov=$prov AND coddist=$dist");
            $idubi=$re_p->fields['idubigeo'];
            $plano=$_POST['value'];
			
				 if(isset($_POST['update'])){
					$id=$_POST['id'];
					
					$update = $db->Execute("UPDATE planos
											SET idubigeo='$idubi',plano='$plano'
											WHERE idplano=$id");
					echo "Actualizado Correctamente";
					return false;
				}
			
            $result = $db->Execute("INSERT INTO planos(idubigeo,plano) VALUES('$idubi','$plano')");
             if($result){ echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; }
        }else{
            echo "No se pudo registrar los datos";
        }

    }
 //--------------------------------------------------------------------
    if($valor=="cuadr"){

        if(isset($_POST['value']) && $_POST['value']==!"" ){
            $cuadr=$_POST['value'];
            $idpla=$_POST['value2'];
			$vacio=$_POST['value3'];
				 if(isset($_POST['update'])){
					$id=$_POST['id'];
					
					$update = $db->Execute("UPDATE cuadrantes
											SET idplano='$idpla',cuadrante='$cuadr',vacio='$vacio'
											WHERE idcuadrante=$id");
					echo "Actualizado Correctamente";
					return false;
				}
            $result = $db->Execute("INSERT INTO cuadrantes(idplano,cuadrante,vacio) VALUES('$idpla','$cuadr','$vacio')");
             if($result){ echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; }
			}else{
				echo "No se pudo registrar los datos";
			}

    }
//-------------------------------------------------------------------------------	
	if($valor=="p_act"){

        if(isset($_POST['value']) && $_POST['value']==!"" ){
            $acti=$_POST['value'];
			 if(isset($_POST['update'])){
                $id=$_POST['id'];
                $ides=$_POST['value2'];
                $update = $db->Execute("UPDATE actividades
                                        SET actividad='$acti',idestado='$ides'
                                        WHERE idactividad=$id");
                echo "Actualizado Correctamente";
                return false;
            }
			
            $result = $db->Execute("INSERT INTO actividades(actividad) VALUES('$acti')");
             if($result){ echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; }
        }else{
            echo "No se pudo registrar los datos";
        }

    }
 //--------------------------------------------------------------------
    if($valor=="i_pre"){
        if(isset($_POST['value']) && $_POST['value']==!"" ){
            $predio=$_POST['value'];
            
            if(isset($_POST['update'])){
                $id=$_POST['id'];
                $id_es=$_POST['value2'];
                $update = $db->Execute("UPDATE tipo_predio
                                        SET tipo_predio='$predio',idestado=$id_es
                                        WHERE idpredio=$id");
                echo "Actualizado Correctamente";
                return false;
            }
            $result = $db->Execute("INSERT INTO tipo_predio(tipo_predio,usureg) VALUES('$predio','$id')");
             if($result){ echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; }
			}else{
				echo "Ingrese un Nivel";
			}

    }
 //--------------------------------------------------------------------
    if($valor=="i_mpre"){
        if(isset($_POST['value']) && $_POST['value']==!"" ){
            $mpredio=$_POST['value'];
            
            if(isset($_POST['update'])){
                $id=$_POST['id'];
                $id_es=$_POST['value2'];
                $update = $db->Execute("UPDATE material_predio
                                        SET material='$mpredio',idestado=$id_es
                                        WHERE idmaterial_predio=$id");
                echo "Actualizado Correctamente";
                return false;
            }
            $result = $db->Execute("INSERT INTO material_predio(material,usureg) VALUES('$mpredio','$id')");
             if($result){ echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; }
			}else{
				echo "Ingrese un Nivel";
			}

    }

 //--------------------------------------------------------------------
    if($valor=="i_npisos"){
        if(isset($_POST['value']) && $_POST['value']==!"" ){
            $piso=$_POST['value'];
            
            if(isset($_POST['update'])){
                $id=$_POST['id'];
                $id_es=$_POST['value2'];
                $update = $db->Execute("UPDATE nro_pisos
                                        SET piso='$piso',idestado=$id_es
                                        WHERE idnro_pisos=$id");
                echo "Actualizado Correctamente";
                return false;
            }
            $result = $db->Execute("INSERT INTO nro_pisos(piso,usureg) VALUES('$piso','$id')");
             if($result){ echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; }
			}else{
				echo "Ingrese un Nivel";
			}

    }
 //--------------------------------------------------------------------
    if($valor=="i_cpared"){
        if(isset($_POST['value']) && $_POST['value']==!"" ){
            $color=$_POST['value'];
            
            if(isset($_POST['update'])){
                $id=$_POST['id'];
                $id_es=$_POST['value2'];
                $update = $db->Execute("UPDATE colores_pared
                                        SET color='$color',idestado=$id_es
                                        WHERE idcolor_pared=$id");
                echo "Actualizado Correctamente";
                return false;
            }
            $result = $db->Execute("INSERT INTO colores_pared(color,usureg) VALUES('$color','$id')");
             if($result){ echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; }
			}else{
				echo "Ingrese un Nivel";
			}

    }	
	
	
}

?>
