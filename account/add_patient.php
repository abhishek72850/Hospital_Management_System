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
	<title>Add Patient</title>
	<link rel="stylesheet" type="text/css" href="../css/add_patient.css">

	<script type="text/javascript" src="../js/jquery.js"></script>
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
	<div>
		<form action="#" method="post" id="add_patient_form">
			<div>
				<label>Patient Name</label>
				<input type="text" name="pname">
			</div>
			<div>
				<label>Age</label>
				<input type="text" name="age">
			</div>
			<div>
				<label>Gender</label>
				<input type="radio" name="gender" value="male">Male 
				<input type="radio" name="gender" value="female">Female
			</div>
			<div>
				<label>Mobile</label>
				<input type="text" name="mobile">
			</div>
			<div>
				<label>Address</label>
				<input type="text" name="address">
			</div>
			<div>
				<input type="hidden" name="action" value="<?php echo $_GET['action']; ?>">
				<input type="hidden" name="pid" value="<?php if(isset($_GET['pid'])) echo $_GET['pid']; ?>">
				<button type="submit">Add/Update Patient</button>
			</div>
		</form>
	</div>

	<script type="text/javascript">

		$(document).ready(function(){
		
			$("#add_patient_form").on("submit",function(e){

				e.preventDefault();

				var name=this.pname.value;
				var age=this.age.value;
				var gender=this.gender.value;
				var mobile=this.mobile.value;
				var address=this.address.value;
				var action=this.action.value;
				var pid=this.pid.value;

				$(this).manager({
					url:"../server/usermanager.php",
					data: {
						email:his.email,
						uid:his.id,
						name:name,
						age:age,
						gender:gender,
						mobile:mobile,
						address:address,
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