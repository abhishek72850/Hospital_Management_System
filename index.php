<?php
	session_start();

	require_once('server/dbconfig.php');
	require_once('server/webuser.php');
	
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
		$user=new WebUser(substr(session_id(),0,20),WebUser::TYPE_GUEST);
		$_SESSION['id']=$user->getUID();
		$_SESSION['name']='Login/Signup';
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/home.css">
	<link rel="stylesheet" type="text/css" href="css/featherlight.min.css" />

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/featherlight.js"></script>
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
						<a href="account/">View Profile</a>
						<a href="logout.php">Logout</a>
					</div>
					<?php
						}
					?>
				</div>
			</div>
		</header>
		<section class="sub_sec">
			<div class="search_form_cont">
				<form action="#" method="post" id="searchForm">
					<div>
						<input type="text" name="search" placeholder="Enter State or Hospital Name">
						<button type="submit"><img src="images/search.png"></button>
					</div>
				</form>
			</div>
			<div class="result_cont">
				<div class="card_cont">

					<!-- <div class="result_card">
						<div class="card_sub_sec">
							<img src="#">
						</div>
						<div class="card_sub_sec">
							<div>
								<h4>Hospital Name</h4>
								<address>Hospital Address</address>
								<span>Rating</span>
							</div>
						</div>
						<div class="card_sub_sec">
							<button>View</button>
						</div>
					</div> -->
				</div>
			</div>
		</section>
		<footer class="sub_sec"></footer>
	</div>
	<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript" src="js/app.js"></script>
</body>
</html>