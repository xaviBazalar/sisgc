<?php 
	class DoiDAO {
		public function insert($doi,$db){
			$result = $db->_query("INSERT INTO doi(doi,usureg) VALUES('$doi->doi','$doi->usureg')");
            if($result){ 
				echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; 
			}else{
				echo "Ingrese un Nivel";
			}
		}
		
		public function update($doi,$db){
			$update = $db-> _query("UPDATE doi
                                    SET doi='$doi->doi',idestado=$doi->estado
                                    WHERE iddoi=$doi->id_doi");
            echo "Actualizado Correctamente";
		}
	}