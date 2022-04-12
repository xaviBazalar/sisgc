<?php 

	require_once('DAO/periodoDAO.class.php');
	
	class Periodo extends PeriodoDAO  {
		public $periodo;
		public $fec_ini;
		public $fec_fin;
		public $estado;
		public $usureg;
		public $id_periodo;
				
		function __construct($periodo='',$fec_ini='',$fec_fin='',$estado='',$usureg='',$id=''){
			$this->periodo=$periodo;
			$this->fec_ini=$fec_ini;
			$this->fec_fin=$fec_fin;
			$this->estado=$estado;
			$this->usureg=$usureg;
			$this->id_periodo=$id;
		}
	}