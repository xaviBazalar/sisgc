<?php

function rango_hora($x){
		$r_hora="";
	switch($x){
		case 7:
			$r_hora="07:00-07:59";
		break;
		case 8:
			$r_hora="08:00-08:59";
		break;
		case 9:
			$r_hora="09:00-09:59";
		break;
		case 10:
			$r_hora="10:00-10:59";
		break;
		case 11:
			$r_hora="11:00-11:59";
		break;
		case 12:
			$r_hora="12:00-12:59";
		break;
		case 13:
			$r_hora="13:00-13:59";
		break;
		case 14:
			$r_hora="14:00-14:59";
		break;
		case 15:
			$r_hora="15:00-15:59";
		break;
		case 16:
			$r_hora="16:00-16:59";
		break;
		case 17:
			$r_hora="17:00-17:59";
		break;
		case 18:
			$r_hora="18:00-18:59";
		break;
		case 19:
			$r_hora="19:00-19:59";
		break;
		case 20:
			$r_hora="20:00-20:59";
		break;
	

	}
	
	return $r_hora;
}


?>