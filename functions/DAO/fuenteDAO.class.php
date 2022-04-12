<?php 
	class FuenteDAO {
		public function insert($fuente,$db){
			$result = $db->_query("INSERT INTO fuentes(fuente,usureg) VALUES('$fuente->fuente','$fuente->usureg')");
            if($result){ 
				echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; 
			}else{
				echo "Ingrese un Nivel";
			}
		}
		
		public function update($fuente,$db){
			$update = $db->_query("UPDATE fuentes
                                        SET fuente='$fuente->fuente',idestado='$fuente->estado'
                                        WHERE idfuente='$fuente->id_ft'");
            echo "Actualizado Correctamente";
		}
	}