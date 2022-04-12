<?php 

	require_once('DAO/fuenteDAO.class.php');
	
	class Fuente extends FuenteDAO  {
		public $fuente;
		public $estado;
		public $usureg;
		public $id_ft;
				
		function __construct($fuente='',$estado='',$usureg='',$id=''){
			$this->fuente=$periodo;
			$this->estado=$estado;
			$this->usureg=$usureg;
			$this->id_ft=$id;
		}
	}