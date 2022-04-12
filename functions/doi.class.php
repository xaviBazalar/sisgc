<?php 

	require_once('DAO/doiDAO.class.php');
	
	class Doi extends DoiDAO  {
		public $doi;
		public $estado;
		public $usureg;
		public $id_doi;
				
		function __construct($doi='',$estado='',$usureg='',$id=''){
			$this->doi=$doi;
			$this->estado=$estado;
			$this->usureg=$usureg;
			$this->id_doi=$id;
		}
	}