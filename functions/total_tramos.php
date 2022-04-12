<?php
			
				if($query->fields['idresultado']==7){
						$df_pago[$prioridad]=$df_pago[$prioridad]+1;// DIFICULTAD DE PAGO
				}
				if($query->fields['idresultado']==13){
						$nc_fs[$prioridad]=$nc_fs[$prioridad]+1;// NO CONTESTAN  FS
				}
		
				if($query->fields['idresultado']==11 && $query->fields['idjustificacion']==109){
						$msg_ref[$prioridad]=$msg_ref[$prioridad]+1;// MENSAJE CON REFERENCIA
				}
				
				if($query->fields['idresultado']==11 && $query->fields['idjustificacion']==111){
						$msg_ref[$prioridad]=$msg_ref[$prioridad]+1;// MENSAJE CON REFERENCIA
				}
				
				if($query->fields['idresultado']==11 && $query->fields['idjustificacion']==153){
						$msg_con[$prioridad]=$msg_con[$prioridad]+1;// MENSAJE CON CONYUGE
				}
				
				if($query->fields['idresultado']==11 && $query->fields['idjustificacion']==110){
						$msg_ter[$prioridad]=$msg_ter[$prioridad]+1;// MENSAJE CON TERCEROS
				}
				
				if($query->fields['idresultado']==12){
						$nc_fs[$prioridad]=$nc_fs[$prioridad]+1;// NO CONTESTAN FS
				}
				
				if($query->fields['idresultado']==2){
						$pro_p[$prioridad]=$pro_p[$prioridad]+1;//PROMESA DE PAGO
				}
				
				if($query->fields['idresultado']==9){
						$recl[$prioridad]=$recl[$prioridad]+1;//RECLAMO
				}
				
				if($query->fields['idresultado']==17){
						$renue[$prioridad]=$renue[$prioridad]+1;//RENUENTE
				}
				
				if($query->fields['idresultado']==6){
						$segi[$prioridad]=$segi[$prioridad]+1;//OBSERVACION
				}
				
				if($query->fields['idresultado']==1){
						$yapa[$prioridad]=$yapa[$prioridad]+1;//DICE QUE PAGO
				}
				
				if($query->fields['idresultado']==10){
						$falla[$prioridad]=$falla[$prioridad]+1;//FALLECIDO
				}
				
?>