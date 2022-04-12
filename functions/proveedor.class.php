<?php 

	require_once('DAO/proveedorDAO.class.php');
	
	class Proveedor extends ProveedorDAO  {
		public $proveedor;
		public $iddoi;
		public $idpersoneria;
		public $coddpto;
		public $codprov;
		public $coddist;
		public $tel;
		public $doc;
		public $cntac;
		public $obs;
		public $estado;
		public $usureg;
		public $id_prove;
		
		function __construct($proveedor='',$iddoi='',$idpersoneria='',$coddpto='',$codprov='',$coddist='',$tel='',$doc='',
							 $cntac='',$obs='',$estado='',$usureg='',$id=''){
			$this->proveedor=$proveedor;
			$this->iddoi=$iddoi;
			$this->idpersoneria=$idpersoneria;
			$this->coddpto=$coddpto;
			$this->codprov=$codprov;
			$this->coddist=$coddist;
			$this->tel=$telefonos;
			$this->doc=$documento;
			$this->cntac=$cntac;
			$this->obs=$obs;
			$this->estado=$estado;
			$this->usureg=$usureg;
			$this->id_prove=$id;
		}
	}