<?PHP
	require_once('DAO/monedaDAO.class.php');
	
	class Moneda extends MonedaDAO  {
		public $moneda;
		public $simbol;
		public $estado;
		public $usureg;
		public $id_nivel;
				
		function __construct($moneda='',$simbol='',$estado='',$usureg='',$id=''){
			$this->moneda=$moneda;
			$this->simbol=$simbol;
			$this->estado=$estado;
			$this->usureg=$usureg;
			$this->id_moneda=$id;
		}
	        
	}
