<?php

function result_eq($x,$y=false){
		$res="";
	switch($x){
		case 7:
			$res="CD";
		break;
		case 1:
			$res="CD";
		break;
		case 2:
			$res="CD";
		break;
		case 6:
			$res="CD";
		break;
		case 17:
			$res="CD";
		break;
		case 12:
			$res="NC";
		break;
		case 13:
			$res="NC";
		break;
		case 10:
			$res="NC";
		break;
		case 9:
			$res="CD";
		break;
		case 11:
			$res="CI";
		break;
	}
	
	if($y && $y==153){
		$res="CD";
	}
	return $res;
}


?>