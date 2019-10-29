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
	<title>Personal Detail</title>

	<link rel="stylesheet" type="text/css" href="../css/account_common.css">
	<link rel="stylesheet" type="text/css" href="../css/account_personal.css">

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
	<div class="heading">
		<h2>Personal Detail</h2>
	</div>
	<div class="form_cont">
		<div>
			<form action="#" method="post" id="manage_personal_form">
				<div>
					<label>Name</label>
					<input type="text" name="name">
				</div>
				<div>
					<label>Age</label>
					<input type="text" name="age">
				</div>
				<div>
					<label>Gender</label>
					<div>
						<input type="radio" name="gender" value="male">Male
						<input type="radio" name="gender" value="female">Female	
					</div>
				</div>
				<div>
					<label>Mobile</label>
					<input type="text" name="mobile">
				</div>
				<div>
					<label>Address</label>
					<textarea name="address"></textarea>
				</div>
				<div>
					<button type="submit">Save Changes</button>
				</div>
			</form>
		</div>
	</div>
	<div class="loadme"></div>
	<script type="text/javascript">
		
		$(document).ready(function(){

			$("#manage_personal_form").on("submit",function(e){
				e.preventDefault();

				var name=this.name.value;
				var age=this.age.value;
				var gender=this.gender.value;
				var mobile=this.mobile.value;
				var addr=this.address.value;

				$(this).manager({
					url:"../server/usermanager.php",
					data: {
						email:his.email,
						name:name,
						age:age,
						gender:gender,
						mobile:mobile,
						address:addr,
						action:"manage_personal"
					},
					callbackfunc:function(data){
						console.log(data)
						$(".loadme").hide();
						if(JSON.parse(data).success){
							alert("Suucessfully Updated");
						}	
												
					}	
				});

			});

		});
	</script>
</body>
</html>