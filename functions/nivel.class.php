<?PHP
	require_once('DAO/nivelDAO.class.php');
	class Nivel extends NivelDAO  {
		public $nivel;
		public $estado;
		public $usureg;
		public $id_nivel;
				
		function __construct($nivel='',$estado='',$usureg='',$id=''){
			$this->nivel=$nivel;
			$this->estado=$estado;
			$this->usureg=$usureg;
			$this->id_nivel=$id;
		}
	        
	}
