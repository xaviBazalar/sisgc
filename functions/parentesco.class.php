<?PHP
	require_once('DAO/parentescoDAO.class.php');
	
	class Parentesco extends ParentescoDAO  {
		public $parentesco;
		public $estado;
		public $usureg;
		public $id_nivel;
				
		function __construct($parentesco='',$estado='',$usureg='',$id=''){
			$this->parentesco=$moneda;
			$this->estado=$estado;
			$this->usureg=$usureg;
			$this->id_parent=$id;
		}
	}