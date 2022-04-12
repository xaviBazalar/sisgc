<?php 

	require_once('DAO/productoDAO.class.php');
	
	class Producto extends ProductoDAO  {
		public $producto;
		public $seg;
		public $proveedor;
		public $estado;
		public $usureg;
		public $id_prd;
		
		function __construct($producto='',$seg='',$provee='',$estado='',$usureg='',$id=''){
			$this->producto=$producto;
			$this->seg=$seg;
			$this->proveedor=$provee;
			$this->estado=$estado;
			$this->usureg=$usureg;
			$this->id_prd=$id;
		}
	}