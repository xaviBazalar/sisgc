<?php 

	require_once('DAO/personeriaDAO.class.php');
	
	class Personeria extends PersoneriaDAO  {
		public $personeria;
		public $estado;
		public $usureg;
		public $id_person;
				
		function __construct($personeria='',$estado='',$usureg='',$id=''){
			$this->personeria=$personeria;
			$this->estado=$estado;
			$this->usureg=$usureg;
			$this->id_person=$id;
		}
	}