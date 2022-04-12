<?php 

	require_once('DAO/segmntoDAO.class.php');
	
	class Segmento extends SegmentoDAO  {
		public $segmento;
		public $estado;
		public $usureg;
		public $id_seg;
		
		function __construct($segmento='',$estado='',$usureg='',$id=''){
			$this->segmento=$segmento;
			$this->estado=$estado;
			$this->usureg=$usureg;
			$this->id_seg=$id;
		}
	}