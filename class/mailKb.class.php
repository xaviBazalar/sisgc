<?php
	session_start();
	class mailKb {
		public $user_r;
		public $id;
		public $mail;
		public $ori;
		public $ft;
		public $obs;
		public $prio;
		public $debug;
		
		function __construct(){
			$this->user_r=$_SESSION['iduser'];
			$this->debug=false;
		}
		
		public function setEmail($id,$mail,$ori,$ft,$obs,$prio){
			$this->id=$id;
			$this->mail=$mail;
			$this->ori=$ori;
			$this->ft=$ft;
			$this->obs=$obs;
			$this->prio=$prio;
		}
		
		public function InsertMail($db){
			
			$sql="INSERT into emails(idfuente,idorigenemail,idcliente,email,prioridad,observacion,usureg)
				 VALUES('$this->ft','$this->ori','$this->id','$this->mail','$this->prio','$this->obs','$this->user_r')";
			
			$rs=$db->Execute($sql);
			if(!$rs){
				echo $db->ErrorMsg();
				return false;
			}
		}
		
		public function updateMail($db){
			if($this->prio==1){
				$sql="UPDATE emails SET prioridad='0' where idcliente='$this->id'";
				$rs=$db->Execute($sql);
				if(!$rs){
					echo $db->ErrorMsg();
					return false;
				}
			}
		}
		
		
		

	}

?>