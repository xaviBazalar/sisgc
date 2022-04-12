<?php 
	class PeriodoDAO {
		public function insert($periodo,$db){
			$result = $db->_query("INSERT INTO periodos(periodo,fecini,fecfin,usureg) VALUES('$periodo->periodo','$periodo->fec_ini','$periodo->fec_fin','$periodo->usureg')");
            if($result){ 
				echo "<font color='red'>"."Dato Registrado Correctamente"."</font>"; 
			}else{
				echo "Ingrese un Nivel";
			}
		}
		
		public function update($periodo,$db){
			$update = $db->_query("UPDATE periodos
                                        SET periodo='$periodo->periodo',fecini='$periodo->fec_ini',fecfin='$periodo->fec_fin'
                                        WHERE idperiodo='$periodo->id_periodo'");
            echo "Actualizado Correctamente";
		}
	}