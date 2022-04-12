<?PHP
	require_once('DAO/usuarioDAO.class.php');
	class Usuario extends UsuarioDAO  {
		public $nombre;
		public $doc;
		public $ndoc;
		public $fn;
		public $dom;
		public $fono;
		public $email;
		public $usrs;
		public $upas;
		public $fi;
		public $cart;
		public $niv;
		public $est;
		public $dpto;
		public $prov;
		public $dist;
		public $prove;
		public $modal;
		public $empresa;
		public $turno;
		public $hors;
		public $horlv;
		
		function __construct($nombre='',$doc='',$fecN='',$dir='',$fono='',$mail='',$usrs='',$upas='',$fecI='',$cart=''
							,$niv='',$est='',$dpto='',$prov='',$dist='',$prove='',$modal='',$empresa='',$turno='',$hors='',$horlv=''){
			$this->nombre=$nombre;
			$this->doc=$doc;
			$this->ndoc=$ndoc;
			$this->fn=$fecN;
			$this->dom=$dir;
			$this->fono=$fono;
			$this->email=$mail;
			$this->usrs=$usrs;
			$this->upas=$upas;
			$this->fi=$fecI;
			$this->cart=$cart;
			$this->niv=$niv;
			$this->est=$est;
			$this->dpto=$dpto;
			$this->prov=$prov;
			$this->dist=$dist;
			$this->prove=$prove;
			$this->modal=$prove;
			$this->empresa=$prove;
			$this->turno=$prove;
			$this->hors=$prove;
			$this->horlv=$prove;
		}
	        
	}

		