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
	<title>Account Detail</title>

	<link rel="stylesheet" type="text/css" href="../css/account_common.css">
	<link rel="stylesheet" type="text/css" href="../css/account_detail.css">

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
		<h2>Account Detail</h2>
	</div>
	<div class="ac_detail_cont">
		
		<div class="sub_cont email_cont">
			<form action="#" method="post" id="manage_email_form">
				<fieldset>
					<legend>Manage Email</legend>
					<div>
						<label>Email</label>
						<input type="email" name="email" value="<?php echo $user->getUEmail() ?>">
					</div>
					<div>
						<label>Enter Password</label>
						<input type="password" name="pass" value="" placeholder="Enter Password">
					</div>
					<div>
						<button type="submit">Change</button>
					</div>
				</fieldset>
			</form>
		</div>

		<div class="sub_cont pass_cont">
			<form action="#" method="post" id="manage_pass_form">
				<fieldset>
					<legend>Manage Password</legend>
					<div>
						<label>Current Password</label>
						<input type="text" name="cpass">
					</div>
					<div>
						<label>New Password</label>
						<input type="text" name="npass">
					</div>
					<div>
						<button type="submit">Change</button>
					</div>
				</fieldset>
			</form>
		</div>

	</div>
	<div class="loadme"></div>
	<script type="text/javascript">
		
		$(document).ready(function(){

			$("#manage_email_form").on("submit",function(e){
				e.preventDefault();

				$(".loadme").show();

				var email=this.email.value;
				var pass=this.pass.value;

				$(this).manager({
					url:"../server/usermanager.php",
					data: {
						cemail:email,
						email:his.email,
						password:pass,
						action:"manage_email"
					},
					callbackfunc:function(data){
						$(".loadme").hide();
						if(JSON.parse(data).success){
							window.parent.location="../logout.php";
						}	
												
					}	
				});

				console.log(this.email.value);
			});

			$("#manage_pass_form").on("submit",function(e){
				e.preventDefault();

				$(".loadme").show();

				var cpass=this.cpass.value;
				var npass=this.npass.value;

				$(this).manager({
					url:"../server/usermanager.php",
					data: {
						email:his.email,
						password:cpass,
						npass:npass,
						action:"manage_pass"
					},
					callbackfunc:function(data){
						$(".loadme").hide();
						if(JSON.parse(data).success){
							window.parent.location="../logout.php";
						}	
												
					}	
				});
			});
		});

	</script>
</body>
</html>