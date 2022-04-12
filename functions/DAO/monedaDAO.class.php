<?php 

	class MonedaDAO {
		public function insert($moneda,$db){
				$result = $db->_query("INSERT INTO monedas(monedas,simbolo,usureg) VALUES('$moneda->moneda','$moneda->simbol','$moneda->usureg')");
					if($result){ 
						echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; 
					}else{
						echo "Ingrese una Moneda";
					}
		}
		
		public function update($moneda,$db){
				$update = $db->_query("UPDATE monedas
                                        SET monedas='$moneda->moneda',idestado=$moneda->estado,simbolo='$moneda->simbol'
                                        WHERE idmoneda=$moneda->id_moneda");
                echo "Actualizado Correctamente";
		}
	}