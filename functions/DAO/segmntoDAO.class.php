<?php 
	class SegmentoDAO {
		public function insert($segmento,$db){
			
			$result = $db->_query("INSERT INTO segmentos(segmentos,usureg) VALUES('$segmento->segmento','$segmento->usureg')");
			
			if($result){ 
				echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; 
			}else{
				echo "No se pudo registrar los datos";
			}
		}
		
		public function update($segmento,$db){
			
			$update = $db->_query("UPDATE segmentos
                                        SET segmentos='$segmento->segmento',idestado='$segmento->estado'
                                        WHERE idsegmento=$segmento->id_seg");
                echo "Actualizado Correctamente";
		}
	}