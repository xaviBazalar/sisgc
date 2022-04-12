<?php 

	class UsuarioDAO {
		public function insert($usuario,$db){
				$result = $db->_query("INSERT INTO usuarios(idproveedor,iddoi,idcartera,idnivel,usuario,documento,fechanacimiento,email,direccion,coddpto,codprov,coddist,telefonos,fechaingreso,login,clave,idestado,idmodalidad,idturno,idempresa,horarios,horariolv)
						VALUES('$usuario->prove','$usuario->doc','$usuario->cart','$usuario->niv','$usuario->nombre','$usuario->ndoc','$usuario->fn','$usuario->email','$usuario->dom','$usuario->dpto','$usuario->prov','$usuario->dist','$usuario->fono','$usuario->fi','$usuario->usrs'
						,'$usuario->upas','$usuario->est','$usuario->modal','$usuario->turno','$usuario->empresa','$usuario->hors','$usuario->horlv')");
				if($result){ 
					echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; 
				}else{
					echo "No se pudo registrar los datos";
				}
				return false;
		}
		
		public function update($usuario,$db,$id){
				$update = $db->_query("UPDATE usuarios
                                        SET idproveedor='$usuario->prove',iddoi=$usuario->doc,idcartera=$usuario->cart,idnivel=$usuario->niv,usuario='$usuario->nombre',
                                        documento=$usuario->ndoc,fechanacimiento='$usuario->fn',email='$usuario->email',direccion='$usuario->dom',coddpto='$usuario->dpto',codprov='$usuario->prov',
                                        coddist='$usuario->dist',telefonos='$usuario->fono',fechaingreso='$usuario->fi',login='$usuario->usrs',idestado='$usuario->est',idmodalidad='$usuario->modal',
										idturno='$usuario->turno',idempresa='$usuario->empresa',horarios='$usuario->hors',horariolv='$usuario->horlv'
                                        WHERE idusuario=$id");
                echo "Actualizado Correctamente";
				
		}
	}