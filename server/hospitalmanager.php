<?php

	require_once('dbconfig.php');

	class HospitalManager{

		private $db;

		function __construct(){
			$database=new Database();
			$this->db=$database->getDbConnection();
		}

		public function doSearch($search){

			$search=strtolower(trim($search));
			$search2="+".strtr($search, " ,-_=","+++++");

			$sql="SELECT * FROM hospital h,address a WHERE MATCH(h_name, tags) AGAINST('$search' IN NATURAL LANGUAGE MODE) AND h.h_id=a.r_id";

			$data=mysqli_query($this->db,$sql);

			if(mysqli_num_rows($data)>0){

				$list=array();

				while($row=mysqli_fetch_assoc($data)){
					array_push($list, $row);
				}
				return array("data"=>$list,"success"=>true);
			}
			else{
				return array("error"=>"No Hospital Found","success"=>false);
			}
		}

		public function doDoctorSearch($did){

			$sql="SELECT * FROM doctor d,time_slot t WHERE d.d_id=$did and d.doc_id=t.doc_id";

			$data=mysqli_query($this->db,$sql);

			if(mysqli_num_rows($data)>0){

				$list=array();

				while($row=mysqli_fetch_assoc($data)){
					array_push($list, $row);
				}
				return array("data"=>$list,"success"=>true);
			}
			else{
				return array("error"=>"No Doctor Found","success"=>false);
			}	
		}

		public function doSetAppointment($pid,$uid,$pname,$age,$gender,$addr,$mobile,$did,$app_date,$app_time){

			if($pid!="0"){

				$sql="INSERT INTO appointment(u_id,p_id,d_id,a_date,a_time) VALUES('$uid','$pid','$did','$app_date','$app_time')";

				$result=mysqli_query($this->db,$sql);

				if($result){
					return array('success' => true);
				}
				else{
					return array('error'=>"Unable to Insert",'success'=>false);
				}
			}
			else{

				$sql="INSERT INTO patient(u_id,p_name,p_phone,p_age,p_gender) VALUES('$uid','$pname','$mobile','$age','$gender')";

				$result=mysqli_query($this->db,$sql);

				if($result){
					$sql="SELECT p_id FROM patient WHERE u_id='$uid' AND p_phone='$mobile' AND p_name='$pname'";
					
					$result=mysqli_query($this->db,$sql);

					$row=mysqli_fetch_assoc($result);

					$pid=$row['p_id'];

					$sql="INSERT INTO address(r_id,addr_text) VALUES('$pid','$addr')";

					$result=mysqli_query($this->db,$sql);

					if($result){
						$sql="INSERT INTO appointment(u_id,p_id,d_id,a_date,a_time) VALUES('$uid','$pid','$did','$app_date','$app_time')";

						$result=mysqli_query($this->db,$sql);

						if($result){
							return array('success' => true);
						}
						else{
							return array('error'=>"Unable to Insert Appointment",'success'=>false);
						}
					}
					else{
						return array('error'=>"Unable to Insert Address",'success'=>false);
					}
				}
				else{
					return array('error'=>"Unable to Insert Patient",'success'=>false);
				}
			}

		}

		public function doChangeAppointment($aid,$app_date,$app_time){

			$sql="UPDATE appointment SET a_date='$app_date', a_time='$app_time' WHERE a_id=$aid";

			$result=mysqli_query($this->db,$sql);

			if($result){
				return array('success' => true);
			}
			else{
				return array('error'=>"Unable to Change Appointment",'success'=>false);
			}
		}

		public function doRemoveAppointment($aid){

			$sql="DELETE FROM appointment WHERE a_id=$aid";

			$result=mysqli_query($this->db,$sql);

			if($result){
				return array('success' => true);
			}
			else{
				return array('error'=>"Unable to Remove Appointment",'success'=>false);
			}			
		}

	}

	if(isset($_POST['action'])){

		$hospital = new HospitalManager();

		if($_POST['action']=='search'){
			echo json_encode($hospital->doSearch($_POST['query']));
		}
		elseif($_POST['action']=='doc_search'){
			echo json_encode($hospital->doDoctorSearch($_POST['did']));
		}
		elseif($_POST['action']=='set_appointment'){
			echo json_encode($hospital->doSetAppointment($_POST['pid'],$_POST['uid'],$_POST['pname'],$_POST['page'],$_POST['pgender'],$_POST['paddr'],$_POST['pmobile'],$_POST['did'],$_POST['app_date'],$_POST['app_time']));
		}
		elseif($_POST['action']=='change_appointment'){
			echo json_encode($hospital->doChangeAppointment($_POST['aid'],$_POST['app_date'],$_POST['app_time']));
		}
		elseif($_POST['action']=='remove_appointment'){
			echo json_encode($hospital->doRemoveAppointment($_POST['aid']));
		}

	}

?>