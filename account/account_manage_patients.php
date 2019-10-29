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
	<title>Manage Patients</title>

	<link rel="stylesheet" type="text/css" href="../css/account_common.css">
	<link rel="stylesheet" type="text/css" href="../css/account_manage_patients.css">
	<link rel="stylesheet" type="text/css" href="../css/featherlight.min.css" />

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
		<h2>Add or Manage Patients</h2>
	</div>
	<div class="manage_cont">
		<?php

			$query="SELECT * FROM patient p,address a WHERE p.p_id=a.r_id and p.u_id=".$_SESSION['id'];

			$result=mysqli_query($db,$query);

			while($row=mysqli_fetch_assoc($result)){
				
		?>
		<div class="sub_cont patient_tab_cont">
			<div>
				<div>
					<span>Name: <?php echo $row['p_name']; ?></span>
				</div>
				<div>
					<span>Age: <?php echo $row['p_age']; ?></span>
					<span>Gender: <?php echo $row['p_gender']; ?></span>
				</div>
				<div>
					<address> <?php echo $row['addr_text']; ?></address>
				</div>
			</div>
			<div>
				<button id="edit_patient" data-pid="<?php echo $row['p_id'] ?>" data-action="edit_patient">Edit</button>
				<button id="remove_patient" data-pid='<?php echo $row['p_id'] ?>' data-action='remove_patient'>Remove</button>
			</div>	
		</div>
		<?php
			}
		?>
		<div class="sub_cont patient_add_tab">
			<span>+</span>
		</div>

	</div>

	<script type="text/javascript">

		$(document).ready(function(){
		
			$('.patient_add_tab').on("click",function(){
				window.parent.triggerLight("action=add_patient");
			});

			$('#edit_patient').on("click",function(){

				var pid=this.dataset.pid;
				var action=this.dataset.action;

				window.parent.triggerLight("action="+action+"&pid="+pid);
			});

			$('#remove_patient').on("click",function(){

				var pid=this.dataset.pid;
				var action=this.dataset.action;

				$(this).manager({
					url:"../server/usermanager.php",
					data: {
						email:his.email,
						action:action,
						pid:pid
					},
					callbackfunc:function(data){
						console.log(data);
						if(JSON.parse(data).success){
							window.parent.location="index.php";
						}	
												
					}	
				});
			});
		});
	</script>

</body>
</html>