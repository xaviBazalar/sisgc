<?php
class dat_gest {

	public $tab_n;
	
	function __construct($nivel=""){
		$nivel=$_SESSION['nivel'];
		$this->tab_n=$nivel;
	}

	public function tabs($nivel=""){

		if($nivel=="1" or $nivel=="2" or $nivel=="5" or $nivel=="4"){
			echo "<li class='TabbedPanelsTab' tabindex='0'>Clientes</li>";
		}	
		
		if($nivel=="2" or $nivel=="3"){		
			echo "<li class='TabbedPanelsTab' tabindex='1'>Gesti&oacute;n</li>";
		}

		if($nivel=="1" or $nivel=="2" or $nivel=="5"){		
			echo " <li class='TabbedPanelsTab' tabindex='3'>Direcciones</li>
				  <li class='TabbedPanelsTab' tabindex='4'>Tel&eacute;fonos</li>
				  ";
		}		
		
	/*	if($nivel=="3"){
			echo "<li class=\"TabbedPanelsTab\" tabindex=\"1\">Gesti&oacute;n</li>";
		}*/
	}
}

?>