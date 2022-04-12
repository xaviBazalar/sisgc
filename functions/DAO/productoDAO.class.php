<?php 
	class ProductoDAO {
		public function insert($producto,$db){
			
			$result = $db->_query("INSERT INTO productos(idsegmento,idproveedor,producto,usureg) VALUES('$producto->seg','$producto->proveedor','$producto->producto','$producto->usureg')");
			if($result){ 
				echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; 
			}else{
				echo "No se pudo registrar los datos";
			}
		}
		
		public function update($producto,$db){
			
			$update = $db->_query("UPDATE productos
                                    SET idsegmento='$producto->seg',idproveedor='$producto->proveedor',producto='$producto->producto',idestado='$producto->estado'
                                    WHERE idproducto=$producto->id_prd");
            echo "Actualizado Correctamente";
		}
	}