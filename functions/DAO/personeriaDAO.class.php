<?php 

	class PersoneriaDAO {
		public function insert($personeria,$db){
			$result = $db->_query("INSERT INTO personerias(personerias,usureg) VALUES('$personeria->personeria','$personeria->usureg')");
				if($result){ 
					echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; 
				}else{
					echo "Ingrese un Nivel";
				}
		}
		
		public function update($personeria,$db){
				$update = $db->_query("UPDATE personerias
                                        SET personerias='$personeria->personeria',idestado=$personeria->estado
                                        WHERE idpersoneria=$personeria->id_person");
                echo "Actualizado Correctamente";
		}
	}