<?php 

	class NivelDAO {
		public function insert($nivel,$db){
				$result = $db->_query("INSERT INTO niveles(nivel,usureg) VALUES('$nivel->nivel','$nivel->usureg')");
				if($result){echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; }
				else{
					echo "Ingrese un Nivel";
				}
				
		}
		
		public function update($nivel,$db){
				 $update = $db->_query("UPDATE niveles
                                        SET nivel='$nivel->nivel',idestado=$nivel->estado
                                        WHERE idnivel=$nivel->id_nivel");
                echo "Actualizado Correctamente";
				
		}
	}