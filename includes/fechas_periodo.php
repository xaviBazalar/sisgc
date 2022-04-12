<?php
include_once("../define_con.php");

$perid = $_GET["idperiodo"];
$vSQL = "SELECT fecini, fecfin
	FROM periodos
	WHERE idperiodo = ".$perid;
$rs = $db->Execute($vSQL);

$pos = strpos($rs->fields['fecfin'], "-");
								if($pos){
									$fecs = explode("-",$rs->fields['fecfin']);
									$mes=date("M", mktime(0, 0, 0, $fecs[1], $fecs[2], $fecs[0]));
									if($mes=="Jan"){$mes="Ene";}
									$total=$fecs[2];
									for($i=1;$i<=$total;$i++){
										if($i<10){$dia="0".$i;}else{$dia=$i;}
										if($i==$total){
											echo $mes." ".$i."/".$fecs[0]."-".$fecs[1]."-".$dia."";
											continue;		
										}
										echo $mes." ".$i."/".$fecs[0]."-".$fecs[1]."-".$dia.",";
									}
									
								}
