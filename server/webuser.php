<?php

	class WebUser
	{
		private $uid;
		private $uname;
		private $uemail;
		private $utype;
		private $userArray;
		private $fname;
		private $lname;

		const TYPE_SELF="SELF";			//login types
		const TYPE_GUEST="GUEST";
		
		function __construct()
		{
			$num=func_num_args();
			if($num>2){
				$this->uid=func_get_arg(0);
				$this->uemail=func_get_arg(1);
				$this->utype=func_get_arg(2);
			}
			else{
				$this->uid=func_get_arg(0);
				$this->utype=func_get_arg(1);
			}
		}

		public function getUID(){
			return $this->uid;
		}
		public function getUName(){
			return $this->uname;
		}
		public function getUEmail(){
			return $this->uemail;
		}
		public function getType(){
			return $this->utype;
		}
		public function getFirstName(){
			return $this->fname;
		}
		public function getLastName(){
			return $this->lname;
		}

		public function setSession(){

			$database=new Database();
			$db=$database->getDbConnection();

			$sql="SELECT * FROM user WHERE u_id='".$this->getUID()."' AND u_email='".$this->getUEmail()."'";

			$data=mysqli_query($db,$sql);

			if(mysqli_num_rows($data)>0){
				
				$row=mysqli_fetch_assoc($data);

				$_SESSION['id']=$row['u_id'];
	  			
	  			if($row['u_name']===NULL){
					$_SESSION['name']='User';
					$this->fname='User';
					$this->lname='';
				}
				else{
					$_SESSION['name']=$row['u_name'];
					$this->fname=substr($_SESSION['name'],0,strpos($_SESSION['name']," "));
	  				$this->lname=substr($_SESSION['name'],strpos($_SESSION['name']," ")+1);
				}
	  			
	  			$_SESSION['email']=$row['u_email'];
		  		
	  			$this->uname=$_SESSION['name'];

	  			$this->userArray=$row;
			}
			$database->closeDb();
		}

		public function detail(){
			return $this->userArray;
		}

		public function setSelfSession(){
			$this->setSession();
		}

		public function breadcrumb(){

			$uri=explode('/', $_SERVER['PHP_SELF']);
			array_shift($uri);
			array_pop($uri);

			$bread=array();
			$url=$_SERVER['PHP_SELF'];
			$pos=1;
			
			while(($pos=strpos($url, '/',$pos))!==false){
				
				$bread[current($uri)]=substr($url,0,$pos);
				next($uri);
				$pos++;
			}

			$crumb="";
			foreach ($bread as $key => $value) {
				$crumb.="<a href='".$value."'>".$key."</a><span>/</span>";
			}

			return $crumb;
		}
	}

?>