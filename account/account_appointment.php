<?php
	session_start();

	require_once('../server/dbconfig.php');
	require_once('../server/webuser.php');
	
	$user=null;

	$database=new Database();
	$db=$database->getDbConnection();
	//if user is logged in before
	if(isset($_COOKIE["type"])){

		$user=new WebUser($_COOKIE['id'],$_COOKIE['email'],$_COOKIE['type']);

		if($_COOKIE['type']==WebUser::TYPE_SELF){
			$user->setSelfSession();
		}
	}
	else{
		header('location:../');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Appointments</title>

	<link rel="stylesheet" type="text/css" href="../css/account_common.css">
	<link rel="stylesheet" type="text/css" href="../css/account_appointment.css">

	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/featherlight.js"></script>
	<script type="text/javascript" src="../js/ajax.js"></script>
</head>
<body>
	<script type="text/javascript">
		var his={
			islogin:<?php if($user->getType()!=WebUser::TYPE_GUEST)echo "true";else echo "false"; ?>,
			id:'<?php echo $user->getUID() ?>',
			email:'<?php echo $user->getUEmail() ?>'
		};
	</script>
	<div class="heading">
		<h2>Appointments History</h2>
	</div>
	<div class="appointment_cont">
		<?php
			$id=$user->getUID();
			$sql="SELECT * FROM appointment a,patient p,doctor d,department dp,hospital h,address ad WHERE a.u_id=$id and a.p_id=p.p_id and a.d_id=dp.d_id and dp.h_id=h.h_id and d.d_id=dp.d_id and ad.r_id=h.h_id";

			$result=mysqli_query($db,$sql);

			while($row=mysqli_fetch_assoc($result)){

		?>
		<div class="appointment_card">
			<div class="sub_cont app_date_time">
				<span>Appointment Number: <?php echo $row['a_id'] ?></span>
				<span>Date: <?php echo $row['a_date'] ?> and Time: <?php echo $row['a_time'] ?></span>
			</div>
			<div class="sub_cont app_detail">
				<div class="detail_cont patient_detail">
					<div>
						<span><?php echo $row['p_name'] ?></span>
					</div>
					<div>
						<span>Age: <?php echo $row['p_age'] ?></span>
						<span>Gender: <?php echo $row['p_gender'] ?></span>
					</div>
					<div>
						<span>Phone Number: <?php echo $row['p_phone'] ?></span>
					</div>	
				</div>
				<div class="detail_cont hospital_detail">
					<div>
						<span>Doctor Name: <?php echo $row['doc_name'] ?></span>
					</div>
					<div>
						<span>Department: <?php echo $row['d_name'] ?></span>
					</div>
					<div>
						<span>Hospital: <?php echo $row['h_name'] ?></span>
					</div>
					<div>
						<address>Address: <?php echo $row['addr_text'] ?></address>
					</div>
				</div>
			</div>
			<div class="sub_cont app_manage">
				<button data-aid="<?php echo $row['a_id'] ?>" data-docid="<?php echo $row['doc_id'] ?>" data-action="edit_app" class="manage_app">Edit</button>
				<button data-aid="<?php echo $row['a_id'] ?>" data-docid="<?php echo $row['doc_id'] ?>" data-action="remove_app" class="manage_app">Remove</button>
			</div>
		</div>
		<?php
		}
		?>
	</div>
	<script type="text/javascript">
		
		$(document).ready(function(){

			$('.manage_app').on('click',function(){

				var action=this.dataset.action;
				var aid=this.dataset.aid;
				var docid=this.dataset.docid;

				if(action=="edit_app"){
					window.parent.triggerChangeApp(aid,docid);
				}
				else{
					$(this).manager({
						url:"../server/hospitalmanager.php",
						data: {
							aid:aid,
							action:'remove_appointment'
						},
						callbackfunc:function(data){
							data=JSON.parse(data);
							
							console.log(data);

							if(data.success){
								window.parent.location="index.php";
							}
						}
					});
				}
			});
		});
	</script>
</body>
</html>