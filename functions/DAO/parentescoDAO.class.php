<?php 

	class ParentescoDAO {
		public function insert($parentesco,$db){
				$result = $db->_query("INSERT INTO parentescos(parentescos,usureg) VALUES('$parentesco->parentesco','$parentesco->usureg')");
				if($result){ 
					echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; 
				}else{
					echo "Ingrese un Nivel";
				}
		}
		
		public function update($parentesco,$db){
				$update = $db->_query("UPDATE parentescos
                                        SET parentescos='$parentesco->parentesco',idestado=$parentesco->estado
                                        WHERE idparentesco=$parentesco->id_parent");
                echo "Actualizado Correctamente";
		}
	}