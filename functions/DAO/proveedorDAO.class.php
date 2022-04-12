<?php 
	class ProveedorDAO {
		public function insert($provee,$db){
			$result = $db->_query("INSERT INTO proveedores(iddoi,idpersoneria,proveedor,coddpto,codprov,coddist,telefonos,documento,contacto,observacion,idestado,usureg)
                                    VALUES('$provee->iddoi','$provee->idpersoneria','$provee->proveedor','$provee->coddpto','$provee->codprov','$provee->coddist','$provee->tel','$provee->doc','$provee->cntac','$provee->obs','$provee->estado','$provee->usureg')");
            if($result){ 
				echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; 
			}else{
				echo "No se pudo registrar los datos";
			}
		}
		
		public function update($provee,$db){
			$update = $db->_query("UPDATE proveedores
                                        SET iddoi='$provee->iddoi',idpersoneria='$provee->idpersoneria',proveedor='$provee->proveedor',coddpto='$provee->coddpto',
                                        codprov='$provee->codprov',coddist='$provee->coddist',telefonos='$provee->tel',documento='$provee->doc',
                                        contacto='$provee->cntac',observacion='$provee->obs',idestado='$provee->estado'
                                        WHERE idproveedor=$provee->id_prove");
							
            echo "Actualizado Correctamente";
		}
	}