<?php
	require_once('dbconfig.php');

	class UserManager{

		private $db;

		function __construct(){
			$database=new Database();
			$this->db=$database->getDbConnection();
		}

		public function doLogin($uemail,$pass){

			$sql="SELECT * FROM user WHERE u_email='$uemail' AND u_password='$pass'";
			
			$data=mysqli_query($this->db,$sql);

			if(mysqli_num_rows($data)>0){
				$row=mysqli_fetch_assoc($data);

				$this->createCookie($row["u_id"],$uemail);
				return array("data"=>$row,"success"=>true);
			}
			else{
				return array("error"=>"User Doesn't Exist","success"=>false);
			}
		}

		public function doSignup($uemail,$pass,$cnfpass){

			if($pass==$cnfpass){
				if($this->doLogin($uemail,$pass)["success"]){
					return array("message"=>"User Exists","success"=>false);
				}
				else{

					$sql="INSERT INTO user(u_email,u_password) VALUES('$uemail','$pass')";

					$result=mysqli_query($this->db,$sql);

					if($result){
						$this->createCookie($this->doLogin($uemail,$pass)["data"]["u_id"],$uemail);
						return array("success"=>true);
					}
					else{
						return array("success"=>false);
					}
				}
			}
			else{
				return array("error"=>"Validation Error","success"=>false);
			}
		}

		public function doEmailChange($nemail,$email,$pass){

			$sql="UPDATE user SET u_email='$nemail' WHERE u_email='$email' AND u_password='$pass'";

			$result=mysqli_query($this->db,$sql);

			if($result){
				return array("success"=>true);
			}
			else{
				return array("success"=>false);
			}
		}

		public function doPasswordChange($email,$cpass,$npass){

			$sql="UPDATE user SET u_password='$npass' WHERE u_email='$email' AND u_password='$cpass'";

			$result=mysqli_query($this->db,$sql);

			if($result){
				return array("success"=>true);
			}
			else{
				return array("success"=>false);
			}	
		}

		public function doPersonalChange($email,$name,$age,$gender,$mobile,$address){

			$sql="UPDATE user SET u_name='$name', u_age='$age', u_gender='$gender', u_phone='$mobile', u_addr='$address' WHERE u_email='$email'";

			$result=mysqli_query($this->db,$sql);

			if($result){
				return array("success"=>true);
			}
			else{
				return array("success"=>false);
			}	
		}

		public function doAddPatient($name,$age,$gender,$mobile,$address,$uid){

			$sql="INSERT INTO patient(u_id,p_name,p_age,p_gender,p_phone) VALUES($uid,'$name',$age,'$gender',$mobile)";

			$result=mysqli_query($this->db,$sql);

			if($result){

				$sql="SELECT p_id FROM patient WHERE u_id=$uid AND p_name='$name' AND p_phone=$mobile AND p_age=$age AND p_gender='$gender'";

				//echo $sql;

				$result=mysqli_query($this->db,$sql);

				$row=mysqli_fetch_assoc($result);				
				
				$pid=$row['p_id'];

				$sql="INSERT INTO address(r_id,addr_text) VALUES($pid,'$address')";
				//echo $sql;

				$result=mysqli_query($this->db,$sql);

				if($result){
					return array("success"=>true);
				}	
				else{
					return array("error"=>"Unable to Insert Address","success"=>false);
				}			
			}
			else{
				return array("error"=>"Unable to Add patient","success"=>false);
			}	
		}

		public function doEditPatient($name,$age,$gender,$mobile,$address,$pid){
			$sql="UPDATE patient SET p_name='$name', p_age=$age, p_gender='$gender', p_phone=$mobile WHERE p_id=$pid";

			$result=mysqli_query($this->db,$sql);

			if($result){

				$sql="UPDATE address SET addr_text='$address' WHERE r_id=$pid";

				$result=mysqli_query($this->db,$sql);

				if($result){
					return array("success"=>true);
				}
				else{
					return array("success"=>false);
				}
			}
			else{
				return array("success"=>false);
			}
		}

		public function doRemovePatient($pid){

			$sql="DELETE FROM patient WHERE p_id=$pid";

			$result=mysqli_query($this->db,$sql);

			if($result){
				$sql="DELETE FROM address WHERE r_id=$pid";

				$result=mysqli_query($this->db,$sql);

				if($result)				
					return array("success"=>true);
				else
					return array("success"=>false);
			}
			else{
				return array("success"=>false);
			}			
		}

		public function doAddToFavourite($uid,$hid){
			$sql="INSERT INTO favourite_hospital(uid,hid) VALUES('$uid','$hid')";

			$result=mysqli_query($this->db,$sql);

			if($result){
				return array("success" => true);
			}
			else{
				return array("error"=>"Unable to Add to Favourite","success"=>false);
			}
		}

		public function doRemoveFavourite($uid,$hid){
			$sql="DELETE FROM favourite_hospital WHERE uid=$uid AND hid=$hid";

			$result=mysqli_query($this->db,$sql);

			if($result){
				return array("success" => true);
			}
			else{
				return array("error"=>"Unable to Remove to Favourite","success"=>false);
			}
		}

		private function createCookie($uid,$email,$type='SELF',$user='User'){
			setcookie('name',$user,time() + 60 * 60 * 24 * 30,'/','',false,true);
			setcookie('id',$uid,time() + 60 * 60 * 24 * 30,'/','',false,true);
			setcookie('email',$email,time() + 60 * 60 * 24 * 30,'/','',false,true);
			setcookie('type',$type,time() + 60 * 60 * 24 * 30,'/','',false,true);
		}
	}

	if(isset($_POST["email"])){

		$user = new UserManager();

		if($_POST["action"]=="signup"){
			echo json_encode($user->doSignup($_POST["email"],$_POST["password"],$_POST["cnfpassword"]));
		}
		elseif ($_POST["action"]=="login") {
			echo json_encode($user->doLogin($_POST["email"],$_POST["password"]));
		}
		elseif($_POST["action"]=="manage_email"){
			if($user->doLogin($_POST["email"],$_POST["password"])["success"]){
				echo json_encode($user->doEmailChange($_POST["cemail"],$_POST["email"],$_POST["password"]));
			}
			else{
				echo json_encode(array("error"=>"User does not Exist","success"=>false));		
			}
		}
		elseif($_POST["action"]=="manage_pass"){
			echo json_encode($user->doPasswordChange($_POST["email"],$_POST["password"],$_POST["npass"]));
		}
		elseif($_POST["action"]=="manage_personal"){
			echo json_encode($user->doPersonalChange($_POST['email'],$_POST['name'],$_POST['age'],$_POST['gender'],$_POST['mobile'],$_POST['address']));
		}
		elseif($_POST["action"]=="add_patient"){
			echo json_encode($user->doAddPatient($_POST['name'],$_POST['age'],$_POST['gender'],$_POST['mobile'],$_POST['address'],$_POST['uid']));
		}
		elseif($_POST["action"]=="edit_patient"){
			echo json_encode($user->doEditPatient($_POST['name'],$_POST['age'],$_POST['gender'],$_POST['mobile'],$_POST['address'],$_POST['pid']));
		}
		elseif($_POST["action"]=="remove_patient"){
			echo json_encode($user->doRemovePatient($_POST['pid']));
		}
		elseif($_POST["action"]=="add_fav"){
			echo json_encode($user->doAddToFavourite($_POST['uid'],$_POST['hid']));
		}
		elseif($_POST["action"]=="remove_fav"){
			echo json_encode($user->doRemoveFavourite($_POST['uid'],$_POST['hid']));
		}
	}

?>