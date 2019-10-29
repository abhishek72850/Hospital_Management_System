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
	<title>Account Management</title>
	<link rel="stylesheet" type="text/css" href="../css/header.css">
	<link rel="stylesheet" type="text/css" href="../css/account_index.css">
	<link rel="stylesheet" type="text/css" href="../css/featherlight.min.css" />

	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/featherlight.js"></script>
</head>
<body>
	<script type="text/javascript">
		var his={
			islogin:<?php if($user->getType()!=WebUser::TYPE_GUEST)echo "true";else echo "false"; ?>,
			id:'<?php echo $user->getUID() ?>',
			email:'<?php echo $user->getUEmail() ?>'
		};
	</script>
	<div id="body_cont">
		<header class="sub_sec">
			<div class="header_cont">
				<div class="logo_cont">
					<img src="#">
				</div>
				<div class="profile_cont <?php if($user->getType()==WebUser::TYPE_SELF){ echo 'profile_logged_cont';} ?>">
					<button id="loginCheck">
						<?php
							if($user->getType()==WebUser::TYPE_SELF){
 								echo $user->getFirstName();
 							}
 							else if($user->getType()==WebUser::TYPE_GUEST){
 								echo $_SESSION['name'];
 							}
						?>
					</button>
					<?php
						if($user->getType()==WebUser::TYPE_SELF){
					?>
					<div class="logout_cont">
						<a href="#">View Profile</a>
						<a href="../logout.php">Logout</a>
					</div>
					<?php
						}
					?>
				</div>
			</div>
		</header>
		<section>
			<div class="account_cont">
				<div class="sub_cont nav_cont">
					<ul>
						<li class="click_nav_bar">
							<div class="nav_bars">
								<div></div>
								<div></div>
								<div></div>
							</div>
						</li>
						<li>
							<a href="account_detail.php" target="account_frame">
								<img src="../images/icon-account.png">
								<span>Account Detail</span>
							</a>
						</li>
						<li>
							<a href="account_personal.php" target="account_frame">
								<img src="../images/icon-personal.png">
								<span>Personal Detail</span>
							</a>
						</li>
						<li>
							<a href="account_manage_patients.php" target="account_frame">
								<img src="../images/icon-add-patient.png">
								<span>Manage Patient</span>
							</a>
						</li>
						<li>
							<a href="account_appointment.php" target="account_frame">
								<img src="../images/icon-appointment.png">
								<span>Appointments</span>
							</a>
						</li>
						<li>
							<a href="account_fav_hospital.php" target="account_frame">
								<img src="../images/icon-fav-hospital.png">
								<span>Favourite Hospital</span>
							</a>
						</li>
					</ul>
				</div>
				<div class="sub_cont display_cont">
					<iframe src="account_detail.php" name="account_frame"></iframe>
				</div>	
			</div>
		</section>
	</div>
	<script type="text/javascript">
		
		$(document).ready(function(){

			$(".click_nav_bar").on("click",function(){
				$(".nav_cont>ul").toggleClass("nav_clicked");
			});
		});

		var triggerLight=function(action){
			$.featherlight({iframe: 'add_patient.php?'+action,loading:'Please wait...', iframeMaxWidth: '100%', iframeWidth: 300,iframeHeight: 350});
		}

		var triggerChangeApp=function(aid,docid){
			$.featherlight({iframe: 'change_appointment.php?aid='+aid+"&docid="+docid,loading:'Please wait...', iframeMaxWidth: '100%', iframeWidth: 400,iframeHeight: 200});
		}
	</script>
</body>
</html>